<?php
namespace app\Controllers;
use app\Models\CategoriaModel;
use app\Controllers\ViewRenderer;

class CategoriaController extends Controller
{
  protected $middleware;
  protected $categoriaModel;
  protected $visao;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/categoria');
    $this->categoriaModel = new categoriaModel();

    parent::__construct($this->categoriaModel);
  }

  public function categoriasVer()
  {
    $colunas = [
      'Categoria.id',
      'Categoria.ativo',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.criado',
      'Categoria.modificado',
    ];

    $resultado = $this->categoriaModel->buscar($colunas);

    $this->visao->variavel('titulo', 'Categorias');
    $this->visao->variavel('categorias', $resultado);
    $this->visao->renderizar('/index');
  }

  public function categoriaAdicionarVer()
  {
    $dados = [
      'titulo' => 'Categoria',
      'categorias' => [
        'teste' => 1234,
      ],
    ];

    $this->visao->renderizar('/adicionar/index', $dados);
  }

  public function categoriaEditarVer()
  {
    $dados = [
      'titulo' => 'Categoria',
      'categorias' => [
        'teste' => 1234,
      ],
    ];

    $this->visao->renderizar('/editar/index', $dados);
  }

  public function adicionar(array $params = []): array
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }

    $resultado = $this->categoriaModel->adicionar($dados);

    if (isset($resultado['erro']) and $params) {
      return $resultado;
    }

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $condicao = [
      'Categoria.id' => $resultado['id'],
    ];

    $colunas = [
      'Categoria.id',
      'Categoria.ativo',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.criado',
      'Categoria.modificado',
    ];

    $empresa = $this->categoriaModel->condicao($condicao)
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
        'Categoria.id' => $id,
      ];
    }

    $colunas = [
      'Categoria.id',
      'Categoria.ativo',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.criado',
      'Categoria.modificado',
    ];

    $resultado = $this->categoriaModel->condicao($condicao)
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
    $resultado = $this->categoriaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->categoriaModel->apagar($id);

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