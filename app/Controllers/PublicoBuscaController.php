<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardCategoriaModel;

class PublicoBuscaController extends PublicoController
{
  protected $visao;
  protected $artigoModel;
  protected $categoriaModel;
  protected $subdominio;
  protected $empresaId;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function buscar()
  {
    $limite = 10;
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $resultadoBuscar = [];

    $pagina = intval($_GET['pagina'] ?? 0);
    $textoBusca = htmlspecialchars($_GET['texto_busca'] ?? '');

    $condicao[] = [
      'campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => ATIVO,
    ];

    if ($this->exibirInativos()) {
      unset($condicao[0]);
    }

    if ($textoBusca) {
      $condicao[] = [
        'campo' => 'Artigo.titulo', 'operador' => 'LIKE', 'valor' => '%' . $textoBusca . '%',
      ];
    }

    $cacheNome = 'publico-busca-artigos-total-' . md5(serialize($condicao));
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->artigoModel->contar('Artigo.id')
                                     ->condicao($condicao)
                                     ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    $artigosTotal = intval($resultado['total'] ?? 0);

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);

      $pagina = abs($pagina);
      $pagina = max($pagina, 1);
      $pagina = min($pagina, $paginasTotal);

      $colunas = [
        'Artigo.id',
        'Artigo.titulo',
        'Artigo.ativo',
        'Artigo.categoria_id',
        'Categoria.nome',
        'Categoria.ativo',
      ];

      $ordem = [
        'Artigo.modificado' => 'DESC',
        'Categoria.nome' => 'DESC',
        'Artigo.criado' => 'DESC',
        'Artigo.ordem' => 'ASC',
      ];

      $juntar = [
        'tabelaJoin' => 'Categoria',
        'campoA' => 'Categoria.id',
        'campoB' => 'Artigo.categoria_id',
      ];

      $limite = 10;

      $cacheNome = 'publico-busca-resultado-buscar-' . md5(serialize($textoBusca . $pagina));
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->artigoModel->selecionar($colunas)
                                       ->condicao($condicao)
                                       ->juntar($juntar, 'LEFT')
                                       ->ordem($ordem)
                                       ->pagina($limite, $pagina)
                                       ->executarConsulta();

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Artigo']['id'])) {
        $resultadoBuscar = $resultado;
      }

      $intervaloInicio = ($pagina - 1) * $limite + 1;
      $intervaloFim = min($pagina * $limite, $artigosTotal);
    }

    $categorias = [];

    if ((int) $this->buscarAjuste('publico_cate_busca') == 1) {
      $condicoes[] = [
        'campo' => 'Categoria.ativo', 'operador' => '=', 'valor' => ATIVO,
      ];

      if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
        unset($condicoes[0]);
      }
      elseif ($this->empresaId and $this->empresaId == $this->usuarioLogado['empresaId']) {
        unset($condicoes[0]);
      }

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

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('textoBusca', $textoBusca);
    $this->visao->variavel('resultadoBuscar', $resultadoBuscar);
    $this->visao->variavel('titulo', 'Buscar');
    $this->visao->variavel('menuLateral', $menuLateral);
    $this->visao->renderizar('/busca/index');
  }
}