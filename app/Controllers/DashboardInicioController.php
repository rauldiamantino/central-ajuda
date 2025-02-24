<?php
namespace app\Controllers;
use app\Models\DashboardInicioModel;
use app\Models\DashboardFeedbackModel;
use app\Models\DashboardVisualizacaoModel;

class DashboardInicioController extends DashboardController
{
  private $dashboardInicioModel;
  private $visualizacaoModel;
  private $feedbackModel;

  public function __construct()
  {
    parent::__construct();

    $this->dashboardInicioModel = new DashboardInicioModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->visualizacaoModel = new DashboardVisualizacaoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->feedbackModel = new DashboardFeedbackModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function dashboardVer()
  {
    $artigosPopulares = $this->dashboardInicioModel->buscarFeedbacks(true);
    $artigosVisualizados = $this->dashboardInicioModel->buscarVisualizacoes(true);
    $totalUsuarios = $this->contarUsuarios();
    $totalArtigos = $this->contarArtigos();
    $totalCategorias = $this->contarCategorias();
    $totalVisualizacoes = $this->contarVisualizacoes();
    $totalFeedbacksGostou = $this->contarFeedbacksGostou();
    $totalFeedbacksNaoGostou = $this->contarFeedbacksNaoGostou();

    $this->visao->variavel('artigosPopulares', $artigosPopulares);
    $this->visao->variavel('artigosVisualizados', $artigosVisualizados);
    $this->visao->variavel('totalUsuarios', $totalUsuarios);
    $this->visao->variavel('totalArtigos', $totalArtigos);
    $this->visao->variavel('totalCategorias', $totalCategorias);
    $this->visao->variavel('totalVisualizacoes', $totalVisualizacoes);
    $this->visao->variavel('totalFeedbacksGostou', $totalFeedbacksGostou);
    $this->visao->variavel('totalFeedbacksNaoGostou', $totalFeedbacksNaoGostou);
    $this->visao->variavel('metaTitulo', 'Início - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'dashboard');
    $this->visao->renderizar('/dashboard/index');
  }

  private function contarUsuarios()
  {
    $condicoes = [
      'campo' => 'Usuario.padrao',
      'operador' => '!=',
      'valor' => USUARIO_SUPORTE,
    ];

    if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      $condicoes = [];
    }

    $resultado = $this->usuarioModel->contar('Usuario.id')
                                    ->condicao($condicoes)
                                    ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarArtigos()
  {
    $condicoes = [
      'campo' => 'Artigo.excluido',
      'operador' => '=',
      'valor' => INATIVO,
    ];

    $resultado = $this->artigoModel->contar('Artigo.id')
                                   ->condicao($condicoes)
                                   ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarCategorias()
  {
    $resultado = $this->categoriaModel->contar('Categoria.id')
                                      ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarVisualizacoes()
  {
    $resultado = $this->visualizacaoModel->contar('Visualizacao.id')
                                         ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarFeedbacksGostou()
  {
    $condicoes = [
      'campo' => 'Feedback.util',
      'operador' => '=',
      'valor' => 1,
    ];

    $resultado = $this->feedbackModel->contar('Feedback.id')
                                     ->condicao($condicoes)
                                     ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarFeedbacksNaoGostou()
  {
    $condicoes = [
      'campo' => 'Feedback.util',
      'operador' => '=',
      'valor' => 0,
    ];

    $resultado = $this->feedbackModel->contar('Feedback.id')
                                     ->condicao($condicoes)
                                     ->executarConsulta();

    return $resultado['total'] ?? 0;
  }
}