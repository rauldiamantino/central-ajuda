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
    $resultado = [];
    $condicoes = [];

    $acao = htmlspecialchars($_GET['acao'] ?? '');
    $paginaAtual = intval($_GET['pagina'] ?? 0);
    $botaoVoltar = $this->obterReferer();

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

    if (empty($botaoVoltar)) {
      $botaoVoltar = '?referer=' . urlencode('/dashboard/categorias?pagina=' . $paginaAtual);
    }

    if ($filtroAtual) {
      foreach ($filtroAtual as $chave => $linha):

        if (empty($botaoVoltar)) {
          $botaoVoltar = '?referer=' . urlencode('/dashboard/categorias?' . $chave . '=' . $linha);
        }
        else {
          $botaoVoltar .= urlencode('&' . $chave . '=' . $linha);
        }
      endforeach;
    }

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
    $botaoVoltar = $this->obterReferer();

    // Total artigos
    $condContarArtigos[] = ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => $id];
    $condContarArtigos[] = ['campo' => 'Artigo.excluido', 'operador' => '=', 'valor' => INATIVO];

    $artigosTotal = $this->artigoModel->contar('Artigo.id')
                                      ->condicao($condContarArtigos)
                                      ->executarConsulta();

    $artigosTotal = intval($artigosTotal['total'] ?? 0);

    $id = (int) $id;

    // Categoria
    $condicao[] = ['campo' => 'Categoria.id', 'operador' => '=', 'valor' => $id];

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
    ];

    $categoria = $this->categoriaModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->limite(1)
                                      ->executarConsulta();

    if (isset($categoria['erro']) and $categoria['erro']) {
      $this->redirecionarErro('/dashboard/categorias', $categoria['erro']);
    }

    // Apenas primeira posição
    $categoria = reset($categoria);

    // Artigos
    $condicao = [];
    $condicao[] = ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => $id];
    $condicao[] = ['campo' => 'Artigo.excluido', 'operador' => '=', 'valor' => INATIVO];

    $colunas = [
      'Artigo.id',
      'Artigo.codigo',
      'Artigo.titulo',
      'Artigo.ativo',
      'Artigo.criado',
      'Artigo.ordem',
      'Artigo.empresa_id',
      'Usuario.nome',
      'Usuario.email',
    ];

    $uniaoUsuarios = [
      'tabelaJoin' => 'Usuario',
      'campoA' => 'Usuario.id',
      'campoB' => 'Artigo.usuario_id',
    ];

    $ordem = [
      'Artigo.ordem' => 'ASC',
    ];

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);
      $paginaAtual = abs($paginaAtual);
      $paginaAtual = max($paginaAtual, 1);
      $paginaAtual = min($paginaAtual, $paginasTotal);
    }

    $artigos = $this->artigoModel->selecionar($colunas)
                                 ->condicao($condicao)
                                 ->juntar($uniaoUsuarios, 'LEFT')
                                 ->pagina($limite, $paginaAtual)
                                 ->ordem($ordem)
                                 ->executarConsulta();

    if (isset($artigos['erro']) and $artigos['erro']) {
      $this->redirecionarErro('/dashboard/categoria/editar/' . $id, $artigos['erro']);
    }

    // Categoria e Artigos
    $categoria['artigos'] = $artigos;

    // Paginação
    $intervaloInicio = ($paginaAtual - 1) * $limite + 1;
    $intervaloFim = min($paginaAtual * $limite, $artigosTotal);

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
    $referer = '';
    $botaoVoltar = $this->obterReferer();

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }

    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->redirecionarErro('/dashboard/categorias' . $referer, MSG_ERRO_PERMISSAO);
    }

    $dados = $this->receberJson();
    $resultado = $this->categoriaModel->adicionar($dados);

    if (! isset($resultado['id']) or empty($resultado['id'])) {
      $this->redirecionarErro('/dashboard/categorias', $resultado['erro'] ?? '');
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/dashboard/categoria/editar/' . $resultado['id'] . $referer, 'Categoria criada com sucesso');
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

    if ($this->requisicaoFetch()) {

      if (isset($resultado['erro'])) {
        $codigo = $resultado['erro']['codigo'] ?? 500;
        $this->responderJson($resultado, $codigo);
      }

      $this->responderJson($resultado);
    }

    if (! is_array($resultado)) {
      $resultado = [];
    }

    return $resultado;
  }

  public function atualizar(int $id)
  {
    $referer = '';
    $botaoVoltar = $this->obterReferer();

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }

    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->redirecionarErro('/dashboard/categoria/editar/'. $id . $referer, MSG_ERRO_PERMISSAO);
    }

    $json = $this->receberJson();
    $resultado = $this->categoriaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/categoria/editar/'. $id . $referer, $resultado['erro']);
    }

    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $id, $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $id . '-artigos', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/dashboard/categoria/editar/' . $id . $referer, 'Registro alterado com sucesso');
  }

  public function atualizarOrdem()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }

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
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }

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