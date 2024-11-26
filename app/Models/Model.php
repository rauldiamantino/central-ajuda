<?php
namespace app\Models;
use app\Models\Database;

class Model
{
  private $database;
  private $tabela;
  private $sqlSelect;
  private $sqlCount;
  private $sqlJoin;
  private $sqlOrder;
  private $sqlLimit;
  private $sqlPaginas;
  private $sqlValores = [];
  private $sqlCondicoesOr;
  private $sqlCondicoesAnd;
  protected $usuarioLogado;
  protected $empresaPadraoId;
  protected $sessaoUsuario;

  public function __construct($usuarioLogado, $empresaPadraoId, string $tabela)
  {
    global $sessaoUsuario;
    $this->sessaoUsuario = $sessaoUsuario;

    $this->usuarioLogado = $usuarioLogado;
    $this->empresaPadraoId = $empresaPadraoId;

    $this->database = new Database();
    $this->tabela = $tabela;
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
    $this->sqlCount = 'SELECT COUNT(' . $tabelaCampo . ') AS total FROM ' . $this->gerarBackticks($this->pluralizar($this->tabela));

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

    if (empty($campo) or empty($operador)) {
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
      $this->sqlCondicoesAnd[] = $campo . ' ' . $operador . ' ' . $placeholders;
    }
    elseif ($tipo == 'OR') {
      $this->sqlCondicoesOr[] = $campo . ' ' . $operador . ' ' . $placeholders;
    }
  }

  public function condicao(array $params, string $tipo = 'AND')
  {
    if (isset($params[0]['campo']) or isset($params[1]['campo'])) {
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

  public function ordem(array $params)
  {
    if (empty($params)) {
      return $this;
    }

    $ordem = [];
    foreach($params as $chave => $linha):
      $tempTabColuna = explode('.', $chave);
      $tabela = $this->pluralizar($tempTabColuna[0]);
      $coluna = $tempTabColuna[1] ?? '';

      if ($coluna) {
        $tabelaColuna = $this->gerarBackticks($tabela, $coluna);

        $ordem[] = $tabelaColuna . ' ' . $linha;
      }
    endforeach;

    $this->sqlOrder = 'ORDER BY ' . implode(', ', $ordem);

    return $this;
  }

  public function limite(int $quantidade)
  {
    $this->sqlLimit = 'LIMIT ?';
    $this->sqlValores[] = (int) $quantidade;

    return $this;
  }

  public function pagina(int $limite = 10, int $pagina = 1): self
  {
    $pagina = $pagina - 1;
    $offset = $pagina * $limite;

    if ($offset < 0) {
      $offset = 0;
    }

    $this->sqlPaginas = ' LIMIT ' . $limite . ' OFFSET ' . $offset;

    return $this;
  }

  public function executarConsulta()
  {
    // EmpresaID sempre primeiro
    if (! in_array($this->tabela, ['Empresa', 'Login'])) {

      if (empty($this->empresaPadraoId)) {
        return [];
      }

      $tabela = $this->pluralizar($this->tabela);
      $tabelaCampo = $this->gerarBackticks($tabela, 'empresa_id');
      $this->sqlCondicoesAnd[] = $tabelaCampo . '=' . $this->empresaPadraoId;
    }

    $sql = $this->montarConsulta();

    if (empty($sql)) {
      return [];
    }

    $resultado = $this->database->operacoes($sql, $this->sqlValores);

    if (is_array($resultado) and ! isset($resultado['erro'])) {
      $resultado = $this->organizarResultado($resultado);
    }

    $this->limparPropriedades();

    return $resultado;
  }

  // Queries manuais
  public function executarQuery(string $sql, array $params = []): array
  {
    if (empty($sql)) {
      return [];
    }

    $sql = trim($sql);
    return $this->database->operacoes($sql, $params);
  }

  public function executarQueryLogin(string $sql, array $params = []): array
  {
    if (empty($sql)) {
      return [];
    }

    return $this->database->operacoes($sql, $params);
  }

  // Revisar
  public function adicionar(array $params): array
  {
    $temp = [];
    foreach ($params as $chave => $linha):
      $temp[] = $chave;
      $this->sqlValores[] = $linha;
    endforeach;

    // Prepara
    $colunas = implode(', ', $temp);
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);
    $placeholders = $this->gerarPlaceholders($this->sqlValores, true);

    $sql = 'INSERT INTO ' . $tabela . ' (' . $colunas . ') VALUES ' . $placeholders;
    $resultado = $this->database->operacoes($sql, $this->sqlValores);

    $this->limparPropriedades();

    return $resultado;
  }

  // Revisar
  public function atualizar(array $params, int $id): array
  {
    $tempColunasValores = [];
    foreach ($params as $chave => $linha):
      $valor = $this->gerarPlaceholders();

      $tempColunasValores[] = $chave . ' = ' . $valor;
      $this->sqlValores[] = $linha;
    endforeach;

    // Prepara
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);

    $colunasValores = implode(', ', $tempColunasValores);
    $placeholders = $this->gerarPlaceholders();
    $this->sqlValores[] = $id;

    $andEmpresa = '';

    if ($tabela != 'empresas') {
      $andEmpresa = ' AND empresa_id=' . $this->empresaPadraoId;
    }

    $sql = 'UPDATE ' . $tabela . ' SET ' . $colunasValores . ' WHERE id = ' . $placeholders . $andEmpresa;
    $resultado = $this->database->operacoes($sql, $this->sqlValores);

    $this->limparPropriedades();

    return $resultado;
  }

  // Revisar
  public function apagar(int $id): array
  {
    $opLogico = ' = ';
    $coluna = 'id';
    $placeholders = $this->gerarPlaceholders();
    $this->sqlValores[] = $id;

    // Prepara
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);
    $placeholders = $this->gerarPlaceholders($this->sqlValores, true);

    $sql = 'DELETE FROM ' . $tabela . ' WHERE ' . $coluna . $opLogico . $placeholders;
    $resultado = $this->database->operacoes($sql, $this->sqlValores);

    $this->limparPropriedades();

    return $resultado;
  }

  // --------------- Métodos auxiliares --------------- //
  public function montarConsulta()
  {
    $sql = '';

    if ($this->sqlSelect) {
      $sql = 'SELECT ' . $this->sqlSelect . ' FROM ' . $this->gerarBackticks($this->pluralizar($this->tabela));
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

    if ($this->sqlPaginas) {
      $sql .= $this->sqlPaginas;
    }

    if (empty($this->sqlPaginas) and $this->sqlLimit) {
      $sql .= ' ' . $this->sqlLimit;
    }

    return $sql;
  }

  private function limparPropriedades()
  {
    $this->sqlJoin = null;
    $this->sqlOrder = null;
    $this->sqlCount = null;
    $this->sqlSelect = null;
    $this->sqlLimit = null;
    $this->sqlValores = [];
    $this->sqlPaginas = null;
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
      $array = $resultado;
    }

    // Retorna na raíz
    if ($this->sqlCount) {
      $array = reset($array);
    }

    return $array;
  }

  private function gerarSubquery(string $tabela, array $params): string
  {
    $consultas = [];
    foreach ($params as $chave => $linha):
      $campo = $linha['campo'] ?? '';
      $operador = $linha['operador'] ?? '';
      $valor = $linha['valor'] ?? '';

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

  private function gerarPlaceholders(array $params = [], bool $multiplos = false): string
  {
    $placeholder = '?';

    if ($multiplos) {
      $placeholder = str_repeat('?, ', count($params));
      $placeholder = '(' . rtrim($placeholder, ', ') . ')';
    }

    return $placeholder;
  }

  private function camel2Snake(string $tabela)
  {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $tabela));
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