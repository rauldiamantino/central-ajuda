<?php
namespace app\Roteamento;
use app\Controllers\TesteController;
use app\Controllers\UsuarioController;
use app\Controllers\EmpresaController;
use app\Controllers\EmpresaCadastroController;
use app\Controllers\DashboardController;
use app\Controllers\ArtigoController;
use app\Controllers\ConteudoController;
use app\Controllers\CategoriaController;
use app\Controllers\PublicoController;

class Roteador
{
  protected $rotas;

  public function __construct()
  {
    $this->rotas = [
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/dashboard' => [DashboardController::class, 'dashboardVer'],
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

      'GET:/artigos' => [ArtigoController::class, 'buscar'],
      'GET:/artigo/{id}' => [ArtigoController::class, 'buscar'],
      'POST:/artigo' => [ArtigoController::class, 'adicionar'],
      'PUT:/artigo/{id}' => [ArtigoController::class, 'atualizar'],
      'PUT:/artigo/ordem' => [ArtigoController::class, 'atualizarOrdem'],
      'DELETE:/artigo/{id}' => [ArtigoController::class, 'apagar'],

      'GET:/conteudos' => [ConteudoController::class, 'buscar'],
      'GET:/conteudo/{id}' => [ConteudoController::class, 'buscar'],
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
      header('Content-Type: application/json');
      http_response_code(404);
      echo json_encode(['erro' => 'Recurso não encontrado'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      exit;
    }
  }
}