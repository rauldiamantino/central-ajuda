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
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $artigosTotal = 0;
    $resultadoBuscar = [];

    $pagina = intval($_GET['pagina'] ?? 1);
    $textoBusca = htmlspecialchars($_GET['texto_busca'] ?? '');

    // Resultado por página
    $limite = 10;
    $offset = ($pagina - 1) * $limite;

    if (mb_strlen($textoBusca) > 2) {
      $sql = 'SELECT SQL_CALC_FOUND_ROWS
                `Artigo`.`id` AS `Artigo.id`,
                `Artigo`.`ativo` AS `Artigo.ativo`,
                `Artigo`.`titulo` AS `Artigo.titulo`,
                `Artigo`.`categoria_id` AS `Artigo.categoria_id`,
                `Categoria`.`nome` AS `Categoria.nome`,
                -- Usando GROUP_CONCAT para juntar os títulos dos conteúdos
                GROUP_CONCAT(DISTINCT `Conteudo`.`titulo` ORDER BY `Conteudo`.`id` ASC) AS `Conteudo.titulo`,
                -- Usando uma subconsulta para pegar o primeiro trecho do conteúdo
                (
                    SELECT SUBSTRING_INDEX(
                        SUBSTRING_INDEX(`Conteudo`.`conteudo`, ?, 1),
                        ?, 30
                    )
                    FROM `conteudos` AS `Conteudo`
                    WHERE `Conteudo`.`artigo_id` = `Artigo`.`id`
                    AND `Conteudo`.`empresa_id` = ?
                    ORDER BY `Conteudo`.`id` ASC
                    LIMIT 1
                ) AS `Conteudo.trecho`
              FROM
                `artigos` AS `Artigo`
              LEFT JOIN
                `categorias` AS `Categoria` ON `Categoria`.`id` = `Artigo`.`categoria_id` AND `Categoria`.`empresa_id` = ?
              LEFT JOIN
                `conteudos` AS `Conteudo` ON `Conteudo`.`artigo_id` = `Artigo`.`id`
                AND `Conteudo`.`empresa_id` = ?
              WHERE
                  `Artigo`.`empresa_id` = ?
                AND
                  `Artigo`.`ativo` = ?
                AND (
                    MATCH(`Categoria`.`nome`)
                      AGAINST(? IN NATURAL LANGUAGE MODE)
                    OR MATCH(`Artigo`.`titulo`)
                      AGAINST(? IN NATURAL LANGUAGE MODE)
                    OR (
                        `Conteudo`.`titulo_ocultar` = ?
                        AND MATCH(`Conteudo`.`titulo`)
                          AGAINST(? IN NATURAL LANGUAGE MODE)
                    )
                    OR MATCH(`Conteudo`.`conteudo`)
                      AGAINST(? IN NATURAL LANGUAGE MODE)
                )
              GROUP BY `Artigo`.`id`
              LIMIT ?, ?;';

      $totalSql = 'SELECT FOUND_ROWS();';

      $sqlParams = [
        1,                      // Posição inicial do SUBSTRING_INDEX (fixo)
        1,                      // Posição final do SUBSTRING_INDEX (fixo)
        $this->empresaPadraoId, // Conteudo.empresa_id (usado na subconsulta)
        $this->empresaPadraoId, // Categoria.empresa_id (usado no JOIN)
        $this->empresaPadraoId, // Conteudo.empresa_id (usado no JOIN)
        $this->empresaPadraoId, // Artigo.empresa_id
        ATIVO,                  // Artigo.ativo (verifique se ATIVO é um valor numérico como 1)
        $textoBusca,            // MATCH Categoria.nome
        $textoBusca,            // MATCH Artigo.titulo
        INATIVO,                // Conteudo.titulo_ocultar (verifique se INATIVO é um valor numérico, como 0)
        $textoBusca,            // MATCH Conteudo.titulo
        $textoBusca,            // MATCH Conteudo.conteudo
        $offset,                // OFFSET: Posição inicial dos resultados
        $limite,                // LIMIT: Quantidade de resultados por página
      ];

      $cacheNome = 'publico-busca-resultado-buscar-' . md5(serialize($textoBusca . $pagina));
      $resultadoCache = Cache::buscar($cacheNome, $this->empresaPadraoId);
      $resultadoCache = null;

      if ($resultadoCache == null) {
        $busca = $this->artigoModel->executarQuery($sql, $sqlParams);
        $total = $this->artigoModel->executarQuery($totalSql);

        if (is_array($busca) and ! isset($busca['erro'])) {
          $busca = $this->artigoModel->organizarResultado($busca);
          $total = $this->artigoModel->organizarResultado($total);
        }

        $resultadoCache = [
          'resultado' => $busca,
          'artigosTotal' => $total,
        ];

        Cache::definir($cacheNome, $resultadoCache, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultadoCache['resultado'][0]['Artigo']['id'])) {
        $resultadoBuscar = $resultadoCache['resultado'];
        $artigosTotal = intval($resultadoCache['artigosTotal'][0]['FOUND_ROWS()'] ?? 0);
      }

      $paginasTotal = ceil($artigosTotal / $limite); // Total de páginas
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