<?php
namespace app\Models;
use app\Models\Database;

class PreparaModel
{
  private $database;
  private $tabela;
  private $select;
  private $count;
  private $joins;
  private $ordem;
  private $limite;
  private $offset;
  private $valores;
  private $condicoesOr;
  private $condicoesAnd;

  public function __construct(string $tabela)
  {
    $this->database = new Database();
    $this->tabela = $this->pluralizar($tabela);
  }

  public function selecionar(array $campos = [])
  {
    if (empty($campos)) {
      return $this;
    }

    $temp = [];
    foreach ($campos as $campoAlias):
      $partes = explode('.', $campoAlias);

      if (count($partes) != 2) {
        continue;
      }

      $tabela = $this->pluralizar($partes[0]);
      $temp[] = $this->gerarBackticks($tabela, $partes[1]) . ' AS ' . $this->gerarBackticks($campoAlias);
    endforeach;

    if (empty($temp) and is_string($campos)) {
      $campoAlias = $campos;
      $partes = explode('.', $campos);

      if (count($partes) == 2) {
        $tabela = $this->pluralizar($partes[0]);
        $temp[] = $this->gerarBackticks($tabela, $partes[1]) . ' AS ' . $this->gerarBackticks($campoAlias);
      }
    }

    if ($temp) {
      $this->select = implode(', ', $temp);
    }

    return $this;
  }

  public function contar(string $campo)
  {
    $partes = explode('.', $campo);

    if (count($partes) != 2) {
      return $this;
    }

    $tabela = $this->pluralizar($partes[0]);
    $tabelaCampo = $this->gerarBackticks($tabela, $partes[1]);
    $this->count = 'SELECT COUNT(' . $tabelaCampo . ') AS `total` FROM ' .  $this->gerarBackticks($this->tabela);

    return $this;
  }

  public function adicionarJoin(array $params, string $tipo = 'INNER')
  {
    $campoA = $params['campoA'] ?? '';
    $campoB = $params['campoB'] ?? '';
    $tabelaJoin = $params['tabelaJoin'] ?? '';
    $tabelaJoin = $this->pluralizar($tabelaJoin);

    $tempA = explode('.', $campoA);
    $tempB = explode('.', $campoB);

    $campoA = count($tempA) == 2 ? $this->gerarBackticks($tabelaJoin, $tempA[1]) : '';
    $campoB = count($tempB) == 2 ? $this->gerarBackticks($this->pluralizar($tempB[0]), $tempB[1]) : '';

    if (empty($campoA) or empty($campoB) or empty($tabelaJoin)) {
      return $this;
    }

    $this->joins[] = $tipo . ' JOIN ' . $this->gerarBackticks($tabelaJoin) . ' ON ' . $campoA . ' = ' . $campoB;

    return $this;
  }

  private function gerarCondicao(array $params, string $tipo)
  {
    $campo = $params['campo'] ?? '';
    $valor = $params['valor'] ?? null;
    $operador = $params['operador'] ?? '';

    $temp = explode('.', $campo);
    $campo = count($temp) == 2 ? $this->gerarBackticks($this->pluralizar($temp[0]), $temp[1] ?? '') : '';

    if (empty($campo) or empty($operador) or empty($valor)) {
      return $this;
    }

    if (is_array($valor)) {
      $placeholders = '(' . implode(', ', array_fill(0, count($valor), '?')) . ')';
      $this->valores = array_merge($this->valores, $valor);
    }
    else {
      $placeholders = '?';
      $this->valores[] = $valor;
    }

    if ($tipo == 'AND') {
      $this->condicoesAnd[] = $campo . $operador . $placeholders;
    }
    elseif ($tipo == 'OR') {
      $this->condicoesOr[] = $campo . $operador . $placeholders;
    }
  }

  public function adicionarCondicao(array $params, string $tipo = 'AND')
  {
    if (isset($params[0]['campo'])) {
      foreach ($params as $linha):
        $this->gerarCondicao($linha, $tipo);
      endforeach;
    }
    else {
      $this->gerarCondicao($params, $tipo);
    }

    return $this;
  }

  public function adicionarExists(array $params, string $tipo = 'AND')
  {
    $tabela = $params['tabela'] ?? '';
    $tabela = $this->pluralizar($tabela);
    $tabela = $this->gerarBackticks($tabela);
    $params = $params['params'] ?? [];

    $subquery = $this->gerarSubquery($tabela, $params);

    if (empty($subquery)) {
      return $this;
    }

    if ($tipo == 'AND') {
      $this->condicoesAnd[] = 'EXISTS (' . $subquery . ')';
    }
    elseif ($tipo == 'OR') {
      $this->condicoesOr[] = 'EXISTS (' . $subquery . ')';
    }

    return $this;
  }

  public function adicionarNotExists(string $subquery, string $tipo = 'AND')
  {
    $tabela = $params['tabela'] ?? '';
    $tabela = $this->pluralizar($tabela);
    $tabela = $this->gerarBackticks($tabela);
    $params = $params['params'] ?? [];

    $subquery = $this->gerarSubquery($tabela, $params);

    if (empty($subquery)) {
      return $this;
    }

    if ($tipo === 'AND') {
        $this->condicoesAnd[] = 'NOT EXISTS (' . $subquery . ')';
    }
    elseif ($tipo === 'OR') {
        $this->condicoesOr[] = 'NOT EXISTS (' . $subquery . ')';
    }

    return $this;
  }

  public function adicionarOrdem(string $campo, string $direcao = 'ASC')
  {
    $temp = explode('.', $campo);

    if (count($temp) != 2) {
      return $this;
    }

    $tabela = $this->pluralizar($temp[0]);
    $tabelaCampo = $this->gerarBackticks($tabela, $temp[1]);

    $this->ordem = 'ORDER BY ' . $tabelaCampo . ' ' . strtoupper($direcao);

    return $this;
  }

  public function adicionarLimite(int $quantidade)
  {
    $this->limite = 'LIMIT ?';
    $this->valores[] = (int) $quantidade;

    return $this;
  }

  public function adicionarOffset(int $quantidade)
  {
    $this->offset = 'OFFSET ?';
    $this->valores[] = (int) $quantidade;

    return $this;
  }

  public function montarConsulta()
  {
    $sql = '';

    if ($this->select) {
      $sql = 'SELECT ' . $this->select . ' FROM ' . $this->gerarBackticks($this->tabela);
    }
    elseif ($this->count) {
      $sql = $this->count;
    }

    if ($this->joins) {
      $sql .= ' ' . implode(' ', $this->joins);
    }

    if ($this->condicoesAnd or $this->condicoesOr) {
      $sql .= ' WHERE ';

      if ($this->condicoesAnd) {
        $sql .= implode(' AND ', $this->condicoesAnd);
      }

      if ($this->condicoesAnd and $this->condicoesOr) {
        $sql .= ' OR ' . implode(' OR ', $this->condicoesOr);
      }
    }

    if ($this->ordem) {
      $sql .= ' ' . $this->ordem;
    }

    if ($this->limite) {
      $sql .= ' ' . $this->limite;
    }

    if ($this->offset) {
      $sql .= ' ' . $this->offset;
    }

    return $sql;
  }

  public function executarConsulta()
  {
    $sql = $this->montarConsulta();

    if (empty($sql)) {
      return [];
    }

    $resultado = $this->database->operacoes($sql, $this->valores);
    $this->limparPropriedades();

    if (is_array($resultado) and ! isset($resultado['erro'])) {
      return $this->organizarResultado($resultado);
    }

    return $resultado;
  }

  // --------------- Métodos auxiliares --------------- //
  function organizarResultado(array $resultado = []) {
    $array = [];
    foreach ($resultado as $linha):
      $registroAtual = [];
      foreach ($linha as $chave => $valor):
        $partes = explode('.', $chave);

        if (count($partes) != 2) {
          continue;
        }

        $tabela = $partes[0];
        $campo = $partes[1];
        $registroAtual[ $tabela ][ $campo ] = $valor;
      endforeach;

      if (empty($registroAtual)) {
        continue;
      }

      $array[] = $registroAtual;
    endforeach;

    if (empty($array)) {
      return $resultado;
    }

    return $array;
  }

  private function gerarSubquery(string $tabela, array $params): string
  {
    $consultas = [];
    foreach ($params as $chave => $linha):
      $campo = $linha['campo'] ?? '';
      $operador = $linha['operador'] ?? '';
      $valor = $linha['valor'] ?? null;
      $tempCampo = explode('.', $campo);
      $tempValor = explode('.', $valor);

      $campo = count($tempCampo) == 2 ? $this->gerarBackticks($this->pluralizar($tempCampo[0]), $tempCampo[1] ?? '') : '';

      // tabela.campo como valor
      if (count($tempValor) == 2) {
        $valor = $this->gerarBackticks($this->pluralizar($tempValor[0]), $tempValor[1]);
      }

      if (empty($campo)) {
        continue;
      }

      if (empty($operador)) {
        continue;
      }

      if (count($tempValor) == 2) {
        $placeholders = $valor;
      }
      elseif (is_array($valor)) {
        $placeholders = '(' . implode(', ', array_fill(0, count($valor), '?')) . ')';
        $this->valores = array_merge($this->valores, $valor);
      }
      else {
        $placeholders = '?';
        $this->valores[] = $valor;
      }

      $consultas[] = $campo . $operador . $placeholders;
    endforeach;

    return 'SELECT 1 FROM ' . $tabela . ' WHERE ' . implode(' AND ', $consultas);
  }

  private function pluralizar(string $texto)
  {
    return strtolower($texto) . 's';
  }


  private function gerarBackticks(string $a, string $b = ''): string
  {
    if ($b) {
      return '`' . $a . '`.`' . $b . '`';
    }

    return '`' . $a . '`';
  }

  private function limparPropriedades()
  {
    $this->joins = null;
    $this->ordem = null;
    $this->select = null;
    $this->limite = null;
    $this->offset = null;
    $this->valores = null;
    $this->condicoesOr = null;
    $this->condicoesAnd = null;
  }
}