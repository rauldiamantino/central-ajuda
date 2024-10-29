<?php
namespace app\Controllers;
use app\Models\DashboardConteudoModel;

class DashboardConteudoController extends DashboardController
{
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();
    $this->conteudoModel = new DashboardConteudoModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function conteudoEditarVer(int $id)
  {
    $condicao = [
      'campo' => 'Conteudo.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Conteudo.id',
      'Conteudo.ativo',
      'Conteudo.artigo_id',
      'Conteudo.tipo',
      'Conteudo.titulo',
      'Conteudo.titulo_ocultar',
      'Conteudo.conteudo',
      'Conteudo.empresa_id',
      'Conteudo.url',
    ];

    $resultado = $this->conteudoModel->selecionar($colunas)
                                     ->condicao($condicao)
                                     ->executarConsulta();

    $this->visao->variavel('loader', true);
    $this->visao->variavel('conteudo', reset($resultado));
    $this->visao->variavel('titulo', 'Editar conteúdo');
    $this->visao->variavel('paginaMenuLateral', 'artigos');
    $this->visao->renderizar('/artigo/editar/conteudo/editar/index');
  }

  public function conteudoAdicionarVer()
  {
    $empresaId = 0;

    $ordemNum = [
      'prox' => 1,
    ];

    $artigoId = $_GET['artigo_id'] ?? 0;
    $artigoId = (int) $artigoId;

    $tipo = $_GET['tipo'] ?? 0;
    $tipo = (int) $tipo;

    if ($artigoId) {
      $condicao = [
        'campo' => 'Conteudo.artigo_id',
        'operador' => '=',
        'valor' => $artigoId,
      ];

      $colunas = [
        'Conteudo.id',
        'Conteudo.ordem',
        'Conteudo.empresa_id',
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

      $empresaId = intval($resultadoOrdem[0]['Conteudo']['empresa_id'] ?? 0);
      $ordemAtual = intval($resultadoOrdem[0]['Conteudo']['ordem'] ?? 0);

      if ($resultadoOrdem) {
        $ordemNum['prox'] = $ordemAtual + 1;
      }
    }

    $this->visao->variavel('loader', true);
    $this->visao->variavel('artigoId', $artigoId);
    $this->visao->variavel('empresaId', $empresaId);
    $this->visao->variavel('tipo', $tipo);
    $this->visao->variavel('ordem', $ordemNum);
    $this->visao->variavel('titulo', 'Adicionar conteúdo');
    $this->visao->variavel('paginaMenuLateral', 'artigos');
    $this->visao->renderizar('/artigo/editar/conteudo/adicionar/index');
  }

  public function adicionar(): array
  {
    $dados = $this->receberJson();
    $resultado = $this->conteudoModel->adicionar($dados);

    $urlRetorno = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos';

    if (isset($dados['artigo_id'])) {
      $urlRetorno = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $dados['artigo_id'];
    }

    // Formulário via POST
    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro($urlRetorno, $resultado['erro']);
    }
    elseif ($_POST and isset($resultado['id'])) {
      $nomesCache = ['publico-artigo_artigo_', 'publico-artigo_conteudos_'];
      $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso($urlRetorno, 'Conteúdo adicionado com sucesso');
    }

    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $nomesCache = ['publico-artigo_artigo_', 'publico-artigo_conteudos_'];
    $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

    $this->responderJson($resultado);
  }

  public function buscar(int $artigoId)
  {
    $condicao = [
      'campo' => 'Conteudo.artigo_id',
      'operador' => '=',
      'valor' => $artigoId,
    ];

    $colunas = [
      'Conteudo.id',
      'Conteudo.titulo',
    ];

    $ordem = [
      'Conteudo.ordem' => 'ASC',
    ];

    $limite = 100;

    $resultado = $this->conteudoModel->selecionar($colunas)
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
    $id = (int) $id;
    $json = $this->receberJson();
    $resultado = $this->conteudoModel->atualizar($json, $id);

    $artigoId = intval($json['artigo_id'] ?? 0);

    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $id, $resultado['erro']);
    }
    elseif ($_POST and $resultado) {
      $nomesCache = ['publico-artigo_artigo_', 'publico-artigo_conteudos_'];
      $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId, 'Conteúdo editado com sucesso');
    }
    elseif ($_POST) {
      $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId, 'Nenhuma alteração realizada');
    }
  }

  public function atualizarOrdem()
  {
    $json = $this->receberJson();
    $resultado = $this->conteudoModel->atualizarOrdem($json);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $nomesCache = ['publico-artigo_artigo_', 'publico-artigo_conteudos_'];
    $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

    $this->responderJson($resultado);
  }

  public function apagar(int $id)
  {
    $resultado = $this->conteudoModel->apagar($id);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $nomesCache = ['publico-artigo_artigo_', 'publico-artigo_conteudos_'];
    $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

    $this->sessaoUsuario->definir('ok', 'Conteúdo excluído com sucesso');
    $this->responderJson($resultado);
  }
}