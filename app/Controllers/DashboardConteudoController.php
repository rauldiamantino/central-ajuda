<?php
namespace app\Controllers;
use DateTime;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardConteudoModel;
use app\Controllers\DashboardController;
use app\Controllers\Components\DatabaseFirebaseComponent;

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

    if (isset($dados['tipo']) and $dados['tipo'] == 2 and (! isset($_FILES['arquivo-imagem']) or $_FILES['arquivo-imagem']['error'] !== UPLOAD_ERR_OK)) {
      $this->redirecionarErro('/dashboard/artigos', 'Imagem inválida');
    }

    $resultado = $this->conteudoModel->adicionar($dados);

    $urlRetorno = '/dashboard/artigos';

    if (isset($dados['artigo_id'])) {
      $urlRetorno = '/dashboard/artigo/editar/' . $dados['artigo_id'];
    }

    if ($botaoVoltar) {
      $urlRetorno .= '?referer=' . $botaoVoltar;
    }

    if (! isset($resultado['id']) or empty($resultado['id'])) {
      $this->redirecionarErro($urlRetorno, $resultado['erro'] ?? '');
    }

    $paramsImagem = [
      'artigoId' => $dados['artigo_id'],
      'conteudoId' => $resultado['id'],
    ];

    if ($dados['tipo'] == 2) {
      $firebase = new DatabaseFirebaseComponent();
      $extensao = pathinfo($_FILES['arquivo-imagem']['name'], PATHINFO_EXTENSION);

      if ($firebase->adicionarImagem($this->empresaPadraoId, $_FILES['arquivo-imagem'], $paramsImagem) == false) {
        $this->redirecionarErro($urlRetorno, 'Erro ao fazer upload do arquivo');
      }

      // Armazena no banco sempre com a extensão
      $caminhoComExtensao = $this->empresaPadraoId . '/' . $dados['artigo_id'] . '/' . $resultado['id'] . '.' . $extensao;

      $camposConteudo = [
        'url' => $caminhoComExtensao,
      ];

      $this->conteudoModel->atualizar($camposConteudo, $resultado['id']);
    }


    $camposArtigo = [
      'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
    ];

    $this->artigoModel->atualizar($camposArtigo, $dados['artigo_id']);

    Cache::apagar('publico-artigo_' . $dados['artigo_id'], $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-artigo_' . $dados['artigo_id'] . '-conteudos', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso($urlRetorno, 'Conteúdo adicionado com sucesso');
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

    $referer = '';

    if ($botaoVoltar) {
      $referer = '?referer=' . urlencode($botaoVoltar);
    }


    $id = (int) $id;
    $json = $this->receberJson();
    $artigoId = intval($json['artigo_id'] ?? 0);

    $resultado = $this->conteudoModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoId . $referer, $resultado['erro']);
    }
    elseif ($resultado) {
      $paramsImagem = [
        'artigoId' => $artigoId,
        'conteudoId' => $id,
        'imagemAtual' => $json['url'] ?? '',
      ];

      if (isset($_FILES['arquivo-imagem']) and $_FILES['arquivo-imagem']['error'] === UPLOAD_ERR_OK) {
        $firebase = new DatabaseFirebaseComponent();
        $extensao = pathinfo($_FILES['arquivo-imagem']['name'], PATHINFO_EXTENSION);

        if ($firebase->adicionarImagem($this->empresaPadraoId, $_FILES['arquivo-imagem'], $paramsImagem) == false) {
          $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoId . $referer, 'Erro ao fazer upload do arquivo');
        }

        // Armazena no banco sempre com a extensão
        $caminhoComExtensao = $this->empresaPadraoId . '/' . $artigoId . '/' . $id . '.' . $extensao;

        $camposConteudo = [
          'url' => $caminhoComExtensao,
        ];

        $this->conteudoModel->atualizar($camposConteudo, $id);
      }

      $camposArtigo = [
        'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
      ];

      $this->artigoModel->atualizar($camposArtigo, $artigoId);

      Cache::apagar('publico-artigo_' . $artigoId . '-conteudos', $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso('/dashboard/artigo/editar/' . $artigoId . $referer, 'Conteúdo editado com sucesso');
    }

    $this->redirecionar('/dashboard/artigo/editar/' . $artigoId . $referer, 'Nenhuma alteração realizada');
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

      Cache::apagar('publico-artigo_' . $artigoId . '-conteudos', $this->usuarioLogado['empresaId']);
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
      'Conteudo.tipo',
      'Conteudo.url',
    ];

    $buscarConteudo = $this->conteudoModel->selecionar($colunas)
                                          ->condicao($condicao)
                                          ->executarConsulta();

    $artigoId = $buscarConteudo[0]['Conteudo']['artigo_id'] ?? 0;

    // Apaga imagem
    if (isset($buscarConteudo[0]['Conteudo']['tipo']) and $buscarConteudo[0]['Conteudo']['tipo'] == 2 and isset($buscarConteudo[0]['Conteudo']['url'])) {
      $firebase = new DatabaseFirebaseComponent();
      if ($firebase->apagarImagem($buscarConteudo[0]['Conteudo']['url']) == false) {
        $this->sessaoUsuario->definir('erro', 'Erro ao apagar imagem');
        $this->responderJson(['erro' => 'Erro ao apagar imagem'], 500);
      }
    }

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

      Cache::apagar('publico-artigo_' . $artigoId . '-conteudos', $this->usuarioLogado['empresaId']);
    }

    $this->sessaoUsuario->definir('ok', 'Conteúdo excluído com sucesso');
    $this->responderJson($resultado);
  }
}