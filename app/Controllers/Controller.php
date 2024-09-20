<?php
namespace app\Controllers;

use app\Core\SessaoUsuario;
use app\Models\Model;

class Controller
{
  protected $usuarioLogado;
  protected $dashboardModel;
  protected $sessaoUsuario;
  protected $usuarioLogadoId;
  protected $usuarioLogadoEmail;
  protected $usuarioLogadoNivel;
  protected $usuarioLogadoPadrao;
  protected $usuarioLogadoEmpresaId;
  protected $usuarioLogadoEmpresaAtivo;
  protected $usuarioLogadoSubdominio;

  public function __construct()
  {
    global $sessaoUsuario;
    $this->sessaoUsuario = $sessaoUsuario;

    $this->usuarioLogado = $this->sessaoUsuario->buscar('usuario');
    $this->usuarioLogadoId = $this->usuarioLogado['id'] ?? 0;
    $this->usuarioLogadoEmail = $this->usuarioLogado['email'] ?? '';
    $this->usuarioLogadoNivel = $this->usuarioLogado['nivel'] ?? 0;
    $this->usuarioLogadoPadrao = $this->usuarioLogado['padrao'] ?? 0;
    $this->usuarioLogadoEmpresaId = $this->usuarioLogado['empresa_id'] ?? 0;
    $this->usuarioLogadoEmpresaAtivo = $this->usuarioLogado['empresa_ativo'] ?? 0;
    $this->usuarioLogadoSubdominio = $this->usuarioLogado['subdominio'] ?? '';
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