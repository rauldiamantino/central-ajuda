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
use app\Controllers\TesteController;
use app\Controllers\FirebaseController;
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
    $partesUrl = explode('/', $url);
    $subdominio = $partesUrl[1] ?? '';

    if ($this->subdominioPermitido($subdominio) == false) {
      $subdominio = '';
    }

    $chaveRota = $metodo . ':' . $url;
    $id = (int) basename($url);
    $chaveRota = str_replace($id, '{id}', $chaveRota);
    $chaveRota = str_replace($subdominio, '{subdominio}', $chaveRota);

    $dashboardEmpresa = new DashboardEmpresaController();
    $buscarEmpresa = $dashboardEmpresa->buscarEmpresa($subdominio);
    $empresaId = intval($buscarEmpresa[0]['Empresa.id'] ?? 0);

    $usuarioLogado = $this->sessaoUsuario->buscar('usuario');

    // Sempre prioriza Empresa ID logada
    if (isset($usuarioLogado['empresaId']) and substr($chaveRota, 0, 18) != 'GET:/login/suporte') {
      $empresaId = (int) $usuarioLogado['empresaId'];
    }

    // Restrições de acesso por nível
    if ((! isset($usuarioLogado['padrao']) or $usuarioLogado['padrao'] > 0) and $this->rotaRestritaSuporte($chaveRota)) {
      $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');

      if (! isset($usuarioLogado['id']) or (int) $usuarioLogado['id'] == 0) {
        $this->sessaoUsuario->apagar('usuario');
      }

      header('Location: /login');
      exit;
    }

    // Dashboard
    if (strpos($chaveRota, '/{subdominio}/dashboard')) {
      $sucesso = true;

      if (! isset($usuarioLogado['nivel'])) {
        $sucesso = false;
      }

      if (! isset($usuarioLogado['id']) or (int) $usuarioLogado['id'] == 0) {
        $sucesso = false;
      }

      if (! isset($usuarioLogado['subdominio']) or empty($usuarioLogado['subdominio'])) {
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
        $this->sessaoUsuario->apagar('usuario');
        $sucesso = false;
      }

      // Restrições de acesso por nível
      if ($sucesso and $usuarioLogado['padrao'] > 0 and $this->rotaRestritaSuporte($chaveRota)) {
        $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
        $sucesso = false;
      }

      if ($sucesso and $usuarioLogado['nivel'] == 2 and $this->rotaRestritaNivel2($chaveRota, $usuarioLogado['id'], $id)) {
        $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
        $sucesso = false;
      }

      if ($sucesso == false) {
        header('Location: /login');
        exit;
      }

      // Limita o acesso à empresa correta
      if ($usuarioLogado['subdominio'] !== $subdominio) {
        return $this->paginaErro->erroVer();
      }

      // Define Empresa ID padrão
      $empresaId = $usuarioLogado['empresaId'];
    }

    // Acesso sem subdomínio
    if ($empresaId == 0 and ! $this->rotaPermitida($chaveRota)) {
       return $this->paginaErro->erroVer();
    }

    // Grava EmpresaID na sessão
    $this->sessaoUsuario->definir('empresaId', $empresaId);
    $this->sessaoUsuario->definir('subdominio', $subdominio);

    $rotaRequisitada = $this->acessarRota($chaveRota);

    if ($rotaRequisitada) {
      $controlador = new $rotaRequisitada[0]();
      $metodo = $rotaRequisitada[1];

      if ($id) {
        $controlador->$metodo($id);
      }
      else {
        $controlador->$metodo();
      }
    }
    else {
      $this->paginaErro->erroVer();
    }
  }

  private function limiteRequisicoes()
  {
    $limite = 100;
    $segundos = 60;
    $segundosBloqueio = 60 * 60;

    $tempoAgora = time();
    $requisicoes = $this->sessaoUsuario->buscar('requisicoes');
    $bloqueio = (string) $this->sessaoUsuario->buscar('bloqueioData');

    if (empty($requisicoes)) {
      $requisicoes = [];
    }

    $bloqueioTimestamp = $bloqueio ? strtotime($bloqueio) : 0;

    if ($bloqueioTimestamp > $tempoAgora) {
      $this->sessaoUsuario->definir('erro', 'Limite de requisições excedido. Tente novamente mais tarde.');

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
      $this->sessaoUsuario->definir('erro', 'Limite de requisições excedido. Tente novamente mais tarde.');

      $this->paginaErro->erroVer('Too Many Requests', 429);
      exit;
    }

    // Armazena a data atual das requisições
    $novaLista[] = (new DateTime())->format('Y-m-d H:i:s');
    $this->sessaoUsuario->definir('requisicoes', $novaLista);
  }

  private function subdominioPermitido(string $subdominio = ''): bool
  {
    $subdominios = [
      'padrao',
      'teste2',
      'teste5',
      'luminaon',
    ];

    if (in_array($subdominio, $subdominios)) {
      return true;
    }

    return false;
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
    ];

    if (in_array($rota, $rotasPermitidas)) {
      return true;
    }

    return false;
  }

  private function rotaRestritaNivel2(string $rota, int $usuarioLogadoId, int $id = 0): bool
  {
    $rotasRestritas = [
      0 => 'GET:/{subdominio}/dashboard/usuario/editar/{id}',
      1 => 'GET:/{subdominio}/dashboard/usuario/adicionar',
      2 => 'GET:/{subdominio}/dashboard/empresa/editar',
      3 => 'GET:/{subdominio}/d/usuarios',
      4 => 'GET:/{subdominio}/d/usuario/{id}',
      5 => 'POST:/{subdominio}/d/usuario',
      6 => 'PUT:/{subdominio}/d/usuario/{id}',
      7 => 'DELETE:/{subdominio}/d/usuario/{id}',
      8 => 'GET:/{subdominio}/d/usuario/desbloquear/{id}',
    ];

    if (! in_array($rota, $rotasRestritas)) {
      return false;
    }

    // Permite apenas visualizar e editar o próprio cadastro
    if (in_array($rota, [$rotasRestritas[0], $rotasRestritas[6]]) and $usuarioLogadoId == $id) {
      return false;
    }

    return true;
  }

  private function rotaRestritaSuporte(string $rota): bool
  {
    $rotasRestritas = [
      'GET:/{subdominio}/d/usuario/desbloquear/{id}',
      'GET:/login/suporte/{id}',
      'GET:/login/suporte',
    ];

    if (! in_array($rota, $rotasRestritas)) {
      return false;
    }

    return true;
  }

  private function acessarRota(string $rotaRequisitada = ''): array
  {
    $rotas = [
      // Acesso sem domínio
      'POST:/cadastro' => [DashboardCadastroController::class, 'adicionar'],
      'PUT:/empresa/{id}' => [DashboardEmpresaController::class, 'atualizar'],
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],
      'GET:/login' => [DashboardLoginController::class, 'loginVer'],
      'GET:/login/suporte' => [DashboardLoginController::class, 'loginSuporteVer'],
      'GET:/login/suporte/{id}' => [DashboardLoginController::class, 'loginSuporteVer'],
      'GET:/cadastro' => [DashboardCadastroController::class, 'cadastroVer'],
      'GET:/cadastro/sucesso' => [DashboardCadastroController::class, 'cadastroSucessoVer'],
      'POST:/login' => [DashboardLoginController::class, 'login'],
      'GET:/logout' => [DashboardLoginController::class, 'logout'],

      // Público
      'GET:/{subdominio}' => [PublicoController::class, 'publicoVer'],
      'GET:/{subdominio}/categoria/{id}' => [PublicoCategoriaController::class, 'categoriaVer'],
      'GET:/{subdominio}/artigo/{id}' => [PublicoArtigoController::class, 'artigoVer'],
      'POST:/{subdominio}/buscar' => [PublicoBuscaController::class, 'buscar'],
      'GET:/{subdominio}/buscar' => [PublicoBuscaController::class, 'buscar'],

      // Dashboard
      'GET:/{subdominio}/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/{subdominio}/dashboard/ajustes' => [DashboardAjusteController::class, 'ajustesVer'],
      'GET:/{subdominio}/dashboard/artigos' => [DashboardArtigoController::class, 'artigosVer'],
      'GET:/{subdominio}/dashboard/artigo/editar/{id}' => [DashboardArtigoController::class, 'artigoEditarVer'],
      'GET:/{subdominio}/dashboard/artigo/adicionar' => [DashboardArtigoController::class, 'artigoAdicionarVer'],
      'GET:/{subdominio}/dashboard/categorias' => [DashboardCategoriaController::class, 'categoriasVer'],
      'GET:/{subdominio}/dashboard/categoria/editar/{id}' => [DashboardCategoriaController::class, 'categoriaEditarVer'],
      'GET:/{subdominio}/dashboard/categoria/adicionar' => [DashboardCategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/{subdominio}/dashboard/usuarios' => [DashboardUsuarioController::class, 'UsuariosVer'],
      'GET:/{subdominio}/dashboard/usuario/editar/{id}' => [DashboardUsuarioController::class, 'usuarioEditarVer'],
      'GET:/{subdominio}/dashboard/usuario/adicionar' => [DashboardUsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/{subdominio}/dashboard/empresa/editar' => [DashboardEmpresaController::class, 'empresaEditarVer'],

      // Dashboard - Ajustes
      'PUT:/{subdominio}/d/ajustes' => [DashboardAjusteController::class, 'atualizar'],
      'GET:/{subdominio}/d/firebase' => [FirebaseController::class, 'credenciais'],

      // Dashboard - Artigos
      'GET:/{subdominio}/d/artigos' => [DashboardArtigoController::class, 'buscar'],
      'GET:/{subdominio}/d/artigo/{id}' => [DashboardArtigoController::class, 'buscar'],
      'POST:/{subdominio}/d/artigo' => [DashboardArtigoController::class, 'adicionar'],
      'PUT:/{subdominio}/d/artigo/{id}' => [DashboardArtigoController::class, 'atualizar'],
      'PUT:/{subdominio}/d/artigo/ordem' => [DashboardArtigoController::class, 'atualizarOrdem'],
      'DELETE:/{subdominio}/d/artigo/{id}' => [DashboardArtigoController::class, 'apagar'],

      // Dashboard - Conteúdos
      'GET:/{subdominio}/d/conteudos' => [DashboardConteudoController::class, 'buscar'],
      'GET:/{subdominio}/d/conteudos/{id}' => [DashboardConteudoController::class, 'buscar'],
      'POST:/{subdominio}/d/conteudo' => [DashboardConteudoController::class, 'adicionar'],
      'PUT:/{subdominio}/d/conteudo/{id}' => [DashboardConteudoController::class, 'atualizar'],
      'PUT:/{subdominio}/d/conteudo/ordem' => [DashboardConteudoController::class, 'atualizarOrdem'],
      'DELETE:/{subdominio}/d/conteudo/{id}' => [DashboardConteudoController::class, 'apagar'],

      // Dashboard - Categorias
      'GET:/{subdominio}/d/categorias' => [DashboardCategoriaController::class, 'buscar'],
      'GET:/{subdominio}d/categoria/{id}' => [DashboardCategoriaController::class, 'buscar'],
      'POST:/{subdominio}/d/categoria' => [DashboardCategoriaController::class, 'adicionar'],
      'PUT:/{subdominio}/d/categoria/{id}' => [DashboardCategoriaController::class, 'atualizar'],
      'PUT:/{subdominio}/d/categoria/ordem' => [DashboardCategoriaController::class, 'atualizarOrdem'],
      'DELETE:/{subdominio}/d/categoria/{id}' => [DashboardCategoriaController::class, 'apagar'],

      // Dashboard - Usuários
      'GET:/{subdominio}/d/usuarios' => [DashboardUsuarioController::class, 'buscar'],
      'GET:/{subdominio}/d/usuario/{id}' => [DashboardUsuarioController::class, 'buscar'],
      'POST:/{subdominio}/d/usuario' => [DashboardUsuarioController::class, 'adicionar'],
      'PUT:/{subdominio}/d/usuario/{id}' => [DashboardUsuarioController::class, 'atualizar'],
      'DELETE:/{subdominio}/d/usuario/{id}' => [DashboardUsuarioController::class, 'apagar'],
      'GET:/{subdominio}/d/usuario/desbloquear/{id}' => [DashboardUsuarioController::class, 'desbloquear'],
    ];

    return $rotas[ $rotaRequisitada ] ?? [];
  }
}