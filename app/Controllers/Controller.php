<?php
namespace app\Controllers;
use app\Models\Model;

class Controller
{
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

  public function buscarAjuste(string $nome)
  {
    $ajusteModel = new Model('Ajuste');

    $condicoes = [
      'Ajuste.nome' => $nome,
    ];

    $colunas = [
      'Ajuste.ativo',
    ];

    $resultado = $ajusteModel->condicao($condicoes)
                             ->buscar($colunas);

    return intval($resultado[0]['Ajuste.ativo'] ?? 0);
  }

  public function buscarUsuarioLogado(string $chave = ''): string
  {
    $usuario = [
      'id' => intval($_SESSION['usuario']['id'] ?? 0),
      'email' => $_SESSION['usuario']['email'] ?? '',
      'nivel' => intval($_SESSION['usuario']['nivel'] ?? 0),
      'padrao' => intval($_SESSION['usuario']['padrao'] ?? 0),
      'empresa_id' => intval($_SESSION['usuario']['empresa_id'] ?? 0),
      'subdominio' => $_SESSION['usuario']['subdominio'] ?? '',
    ];

    if ($chave and isset($usuario[ $chave ])) {
      return $usuario[ $chave ];
    }

    return '';
  }
}