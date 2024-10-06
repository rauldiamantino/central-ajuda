<?php
namespace app\Models;
use app\Models\Database;

class Model
{
  protected $tabela = '';
  protected $colunas = '';
  protected $database = '';
  protected $paginacao = '';
  protected $contarColunas = '';
  protected $limiteRegistros = 0;
  protected $ordem = [];
  protected $unioes = [];
  protected $condicoes = [];
  protected $parametros = [];
  protected $colunasValores = [];

  protected $sessaoUsuario;
  protected $usuarioLogado;
  protected $empresaPadraoId;

  public function __construct($usuarioLogado, $empresaPadraoId, string $tabela = '')
  {
    $this->usuarioLogado = $usuarioLogado;
    $this->empresaPadraoId = $empresaPadraoId;

    $this->tabela = $tabela;
    $this->database = new Database();
  }

  // --- CRUD ---
  public function adicionar(array $params): array
  {
    $temp = [];
    foreach ($params as $chave => $linha):
      $temp[] = $this->gerarBackticks($chave);
      $this->parametros[] = $linha;
    endforeach;

    // Prepara
    $colunas = implode(', ', $temp);
    $tabelaAlias = $this->camel2Snake($this->tabela);
    $tabelaAlias = $this->pluralizar($tabelaAlias);
    $tabelaAlias = $this->gerarBackticks($tabelaAlias);
    $placeholders = $this->gerarPlaceholders($this->parametros, true);

    $sql = 'INSERT INTO ' . $tabelaAlias . ' (' . $colunas . ') VALUES ' . $placeholders;
    $resultado = $this->database->operacoes($sql, $this->parametros);

    $this->limparPropriedades();

    return $resultado;
  }

  public function buscar(array $colunas = []): array
  {

    $tabelaColuna = [];
    foreach ($colunas as $linha):
      // Prepara
      $temp = explode('.', $linha);
      $tabela = $temp[0] ?? '';
      $coluna = $temp[1] ?? '';

      if (empty($coluna)) {
        return [];
      }

      $tabelaColunaAlias = $this->gerarBackticks($tabela . '.' . $coluna);
      $tabelaColuna[] = $this->gerarBackticks($tabela, $coluna) . ' AS ' . $tabelaColunaAlias;
    endforeach;

    $this->colunas = implode(', ', $tabelaColuna);

    if (empty($this->colunas)) {
      $this->colunas = '*';
    }

    return $this->executarBusca();
  }

  public function atualizar(array $params, int $id): array
  {
    foreach ($params as $chave => $linha):
      $coluna = $this->gerarBackticks($this->tabela, $chave);
      $valor = $this->gerarPlaceholders();

      $this->colunasValores[] = $coluna . ' = ' . $valor;
      $this->parametros[] = $linha;
    endforeach;

    // Prepara
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);
    $tabela = $this->gerarBackticks($tabela);
    $tabelaAlias = $this->gerarBackticks($this->tabela);

    $colunasValores = implode(', ', $this->colunasValores);
    $placeholders = $this->gerarPlaceholders();
    $this->parametros[] = $id;

    $sql = 'UPDATE ' . $tabela . ' AS ' . $tabelaAlias . ' SET ' . $colunasValores . ' WHERE id = ' . $placeholders;
    $resultado = $this->database->operacoes($sql, $this->parametros);

    $this->limparPropriedades();

    return $resultado;
  }

  public function apagar(int $id): array
  {
    $opLogico = ' = ';
    $coluna = $this->gerarBackticks($this->tabela, 'id');
    $tabela = $this->gerarBackticks($this->tabela);
    $placeholders = $this->gerarPlaceholders();
    $this->parametros[] = $id;

    // Prepara
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);
    $tabela = $this->gerarBackticks($tabela);
    $tabelaAlias = $this->gerarBackticks($this->tabela);
    $placeholders = $this->gerarPlaceholders($this->parametros, true);

    $sql = 'DELETE FROM ' . $tabela . ' AS ' . $tabelaAlias . ' WHERE ' . $coluna . $opLogico . $placeholders;
    $resultado = $this->database->operacoes($sql, $this->parametros);

    $this->limparPropriedades();

    return $resultado;
  }

  // --- Métodos auxiliares ---
  public function ordem(array $params = []): self
  {
    if (empty($params)) {
      return $this;
    }

    foreach($params as $chave => $linha):
      $tempTabColuna = explode('.', $chave);
      $tabela = $tempTabColuna[0];
      $coluna = $tempTabColuna[1] ?? '';

      if ($coluna) {
        $tabelaColuna = $this->gerarBackticks($tabela, $coluna);

        $this->ordem[] = $tabelaColuna . ' ' . $linha;
      }
    endforeach;

    return $this;
  }

  private function executarBusca(array $params = []): array
  {
    // Prepara
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);
    $tabela = $this->gerarBackticks($tabela);
    $tabelaAlias = $this->gerarBackticks($this->tabela);

    $sql = 'SELECT ' . $this->colunas . ' FROM ' . $tabela . ' AS ' . $tabelaAlias;

    // Por enquanto não aceita SELECT *
    if ($this->unioes and $this->colunas != '*') {
      $sql .= ' ' . implode(' ', $this->unioes);
    }

    if (isset($this->condicoes['erro'])) {
      return [];
    }

    if ($this->condicoes) {
      $sql .= ' WHERE ' . implode(' ', $this->condicoes);

      if ($this->tabela == 'Empresa') {
        $sql .= ' AND ' . $this->gerarBackticks($this->tabela, 'id') . ' = ?';
      }
      else {
        $sql .= ' AND ' . $this->gerarBackticks($this->tabela, 'empresa_id') . ' = ?';
      }

      $this->parametros[] = $this->empresaPadraoId;
    }
    elseif ($this->tabela == 'Empresa') {
      $sql .= ' WHERE ' . $this->gerarBackticks($this->tabela, 'id') . ' = ?';
      $this->parametros[] = $this->empresaPadraoId;
    }
    else {
      $sql .= ' WHERE ' . $this->gerarBackticks($this->tabela, 'empresa_id') . ' = ?';
      $this->parametros[] = $this->empresaPadraoId;
    }

    if ($this->ordem) {
      $sql .= ' ORDER BY ' . implode(', ', $this->ordem);
    }

    if ($this->paginacao) {
      $sql .= $this->paginacao;
    }

    if (empty($this->paginacao) and $this->limiteRegistros) {
      $sql .= ' LIMIT ' . $this->limiteRegistros;
    }

    $resultado = $this->database->operacoes($sql, $this->parametros);

    if (empty($resultado)) {
      $resultado = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Recurso não encontrado',
        ],
      ];
    }

    $this->limparPropriedades();

    return $resultado;
  }

  private function executarContar(array $params = []): array
  {
    // Prepara
    $tabela = $this->camel2Snake($this->tabela);
    $tabela = $this->pluralizar($tabela);
    $tabela = $this->gerarBackticks($tabela);
    $tabelaAlias = $this->gerarBackticks($this->tabela);

    $sql = 'SELECT COUNT(' . $this->contarColunas . ') AS `total` FROM ' . $tabela . ' AS ' . $tabelaAlias;

    // Por enquanto não aceita SELECT *
    if ($this->unioes and $this->colunas != '*') {
      $sql .= ' ' . implode(' ', $this->unioes);
    }

    if (isset($this->condicoes['erro'])) {
      return [];
    }

    if ($this->condicoes) {
      $sql .= ' WHERE ' . implode(' ', $this->condicoes);

      if ($this->tabela == 'Empresa') {
        $sql .= ' AND ' . $this->gerarBackticks($this->tabela, 'id') . ' = ?';
      }
      else {
        $sql .= ' AND ' . $this->gerarBackticks($this->tabela, 'empresa_id') . ' = ?';
      }

      $this->parametros[] = $this->empresaPadraoId;
    }
    elseif ($this->tabela == 'Empresa') {
      $sql .= ' WHERE ' . $this->gerarBackticks($this->tabela, 'id') . ' = ?';
      $this->parametros[] = $this->empresaPadraoId;
    }
    else {
      $sql .= ' WHERE ' . $this->gerarBackticks($this->tabela, 'empresa_id') . ' = ?';
      $this->parametros[] = $this->empresaPadraoId;
    }

    if ($this->paginacao) {
      $sql .= $this->paginacao;
    }

    $resultado = $this->database->operacoes($sql, $this->parametros);

    if (empty($resultado) or ! is_array($resultado)) {
      $resultado = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Recurso não encontrado',
        ],
      ];
    }

    $this->limparPropriedades();

    return reset($resultado);
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

  public function uniao(array $params = [], string $uniao = 'INNER'): self
  {
    foreach ($params as $linha):
      // Prepara Join
      $tabelaUniao = $this->pluralizar($linha);
      $tabelaUniao = $this->camel2Snake($tabelaUniao);
      $tabelaUniao = $this->gerarBackticks($tabelaUniao);
      $tabUniaoAlias = $this->gerarBackticks($linha);
      $tabelaAlias = $this->gerarBackticks($this->tabela, 'id');
      $colunaRelAlias = strtolower($this->tabela) . '_id';
      $colunaRelAlias = $this->gerarBackticks($colunaRelAlias);
      $tabelaColunaRel = $tabUniaoAlias . '.' . $colunaRelAlias;

      $this->unioes[] = $uniao . ' JOIN ' . $tabelaUniao . ' AS ' . $tabUniaoAlias . ' ON ' . $tabelaAlias . ' = ' . $tabelaColunaRel;
    endforeach;

    return $this;
  }

  public function uniao2(array $params = [], string $uniao = 'INNER'): self
  {
    foreach ($params as $linha):
      // Prepara Join
      $tabelaUniao = $this->pluralizar($linha);
      $tabelaUniao = $this->camel2Snake($tabelaUniao);
      $tabelaUniao = $this->gerarBackticks($tabelaUniao);
      $tabUniaoAlias = $this->gerarBackticks($linha);

      $colunaUniao = strtolower($linha) . '_id';
      $tabelaAlias = $this->gerarBackticks($this->tabela, $colunaUniao);
      $tabelaColUniao = $this->gerarBackticks($linha, 'id');

      $this->unioes[] = $uniao . ' JOIN ' . $tabelaUniao . ' AS ' . $tabUniaoAlias . ' ON ' . $tabelaAlias . ' = ' . $tabelaColUniao;
    endforeach;

    return $this;
  }

  public function contar(string $colunaContar): array
  {
    $temp = explode('.', $colunaContar);
    $tabela = $temp[0] ?? '';
    $coluna = $temp[1] ?? '';

    if (empty($coluna)) {
      return [];
    }

    // Prepara
    $this->contarColunas = $this->gerarBackticks($tabela, $coluna);

    return $this->executarContar();
  }

  public function limite(int $limite = 0): self
  {
    $this->limiteRegistros = $limite;

    return $this;
  }

  public function pagina(int $limite = 10, int $pagina = 1): self
  {
    $pagina = $pagina - 1;
    $offset = $pagina * $limite;

    if ($offset < 0) {
      $offset = 0;
    }

    $this->paginacao = ' LIMIT ' . $limite . ' OFFSET ' . $offset;

    return $this;
  }

  public function condicao(array $condicoes = []): self
  {
    if ($condicoes) {
      foreach ($condicoes as $chave => $linha):
        $tabelaColuna = explode('.', strtok($chave, ' '));
        $tabela = $tabelaColuna[0];
        $coluna = $tabelaColuna[1] ?? '';

        if (empty($coluna)) {
          $this->condicoes['erro'] = 1;
          break;
        }

        $params = [
          'opLogico' => '',
          'coluna' => $this->gerarBackticks($tabela, $coluna),
          'opComparacao' => $this->obterOpComparacao($chave),
          'valor' => $linha,
        ];

        if (strtoupper($chave) === 'OR') {
          $this->adicionarCondicoesOu($linha);
        }
        elseif ($this->condicoes and is_array($linha)) {
          $params['opLogico'] = 'AND';
          $this->adicionarCondicoesMultiplas($params);
        }
        elseif ($this->condicoes) {
          $params['opLogico'] = 'AND';
          $this->adicionarCondicaoUnica($params);
        }
        elseif (is_array($linha)) {
          $this->adicionarCondicoesMultiplas($params);
        }
        else {
          $this->adicionarCondicaoUnica($params);
        }
      endforeach;
    }

    return $this;
  }

  private function adicionarCondicoesOu(array $condicao): void
  {
    foreach ($condicao as $chave => $linha):
      $params = [
        'opLogico' => 'OR',
        'coluna' => strtok($chave, ' '),
        'opComparacao' => $this->obterOpComparacao($chave),
        'valor' => $linha,
      ];

      if (is_array($linha)) {

        if ($params['opComparacao'] == '=') {
          $params['opComparacao'] = 'IN';
        }

        $this->condicoes[] = $this->gerarCondicao($params);

        foreach ($linha as $sublinha):
          $this->parametros[] = $sublinha;
        endforeach;
      }
      else {
        $this->condicoes[] = $this->gerarCondicao($params);
        $this->parametros[] = $linha;
      }
    endforeach;
  }

  private function adicionarCondicoesMultiplas(array $params = []): void
  {
    if ($params['opComparacao'] == '=') {
      $params['opComparacao'] = 'IN';
    }

    $this->condicoes[] = $this->gerarCondicao($params);

    foreach ($params['valor'] as $sublinha):
      $this->parametros[] = $sublinha;
    endforeach;
  }

  private function adicionarCondicaoUnica(array $params = []): void
  {
    $this->condicoes[] = $this->gerarCondicao($params);
    $this->parametros[] = $params['valor'];
  }

  private function obterOpComparacao(string $coluna): string
  {
    $opComparacao = strstr($coluna, ' ');
    $opComparacao = trim($opComparacao);

    if (empty($opComparacao)) {
      $opComparacao = '=';
    }

    return $opComparacao;
  }

  private function gerarCondicao(array $params): string
  {
    $params['valor'] = $this->gerarPlaceholders($params);
    $condicao = trim(implode(' ', $params));

    return $condicao;
  }

  private function gerarPlaceholders(array $params = [], bool $multiplos = false): string
  {
    $placeholder = '?';

    if ($multiplos) {
      $placeholder = str_repeat('?, ', count($params));
      $placeholder = '(' . rtrim($placeholder, ', ') . ')';
    }
    elseif (isset($params['opComparacao']) and substr(trim($params['opComparacao']), -2) == 'IN') {
      $placeholder = str_repeat('?, ', count($params['valor']));
      $placeholder = '(' . rtrim($placeholder, ', ') . ')';
    }

    return $placeholder;
  }

  private function gerarBackticks($a, $b = '')
  {
    if ($b) {
      return '`' . $a . '`.`' . $b . '`';
    }

    return '`' . $a . '`';
  }

  private function pluralizar(string $texto)
  {
    return $texto . 's';
  }

  private function camel2Snake(string $tabela)
  {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $tabela));
  }

  private function limparPropriedades()
  {
    $this->colunas = '';
    $this->condicoes = [];
    $this->parametros = [];
    $this->colunasValores = [];
    $this->contarColunas = '';
    $this->paginacao = '';
    $this->unioes = [];
    $this->ordem = [];
  }
}