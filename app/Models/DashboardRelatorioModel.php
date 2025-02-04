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

  public function buscarFeedbacks(array $condicoes = [])
  {
    $where = '`Artigo`.`excluido` = ? AND `Artigo`.`empresa_id` = ?';

    $sqlParams = [
      1,                      // Feedback.gostou - util
      1,                      // Feedback.gostou - then
      0,                      // Feedback.nao_gostou - util
      1,                      // Feedback.nao_gostou - then
      0,                      // Artigo.excluido
      $this->empresaPadraoId, // Artigo.empresa_id
    ];

    if ($condicoes) {
      foreach ($condicoes as $chave => $linha):
        $condicoesPermitidas = [
          'Artigo.codigo' => '`Artigo`.`codigo`',
          'Artigo.categoria_id' => '`Artigo`.`categoria_id`',
          'Feedback.criado' => '`feedbacks`.`criado`',
        ];

        $operadoresPermitidos = [
          '=',
          '!=',
          'LIKE',
          'IS',
          'BETWEEN',
        ];

        if (! isset($linha['campo']) or ! isset($linha['campo'], $condicoesPermitidas)) {
          continue;
        }

        if (! isset($linha['operador']) or ! isset($linha['operador'], $operadoresPermitidos)) {
          continue;
        }

        $campo = $condicoesPermitidas[ $linha['campo'] ];
        $operador = $linha['operador'];
        $valor = $linha['valor'] ?? null;

        if (is_array($valor) and $operador != 'BETWEEN' and $campo != '`feedbacks`.`criado`') {
          $valor = null;
        }

        if ($operador == 'BETWEEN') {

          if (! is_array($valor) or count($valor) != 2) {
            continue;
          }

          $where .= ' AND (' . $campo . ' ' . $operador . ' ? AND ?)';
          $sqlParams[] = $valor[0];
          $sqlParams[] = $valor[1];
        }
        else {
          $where .= ' AND ' . $campo . ' ' . $operador . ' ?';
          $sqlParams[] = $valor;
        }
      endforeach;
    }

    $sql = <<<SQL
            SELECT
              `Artigo`.`id` AS `Artigo.id`,
              `Artigo`.`codigo` AS `Artigo.codigo`,
              `Artigo`.`titulo` AS `Artigo.titulo`,
              `Artigo`.`empresa_id` AS `Artigo.empresa_id`,
              `Artigo`.`categoria_id` AS `Artigo.categoria_id`,
              `categorias`.`nome` AS `Categoria.nome`,
              COUNT(CASE WHEN `feedbacks`.`util` = ? THEN ? END) AS `Feedback.gostou`,
              COUNT(CASE WHEN `feedbacks`.`util` = ? THEN ? END) AS `Feedback.nao_gostou`
            FROM
              `artigos` AS `Artigo`
              LEFT JOIN `categorias` ON `categorias`.`id` = `Artigo`.`categoria_id`
              INNER JOIN `feedbacks` ON `feedbacks`.`artigo_id` = `Artigo`.`id`
            WHERE
              $where
            GROUP BY
              `Artigo`.`id`, `Artigo`.`codigo`, `Artigo`.`titulo`, `Artigo`.`categoria_id`, `categorias`.`nome`
            ORDER BY
              `Feedback.gostou` DESC;
           SQL;

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

  public function buscarVisualizacoes(array $condicoes = [])
  {
    $where = '`Artigo`.`excluido` = ? AND `Artigo`.`empresa_id` = ?';

    $sqlParams = [
      0,                      // Artigo.excluido
      $this->empresaPadraoId, // Artigo.empresa_id
    ];

    if ($condicoes) {
      foreach ($condicoes as $chave => $linha):
        $condicoesPermitidas = [
          'Artigo.codigo' => '`Artigo`.`codigo`',
          'Artigo.categoria_id' => '`Artigo`.`categoria_id`',
          'Visualizacao.criado' => '`visualizacoes`.`criado`',
        ];

        $operadoresPermitidos = [
          '=',
          '!=',
          'LIKE',
          'IS',
          'BETWEEN',
        ];

        if (! isset($linha['campo']) or ! isset($linha['campo'], $condicoesPermitidas)) {
          continue;
        }

        if (! isset($linha['operador']) or ! isset($linha['operador'], $operadoresPermitidos)) {
          continue;
        }

        $campo = $condicoesPermitidas[ $linha['campo'] ];
        $operador = $linha['operador'];
        $valor = $linha['valor'] ?? null;

        if (is_array($valor) and $operador != 'BETWEEN' and $campo != '`visualizacoes`.`criado`') {
          $valor = null;
        }

        if ($operador == 'BETWEEN') {

          if (! is_array($valor) or count($valor) != 2) {
            continue;
          }

          $where .= ' AND (' . $campo . ' ' . $operador . ' ? AND ?)';
          $sqlParams[] = $valor[0];
          $sqlParams[] = $valor[1];
        }
        else {
          $where .= ' AND ' . $campo . ' ' . $operador . ' ?';
          $sqlParams[] = $valor;
        }
      endforeach;
    }

    $sql = <<<SQL
            SELECT
              `Artigo`.`id` AS `Artigo.id`,
              `Artigo`.`codigo` AS `Artigo.codigo`,
              `Artigo`.`titulo` AS `Artigo.titulo`,
              `Artigo`.`empresa_id` AS `Artigo.empresa_id`,
              `Artigo`.`categoria_id` AS `Artigo.categoria_id`,
              `categorias`.`nome` AS `Categoria.nome`,
              COUNT(`visualizacoes`.`id`) AS `Visualizacao.total`
            FROM
              `artigos` as `Artigo`
              LEFT JOIN `categorias` ON `categorias`.`id` = `Artigo`.`categoria_id`
              INNER JOIN `visualizacoes` ON `visualizacoes`.`artigo_id` = `Artigo`.`id`
            WHERE
              $where
            GROUP BY
              `Artigo`.`id`, `Artigo`.`codigo`, `Artigo`.`titulo`, `Artigo`.`categoria_id`, `Artigo`.`empresa_id`, `categorias`.`nome`
            ORDER BY
              COUNT(`visualizacoes`.`id`) DESC
           SQL;

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