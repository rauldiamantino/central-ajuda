<?php
namespace app\Models;

class MiddlewareModel extends Model
{
  protected $model;

  public function __construct($model)
  {
    $this->model = new $model();
  }

  public function processarRequisicao(array $dados = []): array
  {
    $this->logRequisicao($dados);

    return $this->verificarAutenticacao();
  }

  protected function logRequisicao(array $dados): void
  {
    $metodo = $_SERVER['REQUEST_METHOD'];
    $url = $_SERVER['REQUEST_URI'];
    $cabecalho = getallheaders();
    $cabecalho = $cabecalho;

    $logMensagem = str_repeat("-", 150) . PHP_EOL . PHP_EOL;
    $logMensagem .= date('Y-m-d H:i:s') . PHP_EOL;

    $arrayMensagem = [
      'metodo' => $metodo,
      'endpoint' => $url,
      'cabecalho' => $cabecalho,
      'corpo' => $dados,
    ];

    $logMensagem .= json_encode($arrayMensagem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL;

    error_log($logMensagem, 3, '../app/logs/api_' . date('Y-m-d') . '.log');
  }

  protected function verificarAutenticacao(bool $api = false): array
  {
    $cabecalhos = getallheaders();
    $erro = false;

    if ($api and ! isset($cabecalhos['Authorization'])) {
      $erro = true;
    }

    if ($api and ! $erro) {
      $token = str_replace('Bearer ', '', $cabecalhos['Authorization']);
      $erro = $this->validarToken($token);
    }

    if ($erro) {
      return ['erro' => 'Token inválido'];
    }

    return [];
  }

  protected function validarToken(string $token): bool
  {
    $condicao = [
      'token' => $token,
    ];

    $colunas = [
      'account_id'
    ];

    $resultado = $this->model->condicao($condicao)
                             ->buscar($colunas);

    if (empty($resultado) or isset($resultado['erro'])) {
      return true;
    }

    return false;
  }
}