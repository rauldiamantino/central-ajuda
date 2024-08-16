<?php
namespace app\Roteamento;
use app\Controllers\TesteController;
use app\Controllers\LoginController;
use app\Controllers\CadastroController;
use app\Controllers\UsuarioController;
use app\Controllers\EmpresaController;
use app\Controllers\EmpresaCadastroController;
use app\Controllers\DashboardController;
use app\Controllers\ArtigoController;
use app\Controllers\ConteudoController;
use app\Controllers\CategoriaController;
use app\Controllers\PublicoController;
use app\Controllers\PaginaErroController;

class Roteador
{
  protected $rotas;
  protected $subdominios;
  protected $paginaErro;
  protected $empresaId;

  public function __construct()
  {
    $this->paginaErro = new PaginaErroController();

    $this->subdominios = [
      'localhost' => 99,
      'luminaon' => 1,
      'teste' => 39,
    ];

    // Subdomínio
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $partes = explode('.', $host);
    $this->empresaId = $this->subdominios[ $partes[0] ] ?? '';

    if (empty($this->empresaId)) {
      $this->paginaErro->erroVer();
      exit;
    }

    $this->rotas = [
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],
      'GET:/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/dashboard/login' => [LoginController::class, 'loginVer'],
      'GET:/dashboard/cadastro' => [CadastroController::class, 'cadastroVer'],
      'GET:/dashboard/artigos' => [ArtigoController::class, 'artigosVer'],
      'GET:/dashboard/artigo/editar/{id}' => [ArtigoController::class, 'artigoEditarVer'],
      'GET:/dashboard/artigo/adicionar' => [ArtigoController::class, 'artigoAdicionarVer'],
      'GET:/dashboard/categorias' => [CategoriaController::class, 'categoriasVer'],
      'GET:/dashboard/categoria/editar/{id}' => [CategoriaController::class, 'categoriaEditarVer'],
      'GET:/dashboard/categoria/adicionar' => [CategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/dashboard/usuarios' => [UsuarioController::class, 'UsuariosVer'],
      'GET:/dashboard/usuario/editar/{id}' => [UsuarioController::class, 'usuarioEditarVer'],
      'GET:/dashboard/usuario/adicionar' => [UsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/dashboard/empresa/editar' => [EmpresaController::class, 'empresaEditarVer'],
      
      'GET:/publico' => [PublicoController::class, 'publicoCategoriasVer'],
      'GET:/publico/categoria/{id}' => [PublicoController::class, 'publicoCategoriaVer'],
      'GET:/publico/artigo/{id}' => [PublicoController::class, 'publicoArtigoVer'],

      'POST:/dashboard/login' => [LoginController::class, 'login'],
      'GET:/dashboard/logout' => [LoginController::class, 'logout'],

      'GET:/artigos' => [ArtigoController::class, 'buscar'],
      'GET:/artigo/{id}' => [ArtigoController::class, 'buscar'],
      'POST:/artigo' => [ArtigoController::class, 'adicionar'],
      'PUT:/artigo/{id}' => [ArtigoController::class, 'atualizar'],
      'PUT:/artigo/ordem' => [ArtigoController::class, 'atualizarOrdem'],
      'DELETE:/artigo/{id}' => [ArtigoController::class, 'apagar'],

      'GET:/conteudos' => [ConteudoController::class, 'buscar'],
      'GET:/conteudos/{id}' => [ConteudoController::class, 'buscar'],
      'POST:/conteudo' => [ConteudoController::class, 'adicionar'],
      'PUT:/conteudo/{id}' => [ConteudoController::class, 'atualizar'],
      'PUT:/conteudo/ordem' => [ConteudoController::class, 'atualizarOrdem'],
      'DELETE:/conteudo/{id}' => [ConteudoController::class, 'apagar'],

      'GET:/categorias' => [CategoriaController::class, 'buscar'],
      'GET:/categoria/{id}' => [CategoriaController::class, 'buscar'],
      'POST:/categoria' => [CategoriaController::class, 'adicionar'],
      'PUT:/categoria/{id}' => [CategoriaController::class, 'atualizar'],
      'PUT:/categoria/ordem' => [CategoriaController::class, 'atualizarOrdem'],
      'DELETE:/categoria/{id}' => [CategoriaController::class, 'apagar'],

      'GET:/usuarios' => [UsuarioController::class, 'buscar'],
      'GET:/usuario/{id}' => [UsuarioController::class, 'buscar'],
      'POST:/usuario' => [UsuarioController::class, 'adicionar'],
      'PUT:/usuario/{id}' => [UsuarioController::class, 'atualizar'],
      'DELETE:/usuario/{id}' => [UsuarioController::class, 'apagar'],

      'GET:/empresas' => [EmpresaController::class, 'buscar'],
      'GET:/empresa/{id}' => [EmpresaController::class, 'buscar'],
      'POST:/empresa' => [EmpresaController::class, 'adicionar'],
      'PUT:/empresa/{id}' => [EmpresaController::class, 'atualizar'],
      'DELETE:/empresa/{id}' => [EmpresaController::class, 'apagar'],

      'POST:/cadastro' => [CadastroController::class, 'adicionar'],

      'GET:/empresas/cadastro/{id}' => [EmpresaCadastroController::class, 'buscar'],
      'POST:/empresas/cadastro' => [EmpresaCadastroController::class, 'adicionar'],
    ];
  }

  public function rotear()
  {
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $metodo = $_SERVER['REQUEST_METHOD'];
    $metodoOculto = $_POST['_method'] ?? null;

    if ($metodoOculto and in_array(strtoupper($metodoOculto), ['PUT', 'DELETE'])) {
      $metodo = strtoupper($metodoOculto);
    }

    $chaveRota = $metodo . ':' . $url;
    $id = (int) basename($url);
    $chaveRota = str_replace($id, '{id}', $chaveRota);

    if ($this->empresaId == 99 and strpos($chaveRota, '/dashboard') == false) {
      $this->paginaErro->erroVer();
      exit;
    }

    $_SESSION['empresa_id'] = $this->empresaId;

    // Redireciona para o login sempre que utilizar rotas da dashboard sem estar logado
    if (strpos($url, 'dashboard') and strpos($chaveRota, '/dashboard/login') == false and strpos($chaveRota, '/dashboard/cadastro') == false) {

      if (! isset($_SESSION['usuario']) or empty($_SESSION['usuario'])) {
        header('Location: /dashboard/login');
        exit;
      }
    }

    if (isset($this->rotas[ $chaveRota ])) {
      $rota = $this->rotas[ $chaveRota ];
      $controlador = new $rota[0]();
      $metodo = $rota[1];

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
}