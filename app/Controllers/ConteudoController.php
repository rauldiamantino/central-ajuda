<?php
namespace app\Controllers;
use app\Models\ConteudoModel;
use app\Controllers\ViewRenderer;

class ConteudoController extends Controller
{
  protected $middleware;
  protected $conteudoModel;
  protected $visao;

  public function __construct()
  {
    $this->conteudoModel = new ConteudoModel();

    parent::__construct($this->conteudoModel);
  }

  public function adicionar(array $params = []): array
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }

    // Adiciona conteudo
    $resultado = $this->conteudoModel->adicionar($dados);

    // Requisição interna
    if ($params and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif ($params and isset($resultado['id'])) {
      $condicao = [
        'Conteudo.id' => $resultado['id'],
      ];

      $colunas = [
        'Conteudo.id',
        'Conteudo.ativo',
        'Conteudo.artigo_id',
        'Conteudo.tipo',
        'Conteudo.titulo',
        'Conteudo.conteudo',
        'Conteudo.url',
        'Conteudo.ordem',
        'Conteudo.criado',
        'Conteudo.modificado',
      ];

      $conteudo = $this->conteudoModel->condicao($condicao)
                                      ->buscar($colunas);
      
      return reset($conteudo);
    }

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

    // Atualiza conteudo
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

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
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

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->conteudoModel->apagar($id);

    if ($rollback and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'];

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($rollback) {
      return $resultado;
    }

    $_SESSION['ok'] = 'Conteúdo excluído com sucesso';

    $this->responderJson($resultado);
  }
}