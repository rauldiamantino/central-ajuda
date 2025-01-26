<?php
namespace app\Controllers;
use app\Models\DashboardRelatorioModel;

class DashboardRelatorioController extends DashboardController
{
  private $dashboardRelatorioModel;

  public function __construct()
  {
    parent::__construct();

    $this->dashboardRelatorioModel = new DashboardRelatorioModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function relatoriosVer()
  {
    $botaoVoltar = $this->obterReferer();

    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('relInicio', true);
    $this->visao->variavel('paginaMenuLateral', 'relatorios');
    $this->visao->variavel('subtitulo', 'Acompanhe os resultados e tome decisões com base em dados detalhados e organizados!');
    $this->visao->variavel('metaTitulo', 'Relatórios');
    $this->visao->renderizar('/relatorio/index');
  }

  public function feedbacks()
  {
    $botaoVoltar = $this->obterReferer();
    $resultado = $this->dashboardRelatorioModel->buscarFeedbacks();

    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('relFeedbacks', $resultado);
    $this->visao->variavel('paginaMenuLateral', 'relatorios');
    $this->visao->variavel('subtitulo', 'Feedbacks de artigos publicados');
    $this->visao->variavel('metaTitulo', 'Relatórios - Feedbacks de artigos publicados');
    $this->visao->renderizar('/relatorio/index');
  }
}