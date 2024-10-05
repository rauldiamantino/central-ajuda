<?php
namespace app\Roteamento;
use app\Controllers\DashboardAjusteController;
use app\Controllers\DashboardArtigoController;
use app\Controllers\DashboardCadastroController;
use app\Controllers\DashboardCategoriaController;
use app\Controllers\DashboardConteudoController;
use app\Controllers\DashboardController;
use app\Controllers\DashboardEmpresaController;
use app\Controllers\DashboardLoginController;
use app\Controllers\DashboardUsuarioController;
use app\Controllers\PaginaErroController;
use app\Controllers\PublicoArtigoController;
use app\Controllers\PublicoBuscaController;
use app\Controllers\PublicoCategoriaController;
use app\Controllers\PublicoController;
use app\Controllers\Components\DatabaseFirebaseComponent;
use app\Controllers\Components\AssinaturaReceberComponent;
use DateTime;

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
    $temp = explode('.', $_SERVER['HTTP_HOST']);
    $subdominio = '';

    if (count($temp) > 1) {
      $subdominio = $temp[0];
    }

    $chaveRota = $metodo . ':' . $url;
    $partesRota = explode('/', trim($url, '/'));

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

    $dashboardEmpresa = new DashboardEmpresaController();

    $coluna = 'id';
    $valor = $empresaId;

    if ($subdominio and $empresaId == 0) {
      $coluna = 'subdominio';
      $valor = $subdominio;
    }

    $buscarEmpresa = $dashboardEmpresa->buscarEmpresaSemId($coluna, $valor);

    $empresaId = intval($buscarEmpresa[0]['Empresa.id'] ?? 0);
    $empresaAtivo = intval($buscarEmpresa[0]['Empresa.ativo'] ?? 0);
    $subdominio = $buscarEmpresa[0]['Empresa.subdominio'] ?? '';

    // Acesso negado
    if ($empresaAtivo == INATIVO) {
      $empresaId = 0;
    }

    $usuarioLogado = $this->sessaoUsuario->buscar('usuario');

    // Sempre utiliza Empresa ID logada
    if (substr($chaveRota, 0, 18) != 'GET:/login/suporte/{empresaId}') {

      if (isset($usuarioLogado['empresaId']) and $this->rotaPublica($chaveRota) == false) {
        $empresaId = (int) $usuarioLogado['empresaId'];
      }
    }

    // Restrições de acesso por nível
    if ((! isset($usuarioLogado['padrao']) or $usuarioLogado['padrao'] != USUARIO_SUPORTE) and $this->rotaRestritaSuporte($chaveRota)) {
      $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
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
        $this->sessaoUsuario->definir('erro', 'Empresa desativada');
        $sucesso = false;
      }

      // Usuário deslogado
      if ($sucesso == false) {
        $this->sessaoUsuario->apagar('usuario');

        header('Location: /login');
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
        header('Location: /login');
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
    $this->sessaoUsuario->definir('empresaId', $empresaId);
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
      'PUT:/empresa/{empresaId}/{id}',
      'GET:/login',
      'GET:/login/suporte/{empresaId}',
      'GET:/login/suporte/{empresaId}/{id}',
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
      0 => 'GET:/dashboard/usuario/editar/{empresaId}/{id}',
      1 => 'GET:/dashboard/usuario/adicionar/{empresaId}',
      2 => 'GET:/dashboard/empresa/editar/{empresaId}',
      3 => 'GET:/d/usuarios/{empresaId}',
      4 => 'GET:/d/usuario/{empresaId}/{id}',
      5 => 'POST:/d/usuario/{empresaId}',
      6 => 'PUT:/d/usuario/{empresaId}/{id}',
      7 => 'DELETE:/d/usuario/{empresaId}/{id}',
      8 => 'GET:/d/usuario/desbloquear/{empresaId}/{id}',
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
      'GET:/d/usuario/desbloquear/{empresaId}/{id}',
      'GET:/login/suporte/{empresaId}/id',
      'GET:/login/suporte/{empresaId}',
      'GET:/dashboard/validar_assinatura/{empresaId}',
      'GET:/dashboard/confirmar_assinatura/{empresaId}',
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
      'PUT:/empresa/{empresaId}/{id}' => [DashboardEmpresaController::class, 'atualizar'],
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
      'GET:/dashboard/{empresaId}' => [DashboardController::class, 'dashboardVer'],
      'GET:/dashboard/ajustes/{empresaId}' => [DashboardAjusteController::class, 'ajustesVer'],
      'GET:/dashboard/artigos/{empresaId}' => [DashboardArtigoController::class, 'artigosVer'],
      'GET:/dashboard/artigo/editar/{empresaId}/{id}' => [DashboardArtigoController::class, 'artigoEditarVer'],
      'GET:/dashboard/artigo/adicionar/{empresaId}' => [DashboardArtigoController::class, 'artigoAdicionarVer'],
      'GET:/dashboard/conteudo/editar/{empresaId}/{id}' => [DashboardConteudoController::class, 'conteudoEditarVer'],
      'GET:/dashboard/conteudo/adicionar/{empresaId}' => [DashboardConteudoController::class, 'conteudoAdicionarVer'],
      'GET:/dashboard/categorias/{empresaId}' => [DashboardCategoriaController::class, 'categoriasVer'],
      'GET:/dashboard/categoria/editar/{empresaId}/{id}' => [DashboardCategoriaController::class, 'categoriaEditarVer'],
      'GET:/dashboard/categoria/adicionar/{empresaId}' => [DashboardCategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/dashboard/usuarios/{empresaId}' => [DashboardUsuarioController::class, 'UsuariosVer'],
      'GET:/dashboard/usuario/editar/{empresaId}/{id}' => [DashboardUsuarioController::class, 'usuarioEditarVer'],
      'GET:/dashboard/usuario/adicionar/{empresaId}' => [DashboardUsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/dashboard/empresa/editar/{empresaId}' => [DashboardEmpresaController::class, 'empresaEditarVer'],
      'GET:/dashboard/validar_assinatura/{empresaId}' => [DashboardEmpresaController::class, 'reprocessarAssinatura'],
      'GET:/dashboard/confirmar_assinatura/{empresaId}' => [DashboardEmpresaController::class, 'confirmarAssinatura'],

      // Dashboard - Ajustes
      'PUT:/d/ajustes/{empresaId}' => [DashboardAjusteController::class, 'atualizar'],
      'GET:/d/firebase/{empresaId}' => [DatabaseFirebaseComponent::class, 'credenciais'],

      // Dashboard - Assinatura
      'POST:/d/assinaturas/receber' => [AssinaturaReceberComponent::class, 'receberWebhook'],

      // Dashboard - Artigos
      'GET:/d/artigos/{empresaId}' => [DashboardArtigoController::class, 'buscar'],
      'GET:/d/artigo/{empresaId}/{id}' => [DashboardArtigoController::class, 'buscar'],
      'POST:/d/artigo/{empresaId}' => [DashboardArtigoController::class, 'adicionar'],
      'PUT:/d/artigo/{empresaId}/{id}' => [DashboardArtigoController::class, 'atualizar'],
      'PUT:/d/artigo/ordem/{empresaId}' => [DashboardArtigoController::class, 'atualizarOrdem'],
      'DELETE:/d/artigo/{empresaId}/{id}' => [DashboardArtigoController::class, 'apagar'],

      // Dashboard - Conteúdos
      'GET:/d/conteudos/{empresaId}' => [DashboardConteudoController::class, 'buscar'],
      'GET:/d/conteudos/{empresaId}/{id}' => [DashboardConteudoController::class, 'buscar'],
      'POST:/d/conteudo/{empresaId}' => [DashboardConteudoController::class, 'adicionar'],
      'PUT:/d/conteudo/{empresaId}/{id}' => [DashboardConteudoController::class, 'atualizar'],
      'PUT:/d/conteudo/ordem/{empresaId}' => [DashboardConteudoController::class, 'atualizarOrdem'],
      'DELETE:/d/conteudo/{empresaId}/{id}' => [DashboardConteudoController::class, 'apagar'],

      // Dashboard - Categorias
      'GET:/d/categorias/{empresaId}' => [DashboardCategoriaController::class, 'buscar'],
      'GET:d/categoria/{empresaId}/{id}' => [DashboardCategoriaController::class, 'buscar'],
      'POST:/d/categoria/{empresaId}' => [DashboardCategoriaController::class, 'adicionar'],
      'PUT:/d/categoria/{empresaId}/{id}' => [DashboardCategoriaController::class, 'atualizar'],
      'PUT:/d/categoria/ordem/{empresaId}' => [DashboardCategoriaController::class, 'atualizarOrdem'],
      'DELETE:/d/categoria/{empresaId}/{id}' => [DashboardCategoriaController::class, 'apagar'],

      // Dashboard - Usuários
      'GET:/d/usuarios/{empresaId}' => [DashboardUsuarioController::class, 'buscar'],
      'GET:/d/usuario/{empresaId}/{id}' => [DashboardUsuarioController::class, 'buscar'],
      'POST:/d/usuario/{empresaId}' => [DashboardUsuarioController::class, 'adicionar'],
      'PUT:/d/usuario/{empresaId}/{id}' => [DashboardUsuarioController::class, 'atualizar'],
      'DELETE:/d/usuario/{empresaId}/{id}' => [DashboardUsuarioController::class, 'apagar'],
      'GET:/d/usuario/desbloquear/{empresaId}/{id}' => [DashboardUsuarioController::class, 'desbloquear'],
    ];

    return $rotas[ $rotaRequisitada ] ?? [];
  }
}