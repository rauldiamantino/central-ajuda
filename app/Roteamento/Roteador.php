<?php
namespace app\Roteamento;
use app\Controllers\TesteController;
use app\Controllers\UsuarioController;
use app\Controllers\EmpresaController;
use app\Controllers\EmpresaCadastroController;
use app\Controllers\DashboardController;
use app\Controllers\ArtigoController;

class Roteador
{
  protected $rotas;

  public function __construct()
  {
    $this->rotas = [
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/dashboard/artigos' => [ArtigoController::class, 'artigosVer'],
      'GET:/dashboard/artigo/editar' => [ArtigoController::class, 'artigoEditarVer'],
      'GET:/dashboard/artigo/adicionar' => [ArtigoController::class, 'artigoAdicionarVer'],

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