<?php
namespace app\Models;

use Exception;
use \PDO;

class Database
{
  private $conexao;

  public function __construct()
  {
    try {
      $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NOME . ';charset=utf8';
      $this->conexao = new PDO($dsn, DB_USUARIO, DB_SENHA);
      $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e) {
      $logMensagem = str_repeat("-", 150) . PHP_EOL . PHP_EOL;
      $logMensagem .= date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
      $logMensagem .= 'Erro: ' . $e->getMessage() . PHP_EOL . PHP_EOL;

      error_log($logMensagem, 3, '../app/logs/database_' . date('Y-m-d') . '.log');
    }
  }

  public function __destruct()
  {
    $this->conexao = null;
  }

  public function iniciar()
  {
    return $this->conexao->beginTransaction();
  }

  public function operacoes($sql, $parametros = [])
  {
    try {

      if ($this->conexao == null) {
        throw new Exception('Sem conexão com o banco de dados');
      }

      $stmt = $this->conexao->prepare($sql);

      if ($parametros) {
        $indice = 1;
        foreach ($parametros as $chave => $linha):
          $type = PDO::PARAM_STR;

          if (is_int($linha)) {
            $type = PDO::PARAM_INT;
          }

          $stmt->bindValue($indice++, $linha, $type);
        endforeach;
      }

      $stmt->execute();

      if (substr($sql, 0, 6) == 'SELECT') {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      $ultimoId = $this->conexao->lastInsertId();
      $linhasAfetadas = $stmt->rowCount();

      if ($ultimoId) {
        return ['id' => $ultimoId];
      }
      elseif ($linhasAfetadas) {
        return ['linhasAfetadas' => $linhasAfetadas];
      }
      else {
        return [];
      }
    }
    catch (Exception $e){
      $sqlFormatado = $sql;
      foreach ($parametros as $valor) :

        if ($valor == '') {
          $valor = '""';
        }

        $valorFormatado = strip_tags(is_int($valor) ? $valor : $valor);
        $sqlFormatado = preg_replace('/\?/', $valorFormatado, $sqlFormatado, 1);
      endforeach;

      $logMensagem = str_repeat("-", 150) . PHP_EOL . PHP_EOL;
      $logMensagem .= date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
      $logMensagem .= 'Consulta: ' . $sqlFormatado . PHP_EOL . PHP_EOL;
      $logMensagem .= 'Erro: ' . $e->getMessage() . PHP_EOL . PHP_EOL;

      error_log($logMensagem, 3, '../app/logs/database_' . date('Y-m-d') . '.log');

      if (preg_match("/Duplicate entry '([^']+)' for key '([^']+)'/", $e->getMessage(), $matches)) {
        $tabelaColuna = $matches[2] ?? '';
        $tabelaColunaArray = explode('.', $tabelaColuna);
        $coluna = $tabelaColunaArray[1] ?? 'campo';

        $retorno = [
          'erro' => [
            'codigo' => 400,
            'mensagem' => $coluna . ' já cadastrado',
          ],
        ];

        return $retorno;
      }

      return ['erro' => false];
    }
  }
}