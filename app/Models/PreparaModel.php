<?php
namespace app\Models;
use app\Models\Database;

class PreparaModel
{
  private $database;
  private $tabela;
  private $sqlSelect;
  private $sqlCount;
  private $sqlJoin;
  private $sqlOrder;
  private $sqlLimit;
  private $sqlOffset;
  private $sqlValores;
  private $sqlCondicoesOr;
  private $sqlCondicoesAnd;

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
      $this->sqlSelect = implode(', ', $temp);
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
    $this->sqlCount = 'SELECT COUNT(' . $tabelaCampo . ') AS `total` FROM ' .  $this->gerarBackticks($this->tabela);

    return $this;
  }

  public function juntar(array $params, string $tipo = 'INNER')
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

    $this->sqlJoin[] = $tipo . ' JOIN ' . $this->gerarBackticks($tabelaJoin) . ' ON ' . $campoA . ' = ' . $campoB;

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
      $this->sqlValores = array_merge($this->sqlValores, $valor);
    }
    else {
      $placeholders = '?';
      $this->sqlValores[] = $valor;
    }

    if ($tipo == 'AND') {
      $this->sqlCondicoesAnd[] = $campo . $operador . $placeholders;
    }
    elseif ($tipo == 'OR') {
      $this->sqlCondicoesOr[] = $campo . $operador . $placeholders;
    }
  }

  public function condicao(array $params, string $tipo = 'AND')
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

  public function existe(array $params, string $tipo = 'AND')
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
      $this->sqlCondicoesAnd[] = 'EXISTS (' . $subquery . ')';
    }
    elseif ($tipo == 'OR') {
      $this->sqlCondicoesOr[] = 'EXISTS (' . $subquery . ')';
    }

    return $this;
  }

  public function naoExiste(string $subquery, string $tipo = 'AND')
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
        $this->sqlCondicoesAnd[] = 'NOT EXISTS (' . $subquery . ')';
    }
    elseif ($tipo === 'OR') {
        $this->sqlCondicoesOr[] = 'NOT EXISTS (' . $subquery . ')';
    }

    return $this;
  }

  public function ordem(string $campo, string $direcao = 'ASC')
  {
    $temp = explode('.', $campo);

    if (count($temp) != 2) {
      return $this;
    }

    $tabela = $this->pluralizar($temp[0]);
    $tabelaCampo = $this->gerarBackticks($tabela, $temp[1]);

    $this->sqlOrder = 'ORDER BY ' . $tabelaCampo . ' ' . strtoupper($direcao);

    return $this;
  }

  public function limite(int $quantidade)
  {
    $this->sqlLimit = 'LIMIT ?';
    $this->sqlValores[] = (int) $quantidade;

    return $this;
  }

  public function offset(int $quantidade)
  {
    $this->sqlOffset = 'OFFSET ?';
    $this->sqlValores[] = (int) $quantidade;

    return $this;
  }

  public function montarConsulta()
  {
    $sql = '';

    if ($this->sqlSelect) {
      $sql = 'SELECT ' . $this->sqlSelect . ' FROM ' . $this->gerarBackticks($this->tabela);
    }
    elseif ($this->sqlCount) {
      $sql = $this->sqlCount;
    }

    if ($this->sqlJoin) {
      $sql .= ' ' . implode(' ', $this->sqlJoin);
    }

    if ($this->sqlCondicoesAnd or $this->sqlCondicoesOr) {
      $sql .= ' WHERE ';

      if ($this->sqlCondicoesAnd) {
        $sql .= implode(' AND ', $this->sqlCondicoesAnd);
      }

      if ($this->sqlCondicoesAnd and $this->sqlCondicoesOr) {
        $sql .= ' OR ' . implode(' OR ', $this->sqlCondicoesOr);
      }
    }

    if ($this->sqlOrder) {
      $sql .= ' ' . $this->sqlOrder;
    }

    if ($this->sqlLimit) {
      $sql .= ' ' . $this->sqlLimit;
    }

    if ($this->sqlOffset) {
      $sql .= ' ' . $this->sqlOffset;
    }

    return $sql;
  }

  public function executarConsulta()
  {
    $sql = $this->montarConsulta();

    if (empty($sql)) {
      return [];
    }

    $resultado = $this->database->operacoes($sql, $this->sqlValores);
    $this->limparPropriedades();

    if (is_array($resultado) and ! isset($resultado['erro'])) {
      return $this->organizarResultado($resultado);
    }

    return $resultado;
  }

  // --------------- Métodos auxiliares --------------- //
  private function limparPropriedades()
  {
    $this->sqlJoin = null;
    $this->sqlOrder = null;
    $this->sqlSelect = null;
    $this->sqlLimit = null;
    $this->sqlOffset = null;
    $this->sqlValores = null;
    $this->sqlCondicoesOr = null;
    $this->sqlCondicoesAnd = null;
  }

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
        $this->sqlValores = array_merge($this->sqlValores, $valor);
      }
      else {
        $placeholders = '?';
        $this->sqlValores[] = $valor;
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
    return $b ? '`' . $a . '`.`' . $b . '`' : '`' . $a . '`';
  }
}