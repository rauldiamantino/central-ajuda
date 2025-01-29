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

    $artigoTitulo = urldecode($_GET['titulo'] ?? '');
    $artigoTitulo = $this->filtrarInjection($artigoTitulo);

    $artigoStatus = $_GET['status'] ?? '';
    $artigoStatus = $this->filtrarInjection($artigoStatus);

    $categoriaId = $_GET['categoria_id'] ?? '';
    $categoriaId = $this->filtrarInjection($categoriaId);

    $categoriaNome = urldecode($_GET['categoria_nome'] ?? '');
    $categoriaNome = $this->filtrarInjection($categoriaNome);

    // Filtrar por categoria
    if (isset($_GET['categoria_id'])) {
      $filtroAtual['categoria_id'] = $categoriaId;

      if (intval($categoriaId) > 0) {
        $condicoes[] = ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => (int) $categoriaId];
      }
      elseif ($categoriaId === '0') {
        $condicoes[] = ['campo' => 'Artigo.categoria_id', 'operador' => 'IS', 'valor' => NULL];
      }
    }

    if (isset($_GET['categoria_nome'])) {
      $filtroAtual['categoria_nome'] = $categoriaNome;
    }

    // Filtrar por Código
    if (isset($_GET['codigo'])) {
      $filtroAtual['codigo'] = $artigoCodigo;
      $condicoes[] = ['campo' => 'Artigo.codigo', 'operador' => '=', 'valor' => (int) $artigoCodigo];
    }

    // Filtrar por status
    if (isset($_GET['status'])) {
      $filtroAtual['status'] = $artigoStatus;
      $condicoes[] = ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => (int) $artigoStatus];
    }

    // Filtrar por título
    if (isset($_GET['titulo'])) {
      $filtroAtual['titulo'] = $artigoTitulo;
      $condicoes[] = ['campo' => 'Artigo.titulo', 'operador' => 'LIKE', 'valor' => '%' . $artigoTitulo . '%'];
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

    $this->visao->variavel('tipo', 'visualizacoes-1');
    $this->visao->variavel('botaoVoltar', $botaoVoltar);
    $this->visao->variavel('relVisualizacoes', $resultado);
    $this->visao->variavel('paginaMenuLateral', 'relatorios');
    $this->visao->variavel('subtitulo', 'Visualizações de artigos publicados');
    $this->visao->variavel('metaTitulo', 'Relatórios - Visualizações de artigos publicados');
    $this->visao->renderizar('/relatorio/index');
  }
}