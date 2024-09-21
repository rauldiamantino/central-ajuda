<?php
namespace app\Controllers;
use app\Models\DashboardConteudoModel;

class DashboardConteudoController extends DashboardController
{
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();
    $this->conteudoModel = new DashboardConteudoModel();
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
      $this->redirecionarSucesso($urlRetorno, 'Conteúdo adicionado com sucesso');
    }

    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function buscar(int $artigoId)
  {
    $condicao = [
      'Conteudo.artigo_id' => $artigoId,
    ];

    $colunas = [
      'Conteudo.id',
      'Conteudo.titulo',
    ];

    $limite = 100;

    $resultado = $this->conteudoModel->condicao($condicao)
                                     ->ordem(['Conteudo.ordem' => 'ASC'])
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
    $resultado = $this->conteudoModel->atualizar($json, $id);

    $urlRetorno = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos';
    $artigoId = intval($json['artigo_id'] ?? 0);

    if ($artigoId) {
      $urlRetorno = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId;
    }

    if ($_POST and isset($resultado['erro'])) {
      $this->redirecionarErro($urlRetorno, $resultado['erro']);
    }
    elseif ($_POST and $resultado) {
      $this->redirecionarSucesso($urlRetorno, 'Conteúdo editado com sucesso');
    }
    elseif ($_POST) {
      $this->redirecionar($urlRetorno, 'Nenhuma alteração realizada');
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

    $this->sessaoUsuario->definir('ok', 'Conteúdo excluído com sucesso');
    $this->responderJson($resultado);
  }
}