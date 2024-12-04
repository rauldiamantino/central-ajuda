<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardCategoriaModel;

class DashboardCategoriaController extends DashboardController
{
  protected $artigoModel;
  protected $categoriaModel;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function categoriasVer()
  {
    $limite = 10;
    $acao = htmlspecialchars($_GET['acao'] ?? '');
    $paginaAtual = intval($_GET['pagina'] ?? 0);
    $resultado = [];
    $condicoes = [];

    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    // Filtros
    $categoriaId = $_GET['id'] ?? '';
    $categoriaStatus = $_GET['status'] ?? '';
    $categoriaNome = urldecode($_GET['nome'] ?? '');
    $filtroAtual = [];

    // Filtrar por ID
    if (isset($_GET['id'])) {
      $filtroAtual['id'] = $categoriaId;
      $condicoes[] = ['campo' => 'Categoria.id', 'operador' => '=', 'valor' => (int) $categoriaId];
    }

    // Filtrar por status
    if (isset($_GET['status'])) {
      $filtroAtual['status'] = $categoriaStatus;
      $condicoes[] = ['campo' => 'Categoria.ativo', 'operador' => '=', 'valor' => (int) $categoriaStatus];
    }

    // Filtrar por título
    if (isset($_GET['nome'])) {
      $filtroAtual['nome'] = $categoriaNome;
      $condicoes[] = ['campo' => 'Categoria.nome', 'operador' => 'LIKE', 'valor' => '%' . $categoriaNome . '%'];
    }

    // Recupera quantidade de páginas
    $categoriasTotal = $this->categoriaModel->contar('Categoria.id')
                                            ->condicao($condicoes)
                                            ->executarConsulta();

    $categoriasTotal = $categoriasTotal['total'] ?? 0;
    $paginasTotal = ceil($categoriasTotal / $limite);

    $paginaAtual = abs($paginaAtual);
    $paginaAtual = max($paginaAtual, 1);
    $paginaAtual = min($paginaAtual, $paginasTotal);

    // Redireciona para a página onde a nova categoria está
    if ($acao == 'adicionado') {
      $paginaAtual = $paginasTotal;
    }

    $colunas = [
      'Categoria.id',
      'Categoria.icone',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.criado',
      'Categoria.ativo',
      'Categoria.modificado',
      'Categoria.ordem',
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $resultado = $this->categoriaModel->selecionar($colunas)
                                      ->condicao($condicoes)
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

    $ordem = [];
    $ordemAtual = 0;

    if ($resultado) {
      $colCategoriaOrdem = [
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

      $ordemAtual = intval($resultadoOrdem[0]['Categoria']['ordem'] ?? 0);
    }

    $ordem = [
      'prox' => $ordemAtual + 1,
    ];

    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('filtroAtual', $filtroAtual);
    $this->visao->variavel('pagina', $paginaAtual);
    $this->visao->variavel('categoriasTotal', $categoriasTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('metaTitulo', 'Categorias - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'categorias');
    $this->visao->renderizar('/categoria/index');
  }

  public function categoriaEditarVer(int $id)
  {
    $limite = 10;
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $paginaAtual = intval($_GET['pagina'] ?? 0);

    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    $condContarArtigos = [
      'campo' => 'Artigo.categoria_id',
      'operador' => '=',
      'valor' => $id,
    ];

    $artigosTotal = $this->artigoModel->contar('Artigo.id')
                                      ->condicao($condContarArtigos)
                                      ->executarConsulta();

    $artigosTotal = intval($artigosTotal['total'] ?? 0);

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
      'Categoria.icone',
      'Categoria.ativo',
      'Categoria.criado',
      'Categoria.modificado',
      'Categoria.meta_titulo',
      'Categoria.meta_descricao',
      'Artigo.id',
      'Artigo.titulo',
      'Artigo.ativo',
      'Artigo.criado',
      'Artigo.ordem',
      'Artigo.empresa_id',
      'Usuario.nome',
      'Usuario.email',
    ];

    $uniaoArtigos = [
      'tabelaJoin' => 'Artigo',
      'campoA' => 'Artigo.categoria_id',
      'campoB' => 'Categoria.id',
    ];

    $uniaoUsuarios = [
      'tabelaJoin' => 'Usuario',
      'campoA' => 'Usuario.id',
      'campoB' => 'Artigo.usuario_id',
    ];

    $ordem = [
      'Categoria.nome' => 'ASC',
      'Artigo.ordem' => 'ASC',
    ];

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);
      $paginaAtual = abs($paginaAtual);
      $paginaAtual = max($paginaAtual, 1);
      $paginaAtual = min($paginaAtual, $paginasTotal);
    }

    $categoria = $this->categoriaModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->juntar($uniaoArtigos, 'LEFT')
                                      ->juntar($uniaoUsuarios, 'LEFT')
                                      ->pagina($limite, $paginaAtual)
                                      ->ordem($ordem)
                                      ->executarConsulta();

    $intervaloInicio = ($paginaAtual - 1) * $limite + 1;
    $intervaloFim = min($paginaAtual * $limite, $artigosTotal);

    if (isset($categoria['erro']) and $categoria['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias', $categoria['erro']);
    }

    // Adicionar artigo
    $ordem = [];
    $ultimo = end($categoria);
    $ordemAtual = intval($ultimo['Artigo']['ordem'] ?? 0);

    $ordem = [
      'prox' => $ordemAtual + 1,
    ];

    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('categoria', $categoria);
    $this->visao->variavel('pagina', $paginaAtual);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('metaTitulo', 'Editar categoria - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'categorias');
    $this->visao->renderizar('/categoria/editar/index');
  }

  public function adicionar(array $params = []): array
  {
    $dados = $this->receberJson();
    $resultado = $this->categoriaModel->adicionar($dados);

    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    if (! isset($resultado['id']) or empty($resultado['id'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias', $resultado['erro'] ?? '');
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $resultado['id'] . '?referer=' . $botaoVoltar, 'Categoria criada com sucesso');
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
    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    $json = $this->receberJson();
    $resultado = $this->categoriaModel->atualizar($json, $id);

    $referer = '';

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/'. $id . $referer, $resultado['erro']);
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $id, $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $id . '-artigos', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $id . $referer, 'Registro alterado com sucesso');
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