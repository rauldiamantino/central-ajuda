<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardConteudoModel;
use DateTime;

class DashboardConteudoController extends DashboardController
{
  protected $artigoModel;
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();
    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->conteudoModel = new DashboardConteudoModel($this->usuarioLogado, $this->empresaPadraoId);
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
    $resultado = $this->conteudoModel->adicionar($dados);

    $urlRetorno = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos';

    if (isset($dados['artigo_id'])) {
      $urlRetorno = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $dados['artigo_id'];
    }

    if ($botaoVoltar) {
      $urlRetorno .= '?referer=' . $botaoVoltar;
    }

    // Formulário via POST
    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro($urlRetorno, $resultado['erro']);
    }
    elseif ($_POST and isset($resultado['id'])) {
      $camposArtigo = [
        'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
      ];

      $this->artigoModel->atualizar($camposArtigo, $dados['artigo_id']);

      Cache::apagar('publico-artigo-' . $dados['artigo_id'], $this->usuarioLogado['empresaId']);
      Cache::apagar('publico-artigo-' . $dados['artigo_id'] . '-conteudos', $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso($urlRetorno, 'Conteúdo adicionado com sucesso');
    }

    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $camposArtigo = [
      'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
    ];

    $this->artigoModel->atualizar($camposArtigo, $dados['artigo_id']);

    Cache::apagar('publico-artigo-' . $dados['artigo_id'], $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-artigo-' . $dados['artigo_id'] . '-conteudos', $this->usuarioLogado['empresaId']);

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
    $botaoVoltar = $_GET['referer'] ?? '';
    $botaoVoltar = htmlspecialchars($botaoVoltar);
    $botaoVoltar = urldecode($botaoVoltar);

    if (isset($_POST['referer']) and $_POST['referer'] and ! is_array($_POST['referer'])) {
      $botaoVoltar = $_POST['referer'];
      $botaoVoltar = htmlspecialchars($botaoVoltar);
    }

    $id = (int) $id;
    $json = $this->receberJson();
    $resultado = $this->conteudoModel->atualizar($json, $id);
    $artigoId = intval($json['artigo_id'] ?? 0);

    $referer = '';

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }

    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId . $referer, $resultado['erro']);
    }
    elseif ($_POST and $resultado) {
      $camposArtigo = [
        'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
      ];

      $this->artigoModel->atualizar($camposArtigo, $artigoId);

      Cache::apagar('publico-artigo-' . $artigoId . '-conteudos', $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId . $referer, 'Conteúdo editado com sucesso');
    }
    elseif ($_POST) {
      $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId . $referer, 'Nenhuma alteração realizada');
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

    // Cache
    $condicao = [
      'campo' => 'Conteudo.id',
      'operador' => '=',
      'valor' => $json[0]['id'],
    ];

    $colunas = [
      'Conteudo.artigo_id',
    ];

    $buscarConteudo = $this->conteudoModel->selecionar($colunas)
                                          ->condicao($condicao)
                                          ->executarConsulta();

    $artigoId = $buscarConteudo[0]['Conteudo']['artigo_id'] ?? 0;

    if ($artigoId) {
      $camposArtigo = [
        'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
      ];

      $this->artigoModel->atualizar($camposArtigo, $artigoId);

      Cache::apagar('publico-artigo-' . $artigoId . '-conteudos', $this->usuarioLogado['empresaId']);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id)
  {
    // Cache
    $condicao = [
      'campo' => 'Conteudo.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Conteudo.artigo_id',
    ];

    $buscarConteudo = $this->conteudoModel->selecionar($colunas)
                                          ->condicao($condicao)
                                          ->executarConsulta();

    $artigoId = $buscarConteudo[0]['Conteudo']['artigo_id'] ?? 0;

    // Apagar
    $resultado = $this->conteudoModel->apagar($id);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($artigoId) {
      $camposArtigo = [
        'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
      ];

      $this->artigoModel->atualizar($camposArtigo, $artigoId);

      Cache::apagar('publico-artigo-' . $artigoId . '-conteudos', $this->usuarioLogado['empresaId']);
    }

    $this->sessaoUsuario->definir('ok', 'Conteúdo excluído com sucesso');
    $this->responderJson($resultado);
  }
}