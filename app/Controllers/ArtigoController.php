<?php
namespace app\Controllers;
use app\Models\ArtigoModel;
use app\Controllers\ViewRenderer;

class ArtigoController extends Controller
{
  protected $middleware;
  protected $artigoModel;
  protected $visao;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/artigo');
    $this->artigoModel = new ArtigoModel();

    parent::__construct($this->artigoModel);
  }

  public function artigosVer()
  {
    $limite = 10;
    $pagina = intval($_GET['pag'] ?? 0);

    // Recupera quantidade de páginas
    $artigosTotal = $this->artigoModel->contar('Artigo.id');
    $artigosTotal = $artigosTotal['total'] ?? 0;
    $paginasTotal = ceil($artigosTotal / $limite);

    $pagina = abs($pagina);
    $pagina = max($pagina, 1);
    $pagina = min($pagina, $paginasTotal);

    $colunas = [
      'Artigo.id',
      'Artigo.ativo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.visualizacoes',
      'Categoria.nome',
      'Artigo.criado',
      'Artigo.modificado',
    ];

    $uniao = [
      'Categoria',
    ];

    $resultado = $this->artigoModel->uniao2($uniao)
                                   ->pagina($limite, $pagina)
                                   ->buscar($colunas);

    $artigosTotal = $this->artigoModel->contar('Artigo.id');
    $artigosTotal = $artigosTotal['total'] ?? 0;
    $paginasTotal = ceil($artigosTotal / $limite);

    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->variavel('artigos', $resultado);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->renderizar('/index');
  }

  public function artigoAdicionarVer()
  {
    $dados = [
      'titulo' => 'Artigo',
      'empresa' => [
        'teste' => 1234,
      ],
    ];

    $this->visao->renderizar('/adicionar/index', $dados);
  }

  public function artigoEditarVer()
  {
    $dados = [
      'titulo' => 'Artigo',
      'empresa' => [
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

    $resultado = $this->artigoModel->adicionar($dados);

    if (isset($resultado['erro']) and $params) {
      return $resultado;
    }

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $condicao = [
      'Artigo.id' => $resultado['id'],
    ];

    $colunas = [
      'Artigo.id',
      'Artigo.ativo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.visualizacoes',
      'Artigo.criado',
      'Artigo.modificado',
    ];

    $empresa = $this->artigoModel->condicao($condicao)
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
        'Artigo.id' => $id,
      ];
    }

    $colunas = [
      'Artigo.id',
      'Artigo.ativo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.visualizacoes',
      'Artigo.criado',
      'Artigo.modificado',
    ];

    $resultado = $this->artigoModel->condicao($condicao)
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
    $resultado = $this->artigoModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->artigoModel->apagar($id);

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