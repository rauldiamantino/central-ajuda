<?php
namespace app\Roteamento;
use DateTime;
use app\Core\Cache;
use app\Controllers\PublicoController;
use app\Controllers\DashboardController;
use app\Controllers\PaginaErroController;
use app\Controllers\PublicoBuscaController;
use app\Controllers\PublicoArtigoController;
use app\Controllers\DashboardLoginController;
use app\Controllers\DashboardAjusteController;
use app\Controllers\DashboardArtigoController;
use app\Controllers\DashboardEmpresaController;
use app\Controllers\DashboardUsuarioController;
use app\Controllers\PublicoCategoriaController;
use app\Controllers\DashboardCadastroController;
use app\Controllers\DashboardConteudoController;
use app\Controllers\DashboardCategoriaController;
use app\Controllers\Components\DatabaseFirebaseComponent;
use app\Controllers\Components\AssinaturaReceberComponent;

class Roteador
{
  protected $sessaoUsuario;
  protected $paginaErro;

  public function __construct()
  {
    global $sessaoUsuario;
    $this->sessaoUsuario = $sessaoUsuario;
    $this->paginaErro = new PaginaErroController();

    $this->limiteRequisicoes();
  }

  public function rotear()
  {
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $metodo = $_SERVER['REQUEST_METHOD'];
    $metodoOculto = $_POST['_method'] ?? null;

    // Formulário HTML
    if ($metodoOculto and in_array(strtoupper($metodoOculto), ['PUT', 'DELETE'])) {
      $metodo = strtoupper($metodoOculto);
    }

    $chaveRota = $metodo . ':' . $url;
    $partesRota = explode('/', trim($url, '/'));

    if (HOST_LOCAL) {
      $chaveRota = str_replace(RAIZ, '/', $chaveRota);
    }

    // Até criar a landing page
    if ($chaveRota == 'GET:/') {
      header('Location: /login');
      exit;
    }

    if (count($partesRota) > 1) {
      $empresa = reset($partesRota);
      $parteFinal = end($partesRota);
      $id = is_numeric($parteFinal) ? (int) $parteFinal : 0;
    }
    elseif (count($partesRota) == 1) {
      $empresa = reset($partesRota);
      $parteFinal = '';
      $id = 0;
    }
    else {
      $empresa = '';
      $parteFinal = '';
      $id = 0;
    }

    $empresaId = 0;
    $empresaAtivo = 0;
    $assinaturaId = '';
    $assinaturaStatus = 0;
    $gratisVencido = false;
    $gratisPrazo = '';

    $chaveRota = preg_replace('/\b' . preg_quote($id, '/') . '\b/', '{id}', $chaveRota, 1);

    if (! $this->rotaLogin($chaveRota)) {
      $chaveRota = preg_replace('/\b' . preg_quote($empresa, '/') . '\b/', '{empresa}', $chaveRota, 1);
    }

    $rotaRequisitada = $this->acessarRota($chaveRota);

    if (empty($rotaRequisitada)) {
      return $this->paginaErro->erroVer();
    }

    if (! $this->rotaLogin($chaveRota)) {
      $dashboardEmpresa = new DashboardEmpresaController();

      $coluna = 'subdominio';
      $valor = $empresa;
      $cacheTempo = 60 * 60;
      $cacheNome = 'roteador-' . $valor;

      $buscarEmpresa = Cache::buscar($cacheNome);

      if ($buscarEmpresa == null) {
        $buscarEmpresa = $dashboardEmpresa->buscarEmpresaSemId($coluna, $valor);

        if ($buscarEmpresa) {
          Cache::definir($cacheNome, $buscarEmpresa, $cacheTempo);
        }
      }

      $empresaId = intval($buscarEmpresa[0]['Empresa']['id'] ?? 0);
      $empresaAtivo = intval($buscarEmpresa[0]['Empresa']['ativo'] ?? 0);
      $empresa = $buscarEmpresa[0]['Empresa']['subdominio'] ?? '';
      $assinaturaId = $buscarEmpresa[0]['Empresa']['assinatura_id_asaas'] ?? '';;
      $assinaturaStatus = intval($buscarEmpresa[0]['Empresa']['assinatura_status'] ?? 0);
      $gratisPrazo = $buscarEmpresa[0]['Empresa']['gratis_prazo'] ?? '';
    }

    if ($gratisPrazo) {
      $dataHoje = new DateTime('now');
      $dataGratis = new DateTime($gratisPrazo);

      if ($dataGratis <= $dataHoje) {
        $gratisVencido = true;
      }
    }

    // Acesso negado
    if ($empresaAtivo == INATIVO) {
      $empresaId = 0;
    }

    $usuarioLogado = $this->sessaoUsuario->buscar('usuario');

    // Somente suporte acessa Debug
    if (isset($usuarioLogado['padrao']) and $usuarioLogado['padrao'] == USUARIO_SUPORTE) {

      if (isset($_GET['debug']) and $_GET['debug'] == 'true') {
        $this->sessaoUsuario->definir('debugAtivo', true);
      }

      if (isset($_GET['debug']) and $_GET['debug'] == 'false') {
        $this->sessaoUsuario->apagar('debugAtivo');
      }
    }

    // Suporte acessando empresas
    if (isset($usuarioLogado['padrao']) and $usuarioLogado['padrao'] == USUARIO_SUPORTE and $empresaId == 0) {
      $empresaId = $usuarioLogado['empresaId'];

      if (empty($empresa) and ! $this->rotaLogin($chaveRota)) {
        return $this->paginaErro->erroVer();
      }
    }

    // Restrições de acesso por nível
    if ((! isset($usuarioLogado['padrao']) or $usuarioLogado['padrao'] != USUARIO_SUPORTE) and $this->rotaRestritaSuporte($chaveRota)) {
      $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
      header('Location: ' . REFERER);
      exit;
    }

    // Dashboard
    if (strpos($chaveRota, '/{empresa}/dashboard') or strpos($chaveRota, '/{empresa}/d/')) {
      $sucesso = true;
      registrarLog('rota-dash', $chaveRota);
      if (! isset($usuarioLogado['nivel'])) {
        $sucesso = false;
      }
      elseif (! isset($usuarioLogado['id']) or (int) $usuarioLogado['id'] == 0) {
        $sucesso = false;
      }
      elseif (! isset($usuarioLogado['empresaId']) or empty($usuarioLogado['empresaId'])) {
        $sucesso = false;
      }
      elseif ($empresaAtivo == INATIVO and $usuarioLogado['padrao'] != USUARIO_SUPORTE) {
        $this->sessaoUsuario->definir('erro', 'Acesso não autorizado, por favor, entre em contato conosco através do e-mail <span class="font-bold">suporte@360help.com.br</span>');
        $sucesso = false;
      }

      // Usuário deslogado
      if ($sucesso == false) {
        $this->sessaoUsuario->apagar('usuario');

        header('Location: ' . baseUrl('/login'));
        exit;
      }

      if ($this->sessaoUsuario->buscar('acessoBloqueado-' . $usuarioLogado['id'])) {
        $this->sessaoUsuario->definir('erro', 'Acesso bloqueado.');
        $sucesso = false;
      }

      // Restrições de acesso por nível
      if ($sucesso and $usuarioLogado['padrao'] != USUARIO_SUPORTE and $this->rotaRestritaSuporte($chaveRota)) {
        $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
        $sucesso = false;
      }

      if ($sucesso and $usuarioLogado['nivel'] == USUARIO_RESTRITO and $this->rotaRestritaNivel2($chaveRota, $usuarioLogado['id'], $id)) {
        $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
        $sucesso = false;
      }

      if ($sucesso and $usuarioLogado['padrao'] != USUARIO_SUPORTE) {

        if ($gratisVencido and empty($assinaturaId) and $assinaturaStatus == INATIVO and ! $this->rotaAssinaturaVencida($chaveRota)) {
          // Teste expirado
          $this->sessaoUsuario->definir('neutra', 'Ops! Seu período de testes expirou. 😔 <br>
                                                  <a href="/' . $empresa . '/dashboard/empresa/editar?acao=assinar" class="underline font-semibold">Clique aqui</a> para assinar o 360Help!');
          $sucesso = false;
        }
        elseif ($assinaturaId and $assinaturaStatus == INATIVO and ! $this->rotaAssinaturaVencida($chaveRota)) {
          // Assinatura vencida ou problemas no pagamento
          $this->sessaoUsuario->definir('erro', 'Ops! Parece que seu pagamento não foi finalizado. <br>
                                                  <a href="https://api.whatsapp.com/send/?phone=5511934332319&text=Oi!%20Notei%20que%20o%20pagamento%20ainda%20está%20em%20aberto.%20Poderiam%20me%20ajudar%20a%20resolver?%20Obrigado!"
                                                  target="_blank" class="underline font-semibold">Clique aqui</a> para falar com a gente');
          $sucesso = false;
        }
      }

      if ($sucesso == false) {
        header('Location: ' . baseUrl('/login'));
        exit;
      }

      // Limita o acesso à empresa correta
      if ($usuarioLogado['empresaId'] !== $empresaId) {
        return $this->paginaErro->erroVer();
      }
    }

    // Acesso público de usuário com assinatura vencida
    if (! strpos($chaveRota, '/dashboard') and $gratisVencido and $assinaturaStatus == INATIVO and (! isset($usuarioLogado['padrao']) or $usuarioLogado['padrao'] != USUARIO_SUPORTE)) {
        $empresaId = 0;
    }

    // Acesso sem subdomínio
    if ($empresaId == 0 and ! $this->rotaPermitida($chaveRota)) {
       return $this->paginaErro->erroVer();
    }

    // Grava EmpresaID na sessão
    $this->sessaoUsuario->definir('empresaPadraoId', $empresaId);
    $this->sessaoUsuario->definir('subdominio', $empresa);

    // Acessa rota solicitada
    $controlador = new $rotaRequisitada[0]();
    $metodo = $rotaRequisitada[1];

    if ($id) {
      $controlador->$metodo($id);
    }
    else {
      $controlador->$metodo();
    }
  }

  private function limiteRequisicoes()
  {
    $limite = 1000;
    $segundos = 60;
    $segundosBloqueio = $segundos * 60;

    $tempoAgora = time();
    $requisicoes = $this->sessaoUsuario->buscar('requisicoes');
    $bloqueio = (string) $this->sessaoUsuario->buscar('bloqueioData');

    if (empty($requisicoes)) {
      $requisicoes = [];
    }

    $bloqueioTimestamp = $bloqueio ? strtotime($bloqueio) : 0;

    if ($bloqueioTimestamp > $tempoAgora) {
      $this->sessaoUsuario->definir('erro', 'Limite de requisições excedido, tente novamente mais tarde.');

      $this->paginaErro->erroVer('Too Many Requests', 429);
      exit;
    }

    $novaLista = [];
    foreach ($requisicoes as $data):
      // Remove requisições antigas
      if (strtotime($data) > ($tempoAgora - $segundos)) {
        $novaLista[] = $data;
      }
    endforeach;

    $this->sessaoUsuario->definir('requisicoes', $novaLista);
    $this->sessaoUsuario->apagar('bloqueioData');

    if (count($novaLista) >= $limite) {
      $novoBloqueio = $tempoAgora + $segundosBloqueio;
      $desbloqueio = (new DateTime())->setTimestamp($novoBloqueio)->format('Y-m-d H:i:s');

      $acesso = [
        'url' => $_SERVER['REQUEST_URI'],
        'referer' => $_SERVER['HTTP_REFERER'] ?? '',
        'protocolo' => isset($_SERVER['HTTPS']) ? 'HTTPS' : 'HTTP',
      ];

      registrarLog('limite-requisicoes', array_merge($acesso, $_SESSION));

      $this->sessaoUsuario->definir('bloqueioData', $desbloqueio);
      $this->sessaoUsuario->definir('erro', 'Limite de requisições excedido, tente novamente mais tarde.');

      $this->paginaErro->erroVer('Too Many Requests', 429);
      exit;
    }

    // Armazena a data atual das requisições
    $novaLista[] = (new DateTime())->format('Y-m-d H:i:s');
    $this->sessaoUsuario->definir('requisicoes', $novaLista);
  }

  private function rotaPermitida(string $rota = ''): bool
  {
    $rotasPermitidas = [
      'GET:/teste',
      'GET:/erro',
      'POST:/cadastro',
      'PUT:/empresa/{id}',
      'GET:/login',
      'GET:/login/suporte',
      'GET:/login/suporte/{id}',
      'GET:/cadastro',
      'GET:/cadastro/sucesso',
      'POST:/login',
      'GET:/logout',
      'POST:/d/assinaturas/receber',
    ];

    if (in_array($rota, $rotasPermitidas)) {
      return true;
    }

    return false;
  }

  private function rotaRestritaNivel2(string $chaveRota, int $usuarioLogadoId, int $id = 0): bool
  {
    $rotasRestritas = [
      0 => 'GET:/{empresa}/dashboard/usuario/editar/{id}',
      1 => 'GET:/{empresa}/dashboard/usuario/adicionar',
      2 => 'GET:/{empresa}/dashboard/empresa/editar',
      3 => 'GET:/{empresa}/d/usuarios',
      4 => 'GET:/{empresa}/d/usuario/{id}',
      5 => 'POST:/{empresa}/d/usuario',
      6 => 'PUT:/{empresa}/d/usuario/{id}',
      7 => 'DELETE:/{empresa}/d/usuario/{id}',
      8 => 'GET:/{empresa}/d/usuario/desbloquear/{id}',
      9 => 'PUT:/{empresa}/d/empresa/editar/{id}',
    ];

    if (! in_array($chaveRota, $rotasRestritas)) {
      return false;
    }

    // Permite apenas visualizar e editar o próprio cadastro
    if (in_array($chaveRota, [$rotasRestritas[0], $rotasRestritas[6]]) and $usuarioLogadoId == $id) {
      return false;
    }

    return true;
  }

  private function rotaRestritaSuporte(string $chaveRota): bool
  {
    $rotasRestritas = [
      'GET:/{empresa}/d/usuario/desbloquear/{id}',
      'GET:/login/suporte/{id}',
      'GET:/login/suporte',
      'GET:/{empresa}/dashboard/validar_assinatura',
    ];

    if (! in_array($chaveRota, $rotasRestritas)) {
      return false;
    }

    return true;
  }

  private function rotaAssinaturaVencida(string $chaveRota): bool
  {
    $rotasPermitidas = [
      'GET:/{empresa}/dashboard',
      'GET:/{empresa}/dashboard/empresa/editar',
    ];

    if (! in_array($chaveRota, $rotasPermitidas)) {
      return false;
    }

    return true;
  }

  // private function rotaPublica(string $chaveRota): bool
  // {
  //   $rotasRestritas = [
  //     'GET:/',
  //     'GET:/categoria/{id}',
  //     'GET:/artigo/{id}',
  //     'POST:/buscar',
  //     'GET:/buscar',
  //   ];

  //   if (! in_array($chaveRota, $rotasRestritas)) {
  //     return false;
  //   }

  //   return true;
  // }

  private function rotaLogin(string $chaveRota): bool
  {
    $rotasPublicas = [
      'GET:/erro',
      'GET:/login',
      'GET:/login/suporte',
      'GET:/login/suporte/{id}',
      'POST:/cadastro',
      'GET:/cadastro',
      'GET:/cadastro/sucesso',
      'POST:/login',
      'GET:/logout',
    ];

    if (! in_array($chaveRota, $rotasPublicas)) {
      return false;
    }

    return true;
  }

  private function acessarRota(string $rotaRequisitada = ''): array
  {
    $rotas = [
      // Acesso público
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],
      'GET:/login' => [DashboardLoginController::class, 'loginVer'],
      'GET:/login/suporte' => [DashboardLoginController::class, 'loginSuporteVer'],
      'GET:/login/suporte/{id}' => [DashboardLoginController::class, 'loginSuporteVer'],
      'POST:/cadastro' => [DashboardCadastroController::class, 'adicionar'],
      'GET:/cadastro' => [DashboardCadastroController::class, 'cadastroVer'],
      'GET:/cadastro/sucesso' => [DashboardCadastroController::class, 'cadastroSucessoVer'],
      'POST:/login' => [DashboardLoginController::class, 'login'],
      'GET:/logout' => [DashboardLoginController::class, 'logout'],

      // Empresa
      'GET:/{empresa}' => [PublicoController::class, 'publicoVer'],
      'GET:/{empresa}/categoria/{id}' => [PublicoCategoriaController::class, 'categoriaVer'],
      'GET:/{empresa}/artigo/{id}' => [PublicoArtigoController::class, 'artigoVer'],
      'POST:/{empresa}/buscar' => [PublicoBuscaController::class, 'buscar'],
      'GET:/{empresa}/buscar' => [PublicoBuscaController::class, 'buscar'],

      // Dashboard
      'GET:/{empresa}/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/{empresa}/dashboard/ajustes' => [DashboardAjusteController::class, 'ajustesVer'],
      'GET:/{empresa}/dashboard/artigos' => [DashboardArtigoController::class, 'artigosVer'],
      'GET:/{empresa}/dashboard/artigo/editar/{id}' => [DashboardArtigoController::class, 'artigoEditarVer'],
      'GET:/{empresa}/dashboard/conteudo/editar/{id}' => [DashboardConteudoController::class, 'conteudoEditarVer'],
      'GET:/{empresa}/dashboard/conteudo/adicionar' => [DashboardConteudoController::class, 'conteudoAdicionarVer'],
      'GET:/{empresa}/dashboard/categorias' => [DashboardCategoriaController::class, 'categoriasVer'],
      'GET:/{empresa}/dashboard/categoria/editar/{id}' => [DashboardCategoriaController::class, 'categoriaEditarVer'],
      'GET:/{empresa}/dashboard/categoria/adicionar' => [DashboardCategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/{empresa}/dashboard/usuarios' => [DashboardUsuarioController::class, 'UsuariosVer'],
      'GET:/{empresa}/dashboard/usuario/editar/{id}' => [DashboardUsuarioController::class, 'usuarioEditarVer'],
      'GET:/{empresa}/dashboard/usuario/adicionar' => [DashboardUsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/{empresa}/dashboard/empresa/editar' => [DashboardEmpresaController::class, 'empresaEditarVer'],
      'GET:/{empresa}/dashboard/validar_assinatura' => [DashboardEmpresaController::class, 'reprocessarAssinaturaAsaas'],

      // Dashboard - Ajustes
      'PUT:/{empresa}/d/ajustes' => [DashboardAjusteController::class, 'atualizar'],
      'GET:/{empresa}/d/firebase' => [DatabaseFirebaseComponent::class, 'credenciais'],
      'POST:/{empresa}/d/apagar-local' => [DatabaseFirebaseComponent::class, 'apagarLocal'],
      'POST:/{empresa}/d/apagar-artigos-local' => [DatabaseFirebaseComponent::class, 'apagarArtigosLocal'],
      'POST:/{empresa}/d/upload-local' => [DatabaseFirebaseComponent::class, 'uploadLocal'],
      'POST:/{empresa}/d/upload-multiplas-local' => [DatabaseFirebaseComponent::class, 'uploadMultiplasLocal'],
      'POST:/{empresa}/d/assinatura' => [DashboardEmpresaController::class, 'buscarAssinatura'],
      'PUT:/{empresa}/d/empresa/editar/{id}' => [DashboardEmpresaController::class, 'atualizar'],

      // Dashboard - Assinatura
      'POST:/d/assinaturas/receber' => [AssinaturaReceberComponent::class, 'receberWebhook'],
      'GET:/{empresa}/d/assinaturas/gerar' => [DashboardEmpresaController::class, 'criarAssinaturaAsaas'],

      // Cache
      'GET:/{empresa}/cache/limpar' => [Cache::class, 'resetarCache'],

      // Dashboard - Artigos
      'GET:/{empresa}/d/artigos' => [DashboardArtigoController::class, 'buscar'],
      'GET:/{empresa}/d/artigo/{id}' => [DashboardArtigoController::class, 'buscar'],
      'POST:/{empresa}/d/artigo' => [DashboardArtigoController::class, 'adicionar'],
      'PUT:/{empresa}/d/artigo/{id}' => [DashboardArtigoController::class, 'atualizar'],
      'PUT:/{empresa}/d/artigo/ordem' => [DashboardArtigoController::class, 'atualizarOrdem'],
      'DELETE:/{empresa}/d/artigo/{id}' => [DashboardArtigoController::class, 'apagar'],

      // Dashboard - Conteúdos
      'GET:/{empresa}/d/conteudos' => [DashboardConteudoController::class, 'buscar'],
      'GET:/{empresa}/d/conteudos/{id}' => [DashboardConteudoController::class, 'buscar'],
      'POST:/{empresa}/d/conteudo' => [DashboardConteudoController::class, 'adicionar'],
      'PUT:/{empresa}/d/conteudo/{id}' => [DashboardConteudoController::class, 'atualizar'],
      'PUT:/{empresa}/d/conteudo/ordem' => [DashboardConteudoController::class, 'atualizarOrdem'],
      'DELETE:/{empresa}/d/conteudo/{id}' => [DashboardConteudoController::class, 'apagar'],

      // Dashboard - Categorias
      'GET:/{empresa}/d/categorias' => [DashboardCategoriaController::class, 'buscar'],
      'GET:d/categoria/{id}' => [DashboardCategoriaController::class, 'buscar'],
      'POST:/{empresa}/d/categoria' => [DashboardCategoriaController::class, 'adicionar'],
      'PUT:/{empresa}/d/categoria/{id}' => [DashboardCategoriaController::class, 'atualizar'],
      'PUT:/{empresa}/d/categoria/ordem' => [DashboardCategoriaController::class, 'atualizarOrdem'],
      'DELETE:/{empresa}/d/categoria/{id}' => [DashboardCategoriaController::class, 'apagar'],

      // Dashboard - Usuários
      'GET:/{empresa}/d/usuarios' => [DashboardUsuarioController::class, 'buscar'],
      'GET:/{empresa}/d/usuario/{id}' => [DashboardUsuarioController::class, 'buscar'],
      'POST:/{empresa}/d/usuario' => [DashboardUsuarioController::class, 'adicionar'],
      'PUT:/{empresa}/d/usuario/{id}' => [DashboardUsuarioController::class, 'atualizar'],
      'DELETE:/{empresa}/d/usuario/{id}' => [DashboardUsuarioController::class, 'apagar'],
      'GET:/{empresa}/d/usuario/desbloquear/{id}' => [DashboardUsuarioController::class, 'desbloquear'],
    ];

    return $rotas[ $rotaRequisitada ] ?? [];
  }
}