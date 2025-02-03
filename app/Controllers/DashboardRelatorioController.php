<?php
namespace app\Controllers;
use DateTime;
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

    $this->visao->variavel('tipo', '');
    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('relInicio', true);
    $this->visao->variavel('paginaMenuLateral', 'relatorios');
    $this->visao->variavel('subtitulo', 'Acompanhe os resultados e tome decisões com base em dados detalhados e organizados!');
    $this->visao->variavel('metaTitulo', 'Relatórios');
    $this->visao->renderizar('/relatorio/index');
  }

  public function feedbacks()
  {
    // Filtros
    $condicoes = [];
    $filtroAtual = [];

    $artigoCodigo = $_GET['codigo'] ?? '';
    $artigoCodigo = $this->filtrarInjection($artigoCodigo);

    $dataInicio = $_GET['data_inicio'] ?? '';
    $dataInicio = $this->filtrarInjection($dataInicio);

    $dataFim = $_GET['data_fim'] ?? '';
    $dataFim = $this->filtrarInjection($dataFim);

    $categoriaId = $_GET['categoria_id'] ?? '';
    $categoriaId = $this->filtrarInjection($categoriaId);

    $categoriaNome = $_GET['categoria_nome'] ?? '';
    $categoriaNome = $this->filtrarInjection($categoriaNome);

    // Filtrar por Código
    if (isset($_GET['codigo'])) {
      $filtroAtual['codigo'] = $artigoCodigo;
      $condicoes[] = ['campo' => 'Artigo.codigo', 'operador' => '=', 'valor' => (int) $artigoCodigo];
    }

    // Filtrar por Data início
    if (isset($_GET['data_inicio']) and ! isset($_GET['data_fim'])) {
      $filtroAtual['data_inicio'] = $dataInicio;

      // Sempre depois do filtro
      if ($dataInicio) {
        $dataInicio .= ' 23:59:59';
      }

      $condicoes[] = ['campo' => 'Feedback.criado', 'operador' => 'BETWEEN', 'valor' => [(new DateTime($dataInicio))->format('Y-m-d H:i:s'), (new DateTime('now'))->format('Y-m-d H:i:s')]];
    }

    // Filtrar por Data fim
    if (isset($_GET['data_fim']) and ! isset($_GET['data_inicio'])) {
      $filtroAtual['data_fim'] = $dataFim;

      // Sempre depois do filtro
      if ($dataFim) {
        $dataFim .= ' 23:59:59';
      }

      $condicoes[] = ['campo' => 'Feedback.criado', 'operador' => '<=', 'valor' => (new DateTime($dataFim))->format('Y-m-d H:i:s')];
    }

    // Filtrar por Data início e Data fim
    if (isset($_GET['data_inicio']) and isset($_GET['data_fim'])) {
      $filtroAtual['data_inicio'] = $dataInicio;
      $filtroAtual['data_fim'] = $dataFim;

      // Sempre depois do filtro
      if ($dataInicio) {
        $dataInicio .= ' 23:59:59';
      }

      if ($dataFim) {
        $dataFim .= ' 23:59:59';
      }

      $condicoes[] = ['campo' => 'Feedback.criado', 'operador' => 'BETWEEN', 'valor' => [(new DateTime($dataInicio))->format('Y-m-d H:i:s'), (new DateTime($dataFim))->format('Y-m-d H:i:s')]];
    }

    // Filtrar por categoria
    if (isset($_GET['categoria_id'])) {
      $filtroAtual['categoria_id'] = $categoriaId;
      $filtroAtual['categoria_nome'] = $categoriaNome;

      if (intval($categoriaId) > 0) {
        $condicoes[] = ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => (int) $categoriaId];
      }
      elseif ($categoriaId === '0') {
        $condicoes[] = ['campo' => 'Artigo.categoria_id', 'operador' => 'IS', 'valor' => NULL];
      }
    }

    $botaoVoltar = $this->obterReferer();
    $resultado = $this->dashboardRelatorioModel->buscarFeedbacks($condicoes);

    $this->visao->variavel('tipo', 'feedbacks-1');
    $this->visao->variavel('filtroAtual', $filtroAtual);
    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('relFeedbacks', $resultado);
    $this->visao->variavel('paginaMenuLateral', 'relatorios');
    $this->visao->variavel('subtitulo', 'Feedbacks de artigos publicados');
    $this->visao->variavel('metaTitulo', 'Relatórios - Feedbacks de artigos publicados');
    $this->visao->renderizar('/relatorio/index');
  }

  public function visualizacoes()
  {
    $botaoVoltar = $this->obterReferer();
    $resultado = $this->dashboardRelatorioModel->buscarVisualizacoes();

    $this->visao->variavel('tipo', 'visualizacoes-2');
    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('relVisualizacoes', $resultado);
    $this->visao->variavel('paginaMenuLateral', 'relatorios');
    $this->visao->variavel('subtitulo', 'Visualizações de artigos publicados');
    $this->visao->variavel('metaTitulo', 'Relatórios - Visualizações de artigos publicados');
    $this->visao->renderizar('/relatorio/index');
  }
}