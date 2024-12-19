<?php
namespace app\Controllers;
use DateTime;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardFeedbackModel;
use app\Controllers\DashboardController;

class DashboardFeedbackController extends DashboardController
{
  protected $artigoModel;
  protected $feedbackModel;
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();
    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->feedbackModel = new DashboardFeedbackModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function adicionarAtualizar(): array
  {
    $json = $this->receberJson();
    $resultado = $this->feedbackModel->adicionarAtualizar($json);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }
}