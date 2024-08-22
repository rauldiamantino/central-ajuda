<?php
namespace app\Controllers;
use app\Models\ConteudoModel;

class DashboardConteudoController extends DashboardController
{
  protected $conteudoModel;

  public function __construct()
  {
    $this->conteudoModel = new ConteudoModel();
  }

  public function adicionar(): array
  {
    $dados = $this->receberJson();
    $resultado = $this->conteudoModel->adicionar($dados);

    $urlRetorno = '/dashboard/artigos';

    if (isset($dados['artigo_id'])) {
      $urlRetorno = '/dashboard/artigo/editar/' . $dados['artigo_id'];
    }

    // Formulário via POST
    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: ' . $urlRetorno);
      exit();
    }
    elseif ($_POST and isset($resultado['id'])) { 
      $_SESSION['ok'] = 'Conteúdo adicionado com sucesso';

      header('Location: ' . $urlRetorno);
      exit();
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

    $urlRetorno = '/dashboard/artigos';
    $artigoId = intval($json['artigo_id'] ?? 0);

    if ($artigoId) {
      $urlRetorno = '/dashboard/artigo/editar/' . $artigoId;
    }

    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: ' . $urlRetorno);
      exit();
    }
    elseif ($_POST and $resultado) {
      $_SESSION['ok'] = 'Conteúdo editado com sucesso';

      header('Location: ' . $urlRetorno);
      exit();
    }
    elseif ($_POST) {
      $_SESSION['neutra'] = 'Nenhuma alteração realizada';

      header('Location: ' . $urlRetorno);
      exit();
    }
  }

  public function atualizarOrdem()
  {
    $json = $this->receberJson();
    $resultado = $this->conteudoModel->atualizarOrdem($json);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'] ?? '';

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id)
  {
    $resultado = $this->conteudoModel->apagar($id);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'];

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $_SESSION['ok'] = 'Conteúdo excluído com sucesso';
    $this->responderJson($resultado);
  }
}