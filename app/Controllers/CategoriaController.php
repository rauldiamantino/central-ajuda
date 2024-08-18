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
    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);

    // Recupera quantidade de páginas
    $categoriasTotal = $this->categoriaModel->contar('Categoria.id');

    $categoriasTotal = $categoriasTotal['total'] ?? 0;
    $paginasTotal = ceil($categoriasTotal / $limite);

    $pagina = abs($pagina);
    $pagina = max($pagina, 1);
    $pagina = min($pagina, $paginasTotal);

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.criado',
      'Categoria.ativo',
      'Categoria.modificado',
    ];

    $resultado = $this->categoriaModel->pagina($limite, $pagina)
                                      ->ordem(['Categoria.ordem' => 'ASC'])
                                      ->buscar($colunas);

    // Calcular início e fim do intervalo
    $intervaloInicio= 0;
    $intervaloFim = 0;

    if ($categoriasTotal) {
      $intervaloInicio = ($pagina - 1) * $limite + 1;
      $intervaloFim = min($pagina * $limite, $categoriasTotal);
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('categoriasTotal', $categoriasTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Categorias');
    $this->visao->renderizar('/index');
  }

  public function categoriaEditarVer(int $id)
  {
    $id = (int) $id;

    $condicao = [
      'Categoria.id' => $id,
    ];

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.ativo',
      'Categoria.criado',
      'Categoria.modificado',
    ];

    $categoria = $this->categoriaModel->condicao($condicao)
                                      ->buscar($colunas);
    
    if (isset($categoria['erro']) and $categoria['erro']) {
      $_SESSION['erro'] = $categoria['erro']['mensagem'] ?? '';

     header('Location: /dashboard/categorias');
      exit();
    }

    $this->visao->variavel('categoria', reset($categoria));
    $this->visao->variavel('titulo', 'Editar categoria');
    $this->visao->renderizar('/editar');
  }

  public function categoriaAdicionarVer()
  {
    $colCategoriaOrdem = [
      'Categoria.id',
      'Categoria.ordem',
    ];

    $ordCategoriaOrdem = [
      'Categoria.ordem' => 'DESC',
    ];

    $limiteCategoriaOrdem = 1;

    $resultadoOrdem = $this->categoriaModel->ordem($ordCategoriaOrdem)
                                           ->limite($limiteCategoriaOrdem)
                                           ->buscar($colCategoriaOrdem);

    $ordem = [];
    $ordemAtual = intval($resultadoOrdem[0]['Categoria.ordem'] ?? 0);

    if ($resultadoOrdem) {
      $ordem = [
        'prox' => $ordemAtual + 1,
      ];
    }

    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('titulo', 'Adicionar categoria');
    $this->visao->renderizar('/adicionar');
  }

  public function adicionar(array $params = []): array
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }

    // Adicionar categoria
    $resultado = $this->categoriaModel->adicionar($dados);

    // Requisição interna
    if ($params and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif ($params and isset($resultado['id'])) {
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

      $categoria = $this->categoriaModel->condicao($condicao)
                                        ->buscar($colunas);

      if ($params) {
        return reset($categoria);
      }
    }

    // Formulário via POST
    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

     header('Location: /dashboard/categorias');
      exit();
    }
    elseif ($_POST and isset($resultado['id'])) { 
      $_SESSION['ok'] = 'Categoria criada com sucesso';

     header('Location: /dashboard/categorias');
      exit();
    }

    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function buscar(int $id = 0)
  {
    if ($id) {
      $condicao['Categoria.id'] = $id;
    }

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];
    
    $limite = 100;

    $resultado = $this->categoriaModel->ordem(['Categoria.ordem' => 'ASC'])
                                      ->limite($limite)
                                      ->buscar($colunas);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();

    // Adicionar categoria
    $resultado = $this->categoriaModel->atualizar($json, $id);

    if ($_POST and isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

     header('Location: /dashboard/categorias');
      exit();
    }
    elseif ($_POST) { 
      $_SESSION['ok'] = 'Registro alterado com sucesso';

     header('Location: /dashboard/categorias');
      exit();
    }

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function atualizarOrdem()
  {
    $json = $this->receberJson();
    $resultado = $this->categoriaModel->atualizarOrdem($json);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'] ?? '';

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->categoriaModel->apagarCategoria($id);

    if ($rollback and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($rollback) {
      return $resultado;
    }

    $_SESSION['ok'] = 'Categoria excluída com sucesso';

    $this->responderJson($resultado);
  }
}