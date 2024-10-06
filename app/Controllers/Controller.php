<?php
namespace app\Controllers;
use app\Models\Model;

class Controller
{
  protected $model;
  protected $sessaoUsuario;
  protected $usuarioLogado;
  protected $empresaPadraoId;

  public function __construct()
  {
    $this->recuperarSessao();
  }

  private function recuperarSessao()
  {
    global $sessaoUsuario;
    $this->sessaoUsuario = $sessaoUsuario;

    $resultado = $this->sessaoUsuario->buscar('usuario');

    $this->usuarioLogado = [
      'id' => intval($resultado['id'] ?? 0),
      'nome' => $resultado['nome'] ?? '',
      'email' => $resultado['email'] ?? '',
      'nivel' => intval($resultado['nivel'] ?? 0),
      'padrao' => intval($resultado['padrao'] ?? 0),
      'empresaId' => intval($resultado['empresaId'] ?? 0),
      'empresaAtivo' => intval($resultado['empresaAtivo'] ?? 0),
      'subdominio' => $resultado['subdominio'] ?? '',
      'tentativasLogin' => intval($resultado['tentativas_login'] ?? 0),
    ];

    $this->empresaPadraoId = (int) $this->sessaoUsuario->buscar('empresaPadraoId');
  }

  protected function redirecionarErro(string $rota, $mensagem = []): void
  {
    if (isset($mensagem['mensagem'])) {
      $mensagem = $mensagem['mensagem'];
    }

    $this->sessaoUsuario->definir('erro', $mensagem);

    header('Location: ' . $rota);
    exit();
  }

  protected function redirecionarSucesso(string $rota, $mensagem = []): void
  {
    if (isset($mensagem['mensagem'])) {
      $mensagem = $mensagem['mensagem'];
    }

    $this->sessaoUsuario->definir('ok', $mensagem);

    header('Location: ' . $rota);
    exit();
  }

  protected function redirecionar(string $rota, $mensagem = []): void
  {
    if (isset($mensagem['mensagem'])) {
      $mensagem = $mensagem['mensagem'];
    }

    if ($mensagem) {
      $this->sessaoUsuario->definir('neutra', $mensagem);
    }

    header('Location: ' . $rota);
    exit();
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

  public function buscarAjuste(string $nome)
  {
    $ajusteModel = new Model($this->usuarioLogado, $this->empresaPadraoId, 'Ajuste');

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