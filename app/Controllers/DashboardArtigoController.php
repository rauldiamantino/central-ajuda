<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardConteudoModel;
use app\Models\DashboardCategoriaModel;

class DashboardArtigoController extends DashboardController
{
  protected $artigoModel;
  protected $conteudoModel;
  protected $categoriaModel;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->conteudoModel = new DashboardConteudoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function artigosVer()
  {
    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    $limite = 10;
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $paginaAtual = intval($_GET['pagina'] ?? 0);
    $resultado = [];
    $condicoes = [];

    // Filtros
    $artigoId = $_GET['id'] ?? '';
    $artigoTitulo = urldecode($_GET['titulo'] ?? '');
    $artigoStatus = $_GET['status'] ?? '';
    $categoriaId = $_GET['categoria_id'] ?? '';
    $categoriaNome = urldecode($_GET['categoria_nome'] ?? '');
    $filtroAtual = [];

    // Filtrar por categoria
    if (isset($_GET['categoria_id'])) {
      $filtroAtual['categoria_id'] = $categoriaId;

      if (intval($categoriaId) > 0) {
        $condicoes[] = ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => (int) $categoriaId];
      }
      elseif ($categoriaId === '0') {
        $condicoes[] = ['campo' => 'Artigo.categoria_id', 'operador' => 'IS', 'valor' => NULL];
      }
    }

    if (isset($_GET['categoria_nome'])) {
      $filtroAtual['categoria_nome'] = $categoriaNome;
    }

    // Filtrar por ID
    if (isset($_GET['id'])) {
      $filtroAtual['id'] = $artigoId;
      $condicoes[] = ['campo' => 'Artigo.id', 'operador' => '=', 'valor' => (int) $artigoId];
    }

    // Filtrar por status
    if (isset($_GET['status'])) {
      $filtroAtual['status'] = $artigoStatus;
      $condicoes[] = ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => (int) $artigoStatus];
    }

    // Filtrar por título
    if (isset($_GET['titulo'])) {
      $filtroAtual['titulo'] = $artigoTitulo;
      $condicoes[] = ['campo' => 'Artigo.titulo', 'operador' => 'LIKE', 'valor' => '%' . $artigoTitulo . '%'];
    }

    $artigosTotal = $this->artigoModel->contar('Artigo.id')
                                      ->condicao($condicoes)
                                      ->executarConsulta();

    $artigosTotal = intval($artigosTotal['total'] ?? 0);

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);
      $paginaAtual = abs($paginaAtual);
      $paginaAtual = max($paginaAtual, 1);
      $paginaAtual = min($paginaAtual, $paginasTotal);

      $colunas = [
        'Artigo.id',
        'Artigo.titulo',
        'Artigo.usuario_id',
        'Artigo.categoria_id',
        'Artigo.visualizacoes',
        'Categoria.nome',
        'Usuario.nome',
        'Artigo.ordem',
        'Artigo.criado',
        'Artigo.ativo',
        'Artigo.empresa_id',
      ];

      $uniaoCategoria = [
        'tabelaJoin' => 'Categoria',
        'campoA' => 'Categoria.id',
        'campoB' => 'Artigo.categoria_id',
      ];

      $uniaoUsuario = [
        'tabelaJoin' => 'Usuario',
        'campoA' => 'Usuario.id',
        'campoB' => 'Artigo.usuario_id',
      ];

      $ordem = [
        'Artigo.id' => 'DESC',
      ];

      $resultado = $this->artigoModel->selecionar($colunas)
                                     ->condicao($condicoes)
                                     ->juntar($uniaoCategoria, 'LEFT')
                                     ->juntar($uniaoUsuario)
                                     ->pagina($limite, $paginaAtual)
                                     ->ordem($ordem)
                                     ->executarConsulta();

      $intervaloInicio = ($paginaAtual - 1) * $limite + 1;
      $intervaloFim = min($paginaAtual * $limite, $artigosTotal);
    }

    // Exibe menu ao filtrar sem resultados
    if (! isset($resultado[0]) and $filtroAtual) {
      $resultado['filtro'] = true;
    }

    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    // Categorias do select ao adicionar
    $categorias = $this->categoriaModel->selecionar($colCategoria)
                                       ->executarConsulta();

    if (! isset($categorias[0]['Categoria']['nome'])) {
      $categorias = [];
    }

    $colArtigoOrdem = [
      'Artigo.id',
      'Artigo.ordem',
    ];

    $ordArtigoOrdem = [
      'Artigo.ordem' => 'DESC',
    ];

    $limiteArtigoOrdem = 1;

    $resultadoOrdem = $this->artigoModel->selecionar($colArtigoOrdem)
                                        ->ordem($ordArtigoOrdem)
                                        ->limite($limiteArtigoOrdem)
                                        ->executarConsulta();
    $ordem = [];
    $ordemAtual = intval($resultadoOrdem[0]['Artigo']['ordem'] ?? 0);

    $ordem = [
      'prox' => $ordemAtual + 1,
    ];

    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('filtroAtual', $filtroAtual);
    $this->visao->variavel('artigos', $resultado);
    $this->visao->variavel('pagina', $paginaAtual);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->variavel('paginaMenuLateral', 'artigos');
    $this->visao->renderizar('/artigo/index');
  }

  public function artigoEditarVer(int $id)
  {
    $artigo = [];
    $categorias = [];
    $conteudos = [];

    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    $ordemNum = [
      'prox' => 1,
    ];

    $condicao = [
      ['campo' => 'Artigo.id', 'operador' => '=', 'valor' => (int) $id],
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
      'Artigo.empresa_id',
    ];

    $uniaoCategoria = [
      'tabelaJoin' => 'Categoria',
      'campoA' => 'Categoria.id',
      'campoB' => 'Artigo.categoria_id',
    ];

    $uniaoUsuario = [
      'tabelaJoin' => 'Usuario',
      'campoA' => 'Usuario.id',
      'campoB' => 'Artigo.usuario_id',
    ];

    $resultado = $this->artigoModel->selecionar($colunas)
                                   ->condicao($condicao)
                                   ->juntar($uniaoCategoria, 'LEFT')
                                   ->juntar($uniaoUsuario)
                                   ->executarConsulta();

    if (isset($resultado['erro']) and $resultado['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', $resultado['erro']);
    }
    else {
      $artigo = $resultado;
    }

    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $resultado = $this->categoriaModel->selecionar($colCategoria)
                                      ->executarConsulta();

    if (isset($resultado[0]['Categoria']['nome'])) {
      $categorias = $resultado;
    }

    $condicao = [
      ['campo' => 'Conteudo.artigo_id', 'operador' => '=', 'valor' => (int) $id],
    ];

    $colunas = [
      'Conteudo.id',
      'Conteudo.ativo',
      'Conteudo.tipo',
      'Conteudo.titulo',
      'Conteudo.artigo_id',
      'Conteudo.titulo_ocultar',
      'Conteudo.conteudo',
      'Conteudo.empresa_id',
      'Conteudo.url',
      'Conteudo.ordem',
      'Conteudo.criado',
      'Conteudo.modificado',
    ];

    $ordem = [
      'Conteudo.ordem' => 'ASC',
    ];

    $resultado = $this->conteudoModel->selecionar($colunas)
                                     ->condicao($condicao)
                                     ->ordem($ordem)
                                     ->executarConsulta();

    if (isset($resultado[0]['Conteudo']['id'])) {
      $conteudos = $resultado;

      $condicao = [
        'campo' => 'Conteudo.artigo_id', 'operador' => '=', 'valor' => (int) $id,
      ];

      $colunas = [
        'Conteudo.id',
        'Conteudo.ordem',
      ];

      $ordem = [
        'Conteudo.ordem' => 'DESC',
      ];

      $limite = 1;

      $resultadoOrdem = $this->conteudoModel->selecionar($colunas)
                                            ->condicao($condicao)
                                            ->ordem($ordem)
                                            ->limite($limite)
                                            ->executarConsulta();

      $ordemAtual = intval($resultadoOrdem[0]['Conteudo']['ordem'] ?? 0);

      if ($resultadoOrdem) {
        $ordemNum['prox'] = $ordemAtual + 1;
      }
    }

    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('ordem', $ordemNum);
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('titulo', 'Editar artigo');
    $this->visao->variavel('paginaMenuLateral', 'artigos');
    $this->visao->renderizar('/artigo/editar/index');
  }

  public function adicionar(): array
  {
    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    $dados = $this->receberJson();
    $resultado = $this->artigoModel->adicionar($dados);

    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', $resultado['erro']);
    }
    elseif ($_POST and isset($resultado['id'])) {
      Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $dados['categoria_id'], $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $dados['categoria_id'] . '-artigos', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-artigos-categoria-' . $dados['categoria_id'], $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $resultado['id'] . '?referer=' . $botaoVoltar, 'Artigo criado com sucesso');
    }
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao[] = [
        'campo' => 'Artigo.id',
        'operador' => '=',
        'valor' => (int) $id,
      ];
    }

    // Filtrar por categoria
    $categoriaId = $_GET['categoria_id'] ?? '';

    if (isset($_GET['categoria_id'])) {

      if (intval($categoriaId) > 0) {
        $condicao[] = [
          'campo' => 'Artigo.categoria_id',
          'operador' => '=',
          'valor' => (int) $categoriaId,
        ];
      }
      elseif ($categoriaId === '0') {
        $condicao[] = [
          'campo' => 'Artigo.categoria_id',
          'operador' => 'IS',
          'valor' => NULL,
        ];
      }
    }

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
    ];

    $ordem = [
      'Artigo.ordem' => 'ASC'
    ];

    $limite = 500;

    $resultado = $this->artigoModel->selecionar($colunas)
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
    $resultado = $this->artigoModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', $resultado['erro']);
    }

    Cache::apagar('publico-artigo-' . $id, $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categoria-' . $json['categoria_id'] . '-artigos', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-artigos-categoria-' . $json['categoria_id'], $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $id . '?referer=' . $botaoVoltar, 'Registro alterado com sucesso');
  }

  public function atualizarOrdem()
  {
    $json = $this->receberJson();
    $resultado = $this->artigoModel->atualizarOrdem($json);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    // Cache
    $condicao = [
      'campo' => 'Artigo.id',
      'operador' => '=',
      'valor' => $json[0]['id'],
    ];

    $colunas = [
      'Artigo.categoria_id',
    ];

    $buscarArtigo = $this->artigoModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->executarConsulta();

    $categoriaId = $buscarArtigo[0]['Artigo']['categoria_id'] ?? 0;

    if ($categoriaId) {
      Cache::apagar('publico-artigos-categoria-' . $categoriaId, $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $categoriaId . '-artigos', $this->usuarioLogado['empresaId']);
    }

    $this->sessaoUsuario->definir('ok', 'Posições reorganizados');

    $this->responderJson($resultado);
  }

  public function apagar(int $id)
  {
    // Cache
    $condicao = [
      'campo' => 'Artigo.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Artigo.categoria_id',
    ];

    $buscarArtigo = $this->artigoModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->executarConsulta();

    $categoriaId = $buscarArtigo[0]['Artigo']['categoria_id'] ?? 0;

    // Apagar
    $resultado = $this->artigoModel->apagar($id);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($categoriaId) {
      Cache::apagar('publico-artigo-' . $id, $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $categoriaId . '-artigos', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-artigos-categoria-' . $categoriaId, $this->usuarioLogado['empresaId']);
    }

    $this->sessaoUsuario->definir('ok', 'Artigo excluído com sucesso');

    $this->responderJson($resultado);
  }
}