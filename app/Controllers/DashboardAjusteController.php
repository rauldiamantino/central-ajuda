<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardAjusteModel;

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

    $resultado = $this->ajusteModel->atualizarAjustes($json);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/ajustes', $resultado['erro']);
    }

    Cache::apagar('ajustes', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/dashboard/ajustes', 'Ajuste alterado com sucesso');
  }
}