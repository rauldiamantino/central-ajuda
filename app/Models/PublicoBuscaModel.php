<?php
namespace app\Models;
use app\Models\PublicoModel;

class PublicoBuscaModel extends PublicoModel
{
  public $artigoModel;

  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Artigo');

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function buscarArtigos(string $textoBusca, int $pagina = 1, int $limite = 10): array
  {
    $textoBusca = htmlspecialchars($textoBusca);
    $offset = ($pagina - 1) * $limite;

    $sql = <<<SQL
            SELECT SQL_CALC_FOUND_ROWS
              `Artigo`.`id` AS `Artigo.id`,
              `Artigo`.`ativo` AS `Artigo.ativo`,
              `Artigo`.`titulo` AS `Artigo.titulo`,
              `Artigo`.`categoria_id` AS `Artigo.categoria_id`,
              `Categoria`.`nome` AS `Categoria.nome`,
              GROUP_CONCAT(DISTINCT `Conteudo`.`titulo` ORDER BY `Conteudo`.`id` ASC) AS `Conteudo.titulo`,
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
                  MATCH(`Categoria`.`nome`, `Categoria`.`descricao`)
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
            LIMIT ?, ?;
           SQL;

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

    $busca = $this->artigoModel->executarQuery($sql, $sqlParams);
    $total = $this->artigoModel->executarQuery($totalSql);

    if (is_array($busca) and ! isset($busca['erro'])) {
      $busca = $this->artigoModel->organizarResultado($busca);
      $total = $this->artigoModel->organizarResultado($total);
    }
    else {
      $busca = [];
      $total = [];
    }

    return [
      'resultado' => $busca,
      'artigosTotal' => $total,
    ];
  }
}