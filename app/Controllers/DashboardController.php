<?php
namespace app\Controllers;
use app\Models\DashboardModel;
use app\Controllers\ViewRenderer;

class DashboardController extends Controller
{
  protected $dashboardModel;
  protected $visao;

  public function __construct()
  {
    $this->dashboardModel = new DashboardModel();
    $this->visao = new ViewRenderer('/dashboard');
  }

  public function dashboardVer()
  {
    $dados = $this->dashboardModel->dashboardVer();
    
    $this->visao->variavel('titulo', $dados['titulo']);
    $this->visao->variavel('artigos', $dados['dashboard']['artigos']);
    $this->visao->variavel('resumo', $dados['dashboard']['resumo']);
    $this->visao->renderizar('/dashboard/index');
  }
}