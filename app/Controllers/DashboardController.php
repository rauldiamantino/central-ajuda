<?php
namespace app\Controllers;
use app\Models\DashboardModel;
use app\Controllers\Controller;
use app\Controllers\ViewRenderer;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardUsuarioModel;
use app\Models\DashboardCategoriaModel;

class DashboardController extends Controller
{
  protected $artigoModel;
  protected $usuarioModel;
  protected $categoriaModel;
  protected $dashboardModel;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->usuarioModel = new DashboardUsuarioModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->dashboardModel = new DashboardModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->visao = new ViewRenderer('/dashboard');
  }
}