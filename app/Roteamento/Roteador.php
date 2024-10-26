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

    // Subdomínio
    if (strpos($_SERVER['HTTP_HOST'], 'localhost')) {
      $subPadrao = '.localhost';
    }
    elseif (strpos($_SERVER['HTTP_HOST'], '360help.local')) {
      $subPadrao = '.360help.local';
    }
    else {
      $subPadrao = '.';
    }

    $temp = explode($subPadrao, $_SERVER['HTTP_HOST']);
    $subdominio = '';
    $subdominioAtivo = false;

    if (count($temp) > 1) {
      $subdominio = $temp[0];
      $subdominioAtivo = true;
    }

    $chaveRota = $metodo . ':' . $url;
    $partesRota = explode('/', trim($url, '/'));

    if (HOST_LOCAL) {
      $chaveRota = str_replace(RAIZ, '/', $chaveRota);
    }

    $empresaId = 0;
    $id = 0;

    $ids = [];
    foreach ($partesRota as $parte):

      if (count($ids) > 2) {
        break;
      }

      if (is_numeric($parte)) {
        $ids[] = (int) $parte;
      }
    endforeach;

    if (! strpos($chaveRota, '/dashboard') and ! strpos($chaveRota, '/d/') and count($ids) == 1) {
      // Somente parâmetro
      $id = $ids[0];
    }
    else {
      // Empresa e parâmetro
      $empresaId = intval($ids[0] ?? 0);
      $id = intval($ids[1] ?? 0);
    }

    $chaveRota = preg_replace('/\b' . preg_quote($empresaId, '/') . '\b/', '{empresaId}', $chaveRota, 1);
    $chaveRota = preg_replace('/\b' . preg_quote($id, '/') . '\b/', '{id}', $chaveRota, 1);

    $rotaRequisitada = $this->acessarRota($chaveRota);

    if (empty($rotaRequisitada)) {
      return $this->paginaErro->erroVer();
    }

    // Subdomínio somente nas rotas públicas
    if ($subdominioAtivo and ! $this->rotaPublica($chaveRota)) {
      return $this->paginaErro->erroVer();
    }

    $dashboardEmpresa = new DashboardEmpresaController();

    $coluna = 'id';
    $valor = $empresaId;

    if ($subdominio and $empresaId == 0) {
      $coluna = 'subdominio';
      $valor = $subdominio;
    }

    $cacheTempo = 60 * 60;
    $cacheNome = 'roteador_subdominio-' . md5($coluna . $valor);

    $buscarEmpresa = Cache::buscar($cacheNome);

    if ($buscarEmpresa == null) {
      $buscarEmpresa = $dashboardEmpresa->buscarEmpresaSemId($coluna, $valor);

      if ($buscarEmpresa) {
        Cache::definir($cacheNome, $buscarEmpresa, $cacheTempo);
      }
    }

    $empresaId = intval($buscarEmpresa[0]['Empresa']['id'] ?? 0);
    $empresaAtivo = intval($buscarEmpresa[0]['Empresa']['ativo'] ?? 0);
    $subdominio = $buscarEmpresa[0]['Empresa']['subdominio'] ?? '';

    // Acesso negado
    if ($empresaAtivo == INATIVO) {
      $empresaId = 0;
    }

    $usuarioLogado = $this->sessaoUsuario->buscar('usuario');

    // Suporte acessando empresas
    if (isset($usuarioLogado['padrao']) and $usuarioLogado['padrao'] == USUARIO_SUPORTE and $empresaId == 0) {
      $empresaId = $usuarioLogado['empresaId'];
    }

    // Restrições de acesso por nível
    if ((! isset($usuarioLogado['padrao']) or $usuarioLogado['padrao'] != USUARIO_SUPORTE) and $this->rotaRestritaSuporte($chaveRota)) {
      $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
      $referer = $_SERVER['HTTP_REFERER'] ?? '/';
      header('Location: ' . $referer);
      exit;
    }

    // Dashboard
    if (strpos($chaveRota, '/dashboard')) {
      $sucesso = true;

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

      if ($sucesso == false) {
        header('Location: ' . baseUrl('/login'));
        exit;
      }

      // Limita o acesso à empresa correta
      if ($usuarioLogado['empresaId'] !== $empresaId) {
        return $this->paginaErro->erroVer();
      }
    }

    // Acesso sem subdomínio
    if ($empresaId == 0 and ! $this->rotaPermitida($chaveRota)) {
       return $this->paginaErro->erroVer();
    }

    // Grava EmpresaID na sessão
    $this->sessaoUsuario->definir('empresaPadraoId', $empresaId);
    $this->sessaoUsuario->definir('subdominio', $subdominio);

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
      0 => 'GET:/dashboard/{empresaId}/usuario/editar/{id}',
      1 => 'GET:/dashboard/{empresaId}/usuario/adicionar',
      2 => 'GET:/dashboard/{empresaId}/empresa/editar',
      3 => 'GET:/d/{empresaId}/usuarios',
      4 => 'GET:/d/{empresaId}/usuario/{id}',
      5 => 'POST:/d/{empresaId}/usuario',
      6 => 'PUT:/d/{empresaId}/usuario/{id}',
      7 => 'DELETE:/d/{empresaId}/usuario/{id}',
      8 => 'GET:/d/{empresaId}/usuario/desbloquear/{id}',
      9 => 'PUT:/d/{empresaId}/empresa/editar/{id}',
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
      'GET:/d/{empresaId}/usuario/desbloquear/{id}',
      'GET:/login/suporte/id',
      'GET:/login/suporte',
      'GET:/dashboard/{empresaId}/validar_assinatura',
    ];

    if (! in_array($chaveRota, $rotasRestritas)) {
      return false;
    }

    return true;
  }

  private function rotaPublica(string $chaveRota): bool
  {
    $rotasRestritas = [
      'GET:/',
      'GET:/categoria/{id}',
      'GET:/artigo/{id}',
      'POST:/buscar',
      'GET:/buscar',
    ];

    if (! in_array($chaveRota, $rotasRestritas)) {
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

      // Subdomínio
      'GET:/' => [PublicoController::class, 'publicoVer'],
      'GET:/categoria/{id}' => [PublicoCategoriaController::class, 'categoriaVer'],
      'GET:/artigo/{id}' => [PublicoArtigoController::class, 'artigoVer'],
      'POST:/buscar' => [PublicoBuscaController::class, 'buscar'],
      'GET:/buscar' => [PublicoBuscaController::class, 'buscar'],

      // Dashboard
      // 'GET:/dashboard/{empresaId}' => [DashboardController::class, 'dashboardVer'],
      'GET:/dashboard/{empresaId}/ajustes' => [DashboardAjusteController::class, 'ajustesVer'],
      'GET:/dashboard/{empresaId}/artigos' => [DashboardArtigoController::class, 'artigosVer'],
      'GET:/dashboard/{empresaId}/artigo/editar/{id}' => [DashboardArtigoController::class, 'artigoEditarVer'],
      'GET:/dashboard/{empresaId}/artigo/adicionar' => [DashboardArtigoController::class, 'artigoAdicionarVer'],
      'GET:/dashboard/{empresaId}/conteudo/editar/{id}' => [DashboardConteudoController::class, 'conteudoEditarVer'],
      'GET:/dashboard/{empresaId}/conteudo/adicionar' => [DashboardConteudoController::class, 'conteudoAdicionarVer'],
      'GET:/dashboard/{empresaId}/categorias' => [DashboardCategoriaController::class, 'categoriasVer'],
      'GET:/dashboard/{empresaId}/categoria/editar/{id}' => [DashboardCategoriaController::class, 'categoriaEditarVer'],
      'GET:/dashboard/{empresaId}/categoria/adicionar' => [DashboardCategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/dashboard/{empresaId}/usuarios' => [DashboardUsuarioController::class, 'UsuariosVer'],
      'GET:/dashboard/{empresaId}/usuario/editar/{id}' => [DashboardUsuarioController::class, 'usuarioEditarVer'],
      'GET:/dashboard/{empresaId}/usuario/adicionar' => [DashboardUsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/dashboard/{empresaId}/empresa/editar' => [DashboardEmpresaController::class, 'empresaEditarVer'],
      'GET:/dashboard/{empresaId}/validar_assinatura' => [DashboardEmpresaController::class, 'reprocessarAssinatura'],

      // Dashboard - Ajustes
      'PUT:/d/{empresaId}/ajustes' => [DashboardAjusteController::class, 'atualizar'],
      'GET:/d/{empresaId}/firebase' => [DatabaseFirebaseComponent::class, 'credenciais'],
      'POST:/d/{empresaId}/assinatura' => [DashboardEmpresaController::class, 'buscarAssinatura'],
      'PUT:/d/{empresaId}/empresa/editar/{id}' => [DashboardEmpresaController::class, 'atualizar'],

      // Dashboard - Assinatura
      'POST:/d/assinaturas/receber' => [AssinaturaReceberComponent::class, 'receberWebhook'],

      // Dashboard - Artigos
      'GET:/d/{empresaId}/artigos' => [DashboardArtigoController::class, 'buscar'],
      'GET:/d/{empresaId}/artigo/{id}' => [DashboardArtigoController::class, 'buscar'],
      'POST:/d/{empresaId}/artigo' => [DashboardArtigoController::class, 'adicionar'],
      'PUT:/d/{empresaId}/artigo/{id}' => [DashboardArtigoController::class, 'atualizar'],
      'PUT:/d/{empresaId}/artigo/ordem' => [DashboardArtigoController::class, 'atualizarOrdem'],
      'DELETE:/d/{empresaId}/artigo/{id}' => [DashboardArtigoController::class, 'apagar'],

      // Dashboard - Conteúdos
      'GET:/d/{empresaId}/conteudos' => [DashboardConteudoController::class, 'buscar'],
      'GET:/d/{empresaId}/conteudos/{id}' => [DashboardConteudoController::class, 'buscar'],
      'POST:/d/{empresaId}/conteudo' => [DashboardConteudoController::class, 'adicionar'],
      'PUT:/d/{empresaId}/conteudo/{id}' => [DashboardConteudoController::class, 'atualizar'],
      'PUT:/d/{empresaId}/conteudo/ordem' => [DashboardConteudoController::class, 'atualizarOrdem'],
      'DELETE:/d/{empresaId}/conteudo/{id}' => [DashboardConteudoController::class, 'apagar'],

      // Dashboard - Categorias
      'GET:/d/{empresaId}/categorias' => [DashboardCategoriaController::class, 'buscar'],
      'GET:d/categoria/{id}' => [DashboardCategoriaController::class, 'buscar'],
      'POST:/d/{empresaId}/categoria' => [DashboardCategoriaController::class, 'adicionar'],
      'PUT:/d/{empresaId}/categoria/{id}' => [DashboardCategoriaController::class, 'atualizar'],
      'PUT:/d/{empresaId}/categoria/ordem' => [DashboardCategoriaController::class, 'atualizarOrdem'],
      'DELETE:/d/{empresaId}/categoria/{id}' => [DashboardCategoriaController::class, 'apagar'],

      // Dashboard - Usuários
      'GET:/d/{empresaId}/usuarios' => [DashboardUsuarioController::class, 'buscar'],
      'GET:/d/{empresaId}/usuario/{id}' => [DashboardUsuarioController::class, 'buscar'],
      'POST:/d/{empresaId}/usuario' => [DashboardUsuarioController::class, 'adicionar'],
      'PUT:/d/{empresaId}/usuario/{id}' => [DashboardUsuarioController::class, 'atualizar'],
      'DELETE:/d/{empresaId}/usuario/{id}' => [DashboardUsuarioController::class, 'apagar'],
      'GET:/d/{empresaId}/usuario/desbloquear/{id}' => [DashboardUsuarioController::class, 'desbloquear'],
    ];

    return $rotas[ $rotaRequisitada ] ?? [];
  }
}