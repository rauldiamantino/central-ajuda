<?php
namespace app\Controllers;
use app\Models\EmpresaModel;
use app\Controllers\ViewRenderer;

class EmpresaController extends Controller
{
  protected $middleware;
  protected $empresaModel;
  protected $visao;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/empresa');
    $this->empresaModel = new EmpresaModel();

    parent::__construct($this->empresaModel);
  }

  public function empresaVer()
  {
    $dados = [
      'titulo' => 'Empresa',
      'empresa' => [
        'teste' => '1234',
      ],
    ];

    $this->visao->renderizar('/cadastro/index', $dados);
  }

  public function adicionar(array $params = []): array
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }

    $resultado = $this->empresaModel->adicionar($dados);

    if (isset($resultado['erro']) and $params) {
      return $resultado;
    }

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $condicao = [
      'Empresa.id' => $resultado['id'],
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.nome',
      'Empresa.cnpj',
      'Empresa.criado',
      'Empresa.modificado',
    ];

    $empresa = $this->empresaModel->condicao($condicao)
                                  ->buscar($colunas);

    if ($params) {
      return reset($empresa);
    }

    $this->responderJson(reset($empresa));
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao = [
        'Empresa.id' => $id,
      ];
    }

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.nome',
      'Empresa.cnpj',
      'Empresa.criado',
      'Empresa.modificado',
    ];

    $resultado = $this->empresaModel->condicao($condicao)
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
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->empresaModel->apagar($id);

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