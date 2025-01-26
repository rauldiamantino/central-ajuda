<?php
namespace app\Controllers;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardVisualizacaoModel;
use app\Controllers\DashboardController;

class DashboardVisualizacaoController extends DashboardController
{
  protected $artigoModel;
  protected $visualizacaoModel;
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();
    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->visualizacaoModel = new DashboardVisualizacaoModel($this->usuarioLogado, $this->empresaPadraoId);
  }
}