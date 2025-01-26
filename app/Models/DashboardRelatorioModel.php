<?php
namespace app\Models;
use app\Core\Cache;
use app\Models\Model;

class DashboardRelatorioModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'DashboardRelatorio');
  }

  public function buscarFeedbacks()
  {
    $sql = <<<SQL
            SELECT
              `artigos`.`id` AS `Artigo.id`,
              `artigos`.`codigo` AS `Artigo.codigo`,
              `artigos`.`titulo` AS `Artigo.titulo`,
              `artigos`.`empresa_id` AS `Artigo.empresa_id`,
              `artigos`.`categoria_id` AS `Artigo.categoria_id`,
              `categorias`.`nome` AS `Categoria.nome`,
              COUNT(CASE WHEN `feedbacks`.`util` = ? THEN ? END) AS `Feedback.gostou`,
              COUNT(CASE WHEN `feedbacks`.`util` = ? THEN ? END) AS `Feedback.nao_gostou`
            FROM
              `artigos`
              LEFT JOIN `categorias` ON `categorias`.`id` = `artigos`.`categoria_id`
              INNER JOIN `feedbacks` ON `feedbacks`.`artigo_id` = `artigos`.`id`
            WHERE
              `artigos`.`excluido` = ?
              AND `artigos`.`empresa_id` = ?
            GROUP BY
              `artigos`.`id`, `artigos`.`codigo`, `artigos`.`titulo`, `artigos`.`categoria_id`, `categorias`.`nome`
            ORDER BY
              `Feedback.nao_gostou` DESC;
           SQL;

    $sqlParams = [
      1,                      // Feedback.gostou - util
      1,                      // Feedback.gostou - then
      0,                      // Feedback.nao_gostou - util
      1,                      // Feedback.nao_gostou - then
      0,                      // Artigo.excluido
      $this->empresaPadraoId, // Artigo.empresa_id
    ];

    $cacheTempo = 5;
    $cacheNome = md5(serialize($sqlParams) . $sql);

    // Evita duplicidade de consulta
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->executarQuery($sql, $sqlParams);
      Cache::definir($cacheNome, $resultado, $cacheTempo, $this->empresaPadraoId);
    }

    if (is_array($resultado) and ! isset($resultado['erro'])) {
      $resultado = $this->organizarResultado($resultado);
    }
    else {
      $resultado = [];
    }

    return $resultado;
  }
}