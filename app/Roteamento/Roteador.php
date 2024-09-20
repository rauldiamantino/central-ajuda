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

class Roteador
{
  public function rotear()
  {
    global $sessaoUsuario;

    $paginaErro = new PaginaErroController();

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

    // Público
    $empresaId = intval($buscarEmpresa[0]['Empresa.id'] ?? 0);

    // Usuário logado
    $usuarioLogado = $sessaoUsuario->buscar('usuario');
    $usuarioLogadoSubdominio = $usuarioLogado['subdominio'] ?? '';
    $usuarioLogadoEmpresaId = intval($usuarioLogado['empresa_id'] ?? 0);

    // Dashboard
    if (strpos($chaveRota, '/{subdominio}/dashboard')) {

      // Impede acesso a empresa diferente da que está logada
      if ($usuarioLogadoSubdominio and $usuarioLogadoSubdominio !== $subdominio) {
        return $paginaErro->erroVer();
      }

      // Impede acesso para usuário deslogado
      if ($empresaId == 0 or (int) $usuarioLogadoEmpresaId == 0) {
        $sessaoUsuario->apagar('usuario');

        header('Location: /login');
        exit;
      }

      $empresaId = $usuarioLogadoEmpresaId;
    }

    // Acesso sem subdomínio
    if ($empresaId == 0 and $this->rotaPermitida($chaveRota)) {
      $empresaId = $usuarioLogadoEmpresaId;
    }
    elseif ($empresaId == 0) {
      return $paginaErro->erroVer();
    }

    // Grava EmpresaID na sessão
    $sessaoUsuario->definir('empresa_id', $empresaId);
    $sessaoUsuario->definir('subdominio', $subdominio);

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
      $paginaErro->erroVer();
    }
  }

  private function subdominioPermitido(string $subdominio = ''): bool
  {
    $subdominios = [
      'teste',
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

  private function acessarRota(string $rotaRequisitada = ''): array
  {
    $rotas = [
      // Acesso sem domínio
      'POST:/cadastro' => [DashboardCadastroController::class, 'adicionar'],
      'PUT:/empresa/{id}' => [DashboardEmpresaController::class, 'atualizar'],
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],
      'GET:/login' => [DashboardLoginController::class, 'loginVer'],
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
    ];

    return $rotas[ $rotaRequisitada ] ?? [];
  }
}