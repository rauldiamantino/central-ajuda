<?php
namespace app\Controllers;
use app\Models\MiddlewareModel;

class Controller
{
  protected $middleware;

  public function __construct($model)
  {
    $this->middleware = new MiddlewareModel($model);
  }

  protected function processarRequisicao(array $dados = [])
  {
    $processarRequisicao = $this->middleware->processarRequisicao($dados);

    if (isset($processarRequisicao['erro'])) {
      $this->responderJson($processarRequisicao, 401);
    }
  }

  protected function receberJson(): array
  {
    $dados = $_POST;
    
    if (empty($dados)) {
      $json = file_get_contents("php://input");
      $dados = json_decode(trim($json), true);
    }

    if (json_last_error() != JSON_ERROR_NONE) {
      $this->responderJson(['erro' => 'Requisição Inválida'], 400);
      exit;
    }

    return $dados;
  }

  protected function responderJson(array $dados, int $codigoStatus = 200): void
  {
    header('Content-Type: application/json');
    http_response_code($codigoStatus);
    echo json_encode($dados, JSON_FORMATADO);
    exit;
  }

  protected function renderizarView(string $template, array $dados = [])
  {
    echo $this->view->render($template, $dados);
    exit;
  }
}