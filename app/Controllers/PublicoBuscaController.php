<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardCategoriaModel;
use app\Models\PublicoBuscaModel;

class PublicoBuscaController extends PublicoController
{
  protected $visao;
  protected $categoriaModel;
  protected $subdominio;
  protected $empresaId;
  protected $publicoBuscamodel;

  public function __construct()
  {
    parent::__construct();

    $this->publicoBuscamodel = new PublicoBuscaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function buscar()
  {
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $artigosTotal = 0;
    $resultadoBuscar = [];

    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 1);
    $textoBusca = $_GET['texto_busca'] ?? '';
    $textoBusca = $this->filtrarInjection($textoBusca);

    if (mb_strlen($textoBusca) > 2) {
      $cacheNome = 'publico-busca-resultado-buscar-' . md5(serialize($textoBusca . $pagina));
      $resultadoCache = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultadoCache == null) {
        $resultadoCache = $this->publicoBuscamodel->buscarArtigos($textoBusca, $pagina, $limite);
        Cache::definir($cacheNome, $resultadoCache, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultadoCache['resultado'][0]['Artigo']['id'])) {
        $resultadoBuscar = $resultadoCache['resultado'];
        $artigosTotal = intval($resultadoCache['artigosTotal'][0]['FOUND_ROWS()'] ?? 0);
      }

      $paginasTotal = ceil($artigosTotal / $limite);
      $intervaloInicio = ($pagina - 1) * $limite + 1;
      $intervaloFim = min($pagina * $limite, $artigosTotal);
    }

    $categorias = [];

    if ((int) $this->buscarAjuste('publico_cate_busca') == 1) {
      $condicoes[] = [
        'campo' => 'Categoria.ativo', 'operador' => '=', 'valor' => ATIVO,
      ];

      $colunas = [
        'Categoria.id',
        'Categoria.nome',
        'Categoria.ativo',
      ];

      $existe = [
        'tabela' => 'Artigo',
        'params' => [
          ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => 'Categoria.id'],
          ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => (int) ATIVO],
        ],
      ];

      $ordem = [
        'Categoria.ordem' => 'ASC',
      ];

      $cacheNome = 'publico-busca-categorias-' . md5(serialize($condicoes));
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->categoriaModel->selecionar($colunas)
                                          ->condicao($condicoes)
                                          ->existe($existe)
                                          ->ordem($ordem)
                                          ->executarConsulta();

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Categoria']['id'])) {
        $categorias = $resultado;
      }
    }

    $menuLateral = false;

    if ((int) $this->buscarAjuste('publico_cate_busca') == ATIVO) {
      $menuLateral = true;
    }

    $urlCanonica = 'https://' . $this->subdominio . '.360help.com.br';

    if ($this->subdominio_2) {
      $urlCanonica = $this->subdominio_2;
    }

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('textoBusca', htmlspecialchars($textoBusca));
    $this->visao->variavel('resultadoBuscar', $resultadoBuscar);
    $this->visao->variavel('metaTitulo', 'Busca');
    $this->visao->variavel('metaDescricao', '');
    $this->visao->variavel('urlCanonica', $urlCanonica . '/buscar');
    $this->visao->variavel('menuLateral', $menuLateral);
    $this->visao->renderizar('/busca/index');
  }
}