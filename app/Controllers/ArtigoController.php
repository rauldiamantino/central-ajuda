<?php
namespace app\Controllers;
use app\Models\ArtigoModel;
use app\Models\CategoriaModel;
use app\Controllers\ViewRenderer;

class ArtigoController extends Controller
{
  protected $middleware;
  protected $artigoModel;
  protected $categoriaModel;
  protected $visao;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/artigo');
    $this->artigoModel = new ArtigoModel();
    $this->categoriaModel = new CategoriaModel();

    parent::__construct($this->artigoModel);
  }

  public function artigosVer()
  {
    $limite = 20;
    $pagina = intval($_GET['pagina'] ?? 0);

    // Recupera quantidade de páginas
    $artigosTotal = $this->artigoModel->contar('Artigo.id');
    $artigosTotal = $artigosTotal['total'] ?? 0;
    $paginasTotal = ceil($artigosTotal / $limite);

    $pagina = abs($pagina);
    $pagina = max($pagina, 1);
    $pagina = min($pagina, $paginasTotal);

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.visualizacoes',
      'Categoria.nome',
      'Artigo.criado',
      'Artigo.modificado',
      'Artigo.ativo',
    ];

    $uniao = [
      'Categoria',
    ];

    $resultado = $this->artigoModel->uniao2($uniao)
                                   ->pagina($limite, $pagina)
                                   ->buscar($colunas);

    // Calcular início e fim do intervalo
    $intervaloInicio = ($pagina - 1) * $limite + 1;
    $intervaloFim = min($pagina * $limite, $artigosTotal);

    $this->visao->variavel('artigos', $resultado);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/index');
  }

  public function artigoEditarVer(int $id)
  {
    $id = (int) $id;

    $condicao = [
      'Artigo.id' => $id,
    ];

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.visualizacoes',
      'Categoria.nome',
      'Usuario.nome',
      'Artigo.criado',
      'Artigo.modificado',
      'Artigo.ativo',
    ];

    $uniao = [
      'Usuario',
      'Categoria',
    ];

    $artigo = $this->artigoModel->uniao2($uniao)
                                ->condicao($condicao)
                                ->buscar($colunas);
    
    if (isset($artigo['erro']) and $artigo['erro']) {
      $_SESSION['erro'] = $artigo['erro']['mensagem'] ?? '';

      header('Location: /dashboard/artigos');
      exit();
    }

    $condCategoria = [
      'Categoria.ativo' => 1,
    ];

    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $categorias = $this->categoriaModel->condicao($condCategoria)
                                       ->buscar($colCategoria);

    if (! isset($categorias[0]['Categoria.nome'])) {
      $categorias = [];
    }

    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('titulo', 'Editar artigo');
    $this->visao->renderizar('/editar');
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

    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/artigos');
      exit();
    }
    elseif ($_POST) { 
      $_SESSION['ok'] = 'Registro alterado com sucesso';

      header('Location: /dashboard/artigos');
      exit();
    }

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
      $_SESSION['erro'] = $resultado['erro'];

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($rollback) {
      return $resultado;
    }

    $_SESSION['ok'] = 'Artigo excluído com sucesso';

    $this->responderJson($resultado);
  }
}