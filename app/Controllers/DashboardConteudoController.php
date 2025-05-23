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
    $artigoId = $dados['artigo_id'] ?? 0;
    $artigoId = $this->filtrarInjection($artigoId);

    // Busca artigo
    $condicao[] = [
      'campo' => 'Artigo.id',
      'operador' => '=',
      'valor' => (int) $artigoId,
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
      $this->redirecionarErro('/dashboard/artigos', 'Artigo não encontrado');
    }

    $urlRetorno = '/dashboard/artigo/editar/' . $artigoCodigo;

    if ($botaoVoltar) {
      $urlRetorno .= '?referer=' . $botaoVoltar;
    }

    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->redirecionarErro($urlRetorno, MSG_ERRO_PERMISSAO);
    }

    if (isset($dados['tipo']) and $dados['tipo'] == 2 and (! isset($_FILES['arquivo-imagem']) or $_FILES['arquivo-imagem']['error'] !== UPLOAD_ERR_OK)) {
      $this->redirecionarErro($urlRetorno, 'Imagem inválida');
    }

    $resultado = $this->conteudoModel->adicionar($dados);

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

    Cache::apagar('publico-artigo_' . $artigoCodigo, $this->usuarioLogado['empresaId']);
    Cache::apagar('publico-artigo_' . $artigoCodigo . '-conteudos', $this->usuarioLogado['empresaId']);

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

    // Busca artigo
    $condicao[] = [
      'campo' => 'Artigo.id',
      'operador' => '=',
      'valor' => (int) $artigoId,
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
      $this->redirecionarErro('/dashboard/artigos', 'Artigo não encontrado');
    }

    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoCodigo . $referer, MSG_ERRO_PERMISSAO);
    }

    $resultado = $this->conteudoModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoCodigo . $referer, $resultado['erro']);
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
          $this->redirecionarErro('/dashboard/artigo/editar/' . $artigoCodigo . $referer, 'Erro ao fazer upload do arquivo');
        }

        // Armazena no banco sempre com a extensão
        $caminhoComExtensao = $this->empresaPadraoId . '/' . $artigoCodigo . '/' . $id . '.' . $extensao;

        $camposConteudo = [
          'url' => $caminhoComExtensao,
        ];

        $this->conteudoModel->atualizar($camposConteudo, $id);
      }

      $camposArtigo = [
        'modificado' => (new DateTime())->format('Y-m-d H:i:s'),
      ];

      $this->artigoModel->atualizar($camposArtigo, $artigoId);

      Cache::apagar('publico-artigo_' . $artigoCodigo . '-conteudos', $this->usuarioLogado['empresaId']);

      $this->redirecionarSucesso('/dashboard/artigo/editar/' . $artigoCodigo . $referer, 'Conteúdo editado com sucesso');
    }

    $this->redirecionar('/dashboard/artigo/editar/' . $artigoCodigo . $referer, 'Nenhuma alteração realizada');
  }

  public function atualizarOrdem()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
      $this->sessaoUsuario->definir('erro', MSG_ERRO_PERMISSAO);
      $this->responderJson(['erro' => false], 403);
    }

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
      $this->limparCacheTodos(['publico-artigo_'], $this->empresaPadraoId);
    }

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
      $this->limparCacheTodos(['publico-artigo_'], $this->empresaPadraoId);
    }

    $this->sessaoUsuario->definir('ok', 'Conteúdo excluído com sucesso');
    $this->responderJson($resultado);
  }
}