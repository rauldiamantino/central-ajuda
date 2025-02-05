<?php
namespace app\Controllers;
use app\Core\Cache;
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
    $resultado = $this->ajusteModel->buscarAjustes();

    $this->visao->variavel('ajustes', $resultado);
    $this->visao->variavel('metaTitulo', 'Ajustes');
    $this->visao->variavel('paginaMenuLateral', 'ajustes');
    $this->visao->renderizar('/ajuste/index');
  }

  public function atualizar()
  {
    $json = $this->receberJson();

    // Atualiza foto de início
    if (isset($_FILES['arquivo-foto']) and $_FILES['arquivo-foto']['error'] === UPLOAD_ERR_OK) {
      $firebase = new DatabaseFirebaseComponent();
      $extensao = pathinfo($_FILES['arquivo-foto']['name'], PATHINFO_EXTENSION);

      $params = [
        'nome' => 'in',
        'imagemAtual' => $json['publico_inicio_foto'] ?? '',
      ];

      if ($firebase->adicionarImagem($this->empresaPadraoId, $_FILES['arquivo-foto'], $params) == false) {
        $this->redirecionarErro('/dashboard/ajustes', 'Erro ao fazer upload da foto de início');
      }

      $json['publico_inicio_foto'] = $this->empresaPadraoId . '/' . $params['nome'] . '.' . $extensao;
    }
    else {
      unset($json['publico_inicio_foto']);
    }

    $resultado = $this->ajusteModel->atualizarAjustes($json);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/ajustes', $resultado['erro']);
    }

    Cache::apagar('ajustes', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/dashboard/ajustes', 'Ajuste alterado com sucesso');
  }

  public function apagarFoto()
  {
    $foto = $this->buscarAjuste('publico_inicio_foto');

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
    $this->ajusteModel->atualizarAjustes(['publico_inicio_foto' => '']);

    // Limpa cache de artigos públicos
    Cache::apagar('ajustes', $this->usuarioLogado['empresaId']);

    $this->sessaoUsuario->definir('ok', 'Foto removida com sucesso');
    $this->responderJson(['ok' => true]);
  }

  public function buscarAjuste(string $nome)
  {
    $resultado = $this->ajusteModel->buscarAjustes($nome);

    if (empty($resultado)) {
      return '';
    }

    foreach ($resultado as $linha):

      if (! isset($linha['Ajuste']['nome'])) {
        continue;
      }

      if (! isset($linha['Ajuste']['valor'])) {
        continue;
      }

      if ($linha['Ajuste']['nome'] != $nome) {
        continue;
      }

      return $linha['Ajuste']['valor'];
    endforeach;

    return '';
  }
}