<?php
namespace app\Controllers;
use app\Models\DashboardModel;
use app\Controllers\ViewRenderer;

class DashboardController extends Controller
{
  protected $middleware;
  protected $dashboardModel;
  protected $visao;

  public function __construct()
  {
    $this->dashboardModel = new DashboardModel();
    $this->visao = new ViewRenderer('/dashboard');

    parent::__construct($this->dashboardModel);
  }

  public function dashboardVer()
  {
    $dados = $this->dashboardModel->dashboardVer();

    $this->visao->variavel('titulo', $dados['titulo']);
    $this->visao->variavel('artigos', $dados['dashboard']['artigos']);
    $this->visao->variavel('resumo', $dados['dashboard']['resumo']);
    $this->visao->renderizar('/index');
  }
}