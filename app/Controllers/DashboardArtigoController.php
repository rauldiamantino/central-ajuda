<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardConteudoModel;
use app\Models\DashboardCategoriaModel;
use app\Controllers\DashboardController;
use app\Controllers\Components\DatabaseFirebaseComponent;

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
    $limite = 10;
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $paginaAtual = intval($_GET['pagina'] ?? 0);
    $botaoVoltar = $this->obterReferer();

    $resultado = [];
    $condicoes = [];
    $filtroAtual = [];

    // Filtros
    $artigoCodigo = $_GET['codigo'] ?? '';
    $artigoCodigo = $this->filtrarInjection($artigoCodigo);

    $artigoTitulo = urldecode($_GET['titulo'] ?? '');
    $artigoTitulo = $this->filtrarInjection($artigoTitulo);

    $artigoStatus = $_GET['status'] ?? '';
    $artigoStatus = $this->filtrarInjection($artigoStatus);

    $categoriaId = $_GET['categoria_id'] ?? '';
    $categoriaId = $this->filtrarInjection($categoriaId);

    $categoriaNome = urldecode($_GET['categoria_nome'] ?? '');
    $categoriaNome = $this->filtrarInjection($categoriaNome);

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

    // Filtrar por Código
    if (isset($_GET['codigo'])) {
      $filtroAtual['codigo'] = $artigoCodigo;
      $condicoes[] = ['campo' => 'Artigo.codigo', 'operador' => '=', 'valor' => (int) $artigoCodigo];
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

    // Não retorna artigos excluídos
    $condicoes[] = ['campo' => 'Artigo.excluido', 'operador' => '=', 'valor' => INATIVO];

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
        'Artigo.codigo',
        'Artigo.titulo',
        'Artigo.usuario_id',
        'Artigo.categoria_id',
        'Artigo.editar',
        'Categoria.ativo',
        'Categoria.nome',
        'Usuario.nome',
        'Usuario.email',
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
    $this->visao->variavel('metaTitulo', 'Artigos - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'artigos');
    $this->visao->renderizar('/artigo/index');
  }

  public function artigoEditarVer(int $codigo)
  {
    $artigoId = 0;
    $artigo = [];
    $categorias = [];
    $conteudos = [];
    $botaoVoltar = $this->obterReferer();

    $ordemNum = [
      'prox' => 1,
    ];

    $condicao = [
      ['campo' => 'Artigo.codigo', 'operador' => '=', 'valor' => (int) $codigo],
      ['campo' => 'Artigo.excluido', 'operador' => '=', 'valor' => INATIVO],
    ];

    $colunas = [
      'Artigo.id',
      'Artigo.codigo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.editar',
      'Categoria.ativo',
      'Categoria.nome',
      'Usuario.nome',
      'Usuario.foto',
      'Artigo.criado',
      'Artigo.modificado',
      'Artigo.ativo',
      'Artigo.empresa_id',
      'Artigo.meta_titulo',
      'Artigo.meta_descricao',
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
      $this->redirecionarErro('/dashboard/artigos', $resultado['erro']);
    }
    elseif (! isset($resultado[0]['Artigo']['id'])) {
      $this->redirecionarErro('/dashboard/artigos', 'Artigo não encontrado');
    }
    else {
      $artigo = $resultado;
      $artigoId = (int) $artigo[0]['Artigo']['id'];
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
      ['campo' => 'Conteudo.artigo_id', 'operador' => '=', 'valor' => (int) $artigoId],
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
        'campo' => 'Conteudo.artigo_id', 'operador' => '=', 'valor' => (int) $artigoId,
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
    $this->visao->variavel('metaTitulo', 'Editar artigo - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'artigos');
    $this->visao->renderizar('/artigo/editar/index');
  }

  public function adicionar(): array
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->redirecionarErro('/dashboard/artigos', MSG_ERRO_PERMISSAO);
    }

    $dados = $this->receberJson();
    $resultado = $this->artigoModel->adicionar($dados);

    $referer = '';
    $botaoVoltar = $this->obterReferer();

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }

    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/artigos', $resultado['erro']);
    }
    elseif ($_POST and isset($resultado['id'])) {
      $condicao[] = [
        'campo' => 'Artigo.id',
        'operador' => '=',
        'valor' => (int) $resultado['id'],
      ];

      $colunas = [
        'Artigo.id',
        'Artigo.codigo',
      ];

      $limite = 1;

      $resultado = $this->artigoModel->selecionar($colunas)
                                     ->condicao($condicao)
                                     ->limite($limite)
                                     ->executarConsulta();

      // Sempre busca o código para redirecionamentos
      $artigoCodigo = $resultado[0]['Artigo']['codigo'] ?? 0;

      if (empty($artigoCodigo)) {
        $this->redirecionarErro('/dashboard/artigos', 'Falha ao buscar artigo');
      }

      Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $dados['categoria_id'], $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $dados['categoria_id'] . '-artigos', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-artigos-categoria-' . $dados['categoria_id'], $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso('/dashboard/artigo/editar/' . $artigoCodigo . $referer, 'Artigo criado com sucesso');
    }
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao[] = ['campo' => 'Artigo.id', 'operador' => '=', 'valor' => (int) $id];
      $condicao[] = ['campo' => 'Artigo.excluido', 'operador' => '=', 'valor' => INATIVO];
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
      'Artigo.codigo',
      'Artigo.titulo',
      'Artigo.editar',
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
    $json = $this->receberJson();

    $condicao[] = ['campo' => 'Artigo.id', 'operador' => '=', 'valor' => (int) $id];
    $condicao[] = ['campo' => 'Artigo.excluido', 'operador' => '=', 'valor' => INATIVO];

    $colunas = [
      'Artigo.id',
      'Artigo.codigo',
    ];

    $limite = 1;

    $resultado = $this->artigoModel->selecionar($colunas)
                                   ->condicao($condicao)
                                   ->limite($limite)
                                   ->executarConsulta();

    // Sempre busca o código para redirecionamentos
    $artigoCodigo = $resultado[0]['Artigo']['codigo'] ?? 0;

    if ($this->requisicaoFetch() and empty($artigoCodigo)) {
      $this->responderJson(['erro' => 'Artigo não encontrado'], 400);
    }
    elseif (empty($artigoCodigo)) {
      $this->redirecionarErro('/dashboard/artigos', 'Artigo não encontrado');
    }

    $referer = '';
    $botaoVoltar = $this->obterReferer();

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }

    if ($this->requisicaoFetch() and $this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }
    elseif ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoCodigo . $referer, MSG_ERRO_PERMISSAO);
    }

    $resultado = $this->artigoModel->atualizar($json, $id);

    if ($this->requisicaoFetch() and isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }
    elseif (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoCodigo . $referer, $resultado['erro']);
    }

    Cache::apagar('publico-artigo_' . $artigoCodigo, $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);

    if (isset($json['categoria_id'])) {
      Cache::apagar('publico-categoria-' . $json['categoria_id'] . '-artigos', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-artigos-categoria-' . $json['categoria_id'], $this->usuarioLogado['empresaId']);
    }

    if ($this->requisicaoFetch()) {
      $this->responderJson($resultado);
    }
    else {
      $this->redirecionarSucesso('/dashboard/artigo/editar/' . $artigoCodigo . $referer, 'Registro alterado com sucesso');
    }
  }

  public function atualizarOrdem()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }

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

  public function atualizarEditar()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }

    $json = $this->receberJson();

    $artigoId = $json['id'] ?? '';
    $artigoEditar = $json['editar'] ?? '';

    $artigoId = $this->filtrarInjection($artigoId);
    $artigoEditar = $this->filtrarInjection($artigoEditar);

    $resultado = $this->artigoModel->atualizarEditar($json);

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
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }

    // Cache
    $condicao = [
      'campo' => 'Artigo.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Artigo.categoria_id',
      'Artigo.codigo',
    ];

    $buscarArtigo = $this->artigoModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->executarConsulta();

    $categoriaId = $buscarArtigo[0]['Artigo']['categoria_id'] ?? 0;
    $artigoCodigo = $buscarArtigo[0]['Artigo']['codigo'] ?? 0;

    $firebase = new DatabaseFirebaseComponent();
    $apagarImagens = $firebase->apagarImagens($this->empresaPadraoId, $id);

    if ($apagarImagens == false) {
      $this->sessaoUsuario->definir('erro', 'Erro ao apagar imagens');
      $this->responderJson(['erro' => 'Erro ao apagar Imagens'], 500);
    }

    $apagarConteudos = $this->artigoModel->apagarConteudos($id, $this->empresaPadraoId);

    if (isset($apagarConteudos['erro'])) {
      $this->sessaoUsuario->definir('erro', 'Erro ao apagar conteúdos');
      $this->responderJson(['erro' => 'Erro ao apagar conteúdos'], 500);
    }

    // Remove artigo sem apagar do banco
    $camposApagar = [
      'ativo' => INATIVO,
      'excluido' => ATIVO
    ];

    $resultado = $this->artigoModel->atualizar($camposApagar, $id);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($categoriaId) {
      Cache::apagar('publico-categorias', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categorias-inicio', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-categoria-' . $categoriaId . '-artigos', $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-artigos-categoria-' . $categoriaId, $this->usuarioLogado['empresaId']);
    }

    Cache::apagar('publico-artigo_' . $artigoCodigo, $this->usuarioLogado['empresaId']);
    $this->sessaoUsuario->definir('ok', 'Artigo excluído com sucesso');

    $this->responderJson($resultado);
  }
}