<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Core\Helper;
use app\Models\DashboardAjusteModel;
use app\Controllers\Components\DatabaseFirebaseComponent;

class DashboardAjusteController extends DashboardController
{
  protected $ajusteModel;

  public function __construct()
  {
    parent::__construct();

    $this->ajusteModel = new DashboardAjusteModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function ajustesVer()
  {
    $resultado = $this->ajusteModel->buscarTodos();

    $this->visao->variavel('ajustes', $resultado);
    $this->visao->variavel('metaTitulo', 'Ajustes');
    $this->visao->variavel('paginaMenuLateral', 'ajustes');
    $this->visao->renderizar('/ajuste/index');
  }

  public function atualizar()
  {
    $json = $this->receberJson();
    $formFotoMobile = false;
    $formFotoDesktop = false;

    // Atualiza foto de início
    if (isset($_FILES)) {
      $firebase = new DatabaseFirebaseComponent();

      foreach ($_FILES as $chave => $linha):
        $extensao = pathinfo($linha['name'], PATHINFO_EXTENSION);

        if ($chave == 'arquivo-foto-mobile' and $linha['error'] === UPLOAD_ERR_OK) {
          $params = [
            'nome' => 'in-mobile',
            'imagemAtual' => $json['publico_inicio_foto_mobile'] ?? '',
          ];

          if ($firebase->adicionarImagem($this->empresaPadraoId, $linha, $params) == false) {
            $this->redirecionarErro('/dashboard/ajustes', 'Erro ao fazer upload da foto de início (mobile)');
          }

          $formFotoMobile = true;
          $json['publico_inicio_foto_mobile'] = $this->empresaPadraoId . '/' . $params['nome'] . '.' . $extensao;
        }
        elseif ($chave == 'arquivo-foto-desktop' and $linha['error'] === UPLOAD_ERR_OK) {
          $params = [
            'nome' => 'in-desktop',
            'imagemAtual' => $json['publico_inicio_foto_desktop'] ?? '',
          ];

          if ($firebase->adicionarImagem($this->empresaPadraoId, $linha, $params) == false) {
            $this->redirecionarErro('/dashboard/ajustes', 'Erro ao fazer upload da foto de início (desktop)');
          }

          $formFotoDesktop = true;
          $json['publico_inicio_foto_desktop'] = $this->empresaPadraoId . '/' . $params['nome'] . '.' . $extensao;
        }

      endforeach;
    }

    // Grava apenas se alterar no Firebase
    if ($formFotoMobile == false and isset($json['publico_inicio_foto_mobile'])) {
      unset($json['publico_inicio_foto_mobile']);
    }

    if ($formFotoDesktop == false and isset($json['publico_inicio_foto_desktop'])) {
      unset($json['publico_inicio_foto_desktop']);
    }

    $resultado = $this->ajusteModel->atualizarTodos($json);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/ajustes', $resultado['erro']);
    }

    if (isset($resultado['linhasAfetadas']) and $resultado['linhasAfetadas'] == 0 and $formFotoMobile == false and $formFotoDesktop == false) {
      $this->redirecionar('/dashboard/ajustes', 'Nenhuma alteração realizada');
    }

    $this->limparCacheTodos(['ajustes_'], $this->empresaPadraoId);

    $this->redirecionarSucesso('/dashboard/ajustes', 'Ajuste alterado com sucesso');
  }

  public function apagarFoto()
  {
    $foto = '';
    $ajusteNome = '';
    $tipo = $_GET['tipo'] ?? '';

    if ($tipo == 'mobile') {
      $ajusteNome = 'publico_inicio_foto_mobile';
    }

    if ($tipo == 'desktop') {
      $ajusteNome = 'publico_inicio_foto_desktop';
    }

    $foto = Helper::ajuste($ajusteNome);

    if (empty($foto)) {
      $this->sessaoUsuario->definir('erro', 'Imagem não encontrada');
      $this->responderJson(['erro' => 'Imagem não encontrada'], 404);
    }

    $firebase = new DatabaseFirebaseComponent();

    // Apaga Firebase
    if ($firebase->apagarImagem($foto) == false) {
      $this->sessaoUsuario->definir('erro', 'Erro ao apagar imagem');
      $this->responderJson(['erro' => 'Erro ao apagar imagem'], 500);
    }

    // Apaga Banco de dados
    $this->ajusteModel->apagarAjuste(['nome' => $ajusteNome]);

    // Limpa cache de artigos públicos
    $this->limparCacheTodos(['ajustes_'], $this->empresaPadraoId);

    $this->sessaoUsuario->definir('ok', 'Foto removida com sucesso');
    $this->responderJson(['ok' => true]);
  }

  public function buscar(string $nome)
  {
    $cacheNome = 'ajustes_' . $nome;
    $cacheTempo = 60 * 30;
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->ajusteModel->buscar($nome);

      Cache::definir($cacheNome, $resultado, $cacheTempo, $this->empresaPadraoId);
    }

    return $resultado;
  }
}