<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardCategoriaModel;

class DashboardCategoriaController extends DashboardController
{
  protected $categoriaModel;

  public function __construct()
  {
    parent::__construct();

    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function categoriasVer()
  {
    $limite = 10;
    $paginaAtual = intval($_GET['pagina'] ?? 0);

    // Recupera quantidade de páginas
    $categoriasTotal = $this->categoriaModel->contar('Categoria.id')
                                            ->executarConsulta();

    $categoriasTotal = $categoriasTotal['total'] ?? 0;
    $paginasTotal = ceil($categoriasTotal / $limite);

    $paginaAtual = abs($paginaAtual);
    $paginaAtual = max($paginaAtual, 1);
    $paginaAtual = min($paginaAtual, $paginasTotal);

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.criado',
      'Categoria.ativo',
      'Categoria.modificado',
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $resultado = $this->categoriaModel->selecionar($colunas)
                                      ->pagina($limite, $paginaAtual)
                                      ->ordem($ordem)
                                      ->executarConsulta();

    // Calcular início e fim do intervalo
    $intervaloInicio= 0;
    $intervaloFim = 0;

    if ($categoriasTotal) {
      $intervaloInicio = ($paginaAtual - 1) * $limite + 1;
      $intervaloFim = min($paginaAtual * $limite, $categoriasTotal);
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('pagina', $paginaAtual);
    $this->visao->variavel('categoriasTotal', $categoriasTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Categorias');
    $this->visao->variavel('paginaMenuLateral', 'categorias');
    $this->visao->renderizar('/categoria/index');
  }

  public function categoriaEditarVer(int $id)
  {
    $id = (int) $id;

    $condicao = [
      'campo' => 'Categoria.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.ativo',
      'Categoria.criado',
      'Categoria.modificado',
      'Artigo.id',
      'Artigo.titulo',
      'Artigo.ativo',
      'Artigo.empresa_id',
    ];

    $juntar = [
      'tabelaJoin' => 'Artigo',
      'campoA' => 'Artigo.categoria_id',
      'campoB' => 'Categoria.id',
    ];

    $categoria = $this->categoriaModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->juntar($juntar, 'LEFT')
                                      ->executarConsulta();

    if (isset($categoria['erro']) and $categoria['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias', $categoria['erro']);
    }

    $this->visao->variavel('categoria', $categoria);
    $this->visao->variavel('titulo', 'Editar categoria');
    $this->visao->variavel('paginaMenuLateral', 'categorias');
    $this->visao->renderizar('/categoria/editar/index');
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

    $resultadoOrdem = $this->categoriaModel->selecionar($colCategoriaOrdem)
                                           ->ordem($ordCategoriaOrdem)
                                           ->limite($limiteCategoriaOrdem)
                                           ->executarConsulta();

    $ordem = [];
    $ordemAtual = intval($resultadoOrdem[0]['Categoria']['ordem'] ?? 0);

    $ordem = [
      'prox' => $ordemAtual + 1,
    ];

    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('titulo', 'Adicionar categoria');
    $this->visao->variavel('paginaMenuLateral', 'categorias');
    $this->visao->renderizar('/categoria/adicionar');
  }

  public function adicionar(array $params = []): array
  {
    $dados = $this->receberJson();
    $resultado = $this->categoriaModel->adicionar($dados);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias', $resultado['erro']);
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias', 'Categoria criada com sucesso');
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao = [
        'campo' => 'Categoria.id',
        'operador' => '=',
        'valor' => $id,
      ];
    }

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $limite = 100;

    $resultado = $this->categoriaModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->ordem($ordem)
                                      ->limite($limite)
                                      ->executarConsulta();

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();
    $resultado = $this->categoriaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/'. $id, $resultado['erro']);
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $id, $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $id . '-artigos', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $id, 'Registro alterado com sucesso');
  }

  public function atualizarOrdem()
  {
    $json = $this->receberJson();
    $resultado = $this->categoriaModel->atualizarOrdem($json);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);

    $this->responderJson($resultado);
  }

  public function apagar(int $id)
  {
    $resultado = $this->categoriaModel->apagarCategoria($id);

    if (isset($resultado['erro'])) {
      $mensagem = $resultado['erro']['mensagem'] ?? '';
      $this->sessaoUsuario->definir('erro', $mensagem);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);

    $this->sessaoUsuario->definir('ok', 'Categoria excluída com sucesso');
    $this->responderJson($resultado);
  }
}