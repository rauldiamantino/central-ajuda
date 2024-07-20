<?php
namespace app\Controllers;
use app\Models\UsuarioModel;

class UsuarioController extends Controller
{
  protected $middleware;
  protected $usuarioModel;

  public function __construct()
  {
    $this->usuarioModel = new UsuarioModel();
    parent::__construct($this->usuarioModel);
  }

  public function adicionar($params = [])
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }

    $resultado = $this->usuarioModel->adicionar($dados);

    if ($params and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $condicao = [
      'Usuario.id' => $resultado['id'],
    ];

    $colunas = [
      'Usuario.id',
      'Usuario.ativo',
      'Usuario.nivel',
      'Usuario.empresa_id',
      'Usuario.padrao',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.telefone',
      'Usuario.criado',
      'Usuario.modificado',
    ];

    $usuario = $this->usuarioModel->condicao($condicao)
                                  ->buscar($colunas);

    if ($params) {
      return reset($usuario);
    }

    $this->responderJson(reset($usuario));
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao = [
        'Usuario.id' => $id,
      ];
    }

    $colunas = [
      'Usuario.id',
      'Usuario.ativo',
      'Usuario.nivel',
      'Usuario.empresa_id',
      'Usuario.padrao',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.telefone',
      'Usuario.criado',
      'Usuario.modificado',
    ];

    $resultado = $this->usuarioModel->condicao($condicao)
                                    ->buscar($colunas);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($id and count($resultado) == 1) {
      $resultado = reset($resultado);
    }

    $this->responderJson($resultado);
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();
    $resultado = $this->usuarioModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->usuarioModel->apagar($id);

    if ($rollback and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($rollback) {
      return $resultado;
    }

    $this->responderJson($resultado);
  }
}