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

    // Faz upload de imagem e recupera URL
    if (isset($dados['tipo']) and $dados['tipo'] == 2 and isset($_FILES['url']) and $_FILES['url']) {
      $imagem = $_FILES['url'];
      $local = 'img/conteudo/';

      if (! is_dir($local)) {
        mkdir($local, 0777, true);
      }
      
      $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
      $arquivo = $local . md5(time() . $imagem['name']) . '.' . $extensao;

      if (move_uploaded_file($imagem['tmp_name'], $arquivo)) {
        $dados['url'] = $arquivo;
      }
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

  public function ajustes(string $nome)
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
}