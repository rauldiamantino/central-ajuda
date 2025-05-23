<?php
namespace app\Models;

use \PDO;
use Exception;

class Database
{
  private $conexao;

  public function __construct()
  {
    try {
      $host = MYSQL_HOST;
      $dbname = MYSQL_NOME;
      $user = MYSQL_USUARIO;
      $password = MYSQL_SENHA;

      $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4';
      $this->conexao = new PDO($dsn, $user, $password);

      $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e) {
      $log['erro'] = $e->getMessage();

      registrarSentry($e);
      registrarLog('database-conexao', $log);
    }
  }

  public function __destruct()
  {
    $this->conexao = null;
  }

  public function iniciar()
  {
    try {
        $this->conexao->beginTransaction();
    }
    catch (Exception $e) {
      $this->registrarErro($e);
      throw $e;
    }
  }

  public function commit()
  {
    try {
        $this->conexao->commit();
    }
    catch (Exception $e) {
      $this->registrarErro($e);
      throw $e;
    }
  }

  public function rollback()
  {
    try {
        $this->conexao->rollBack();
    }
    catch (Exception $e) {
      $this->registrarErro($e);
      throw $e;
    }
  }

  private function registrarErro(Exception $e)
  {
    $resposta = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => 'Essa solicitação não pode ser atendida',
      ],
    ];

    $erro = [
      'cod' => $e->getCode(),
      'msg' => $e->getMessage(),
    ];

    registrarSentry($e);

    $log = [
      'sql' => isset($sqlFormatado) ? $sqlFormatado : 'Não disponível',
      'resposta' => $resposta,
      'erro' => $erro,
    ];

    registrarLog('database-operacoes', $log);
  }

  public function operacoes($sql, $parametros)
  {
    $erro = [];
    $resposta = [];
    $sqlFormatado = $sql;

    if ($parametros) {
      $temp = $parametros;
      $sqlFormatado = preg_replace_callback('/\?/', function($matches) use (&$temp) {
        $parametro = array_shift($temp);

        if (is_string($parametro)) {
          return "'" . addslashes($parametro) . "'";
        }
        elseif (is_int($parametro) or is_float($parametro)) {
          return $parametro;
        }
        elseif (is_null($parametro)) {
          return 'NULL';
        }
      }, $sqlFormatado);

      $sqlFormatado = preg_replace('/\s+/', ' ', $sqlFormatado);
      $sqlFormatado = trim($sqlFormatado);
      $temp = null;
    }

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
        $resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
      elseif (stripos($sql, 'INSERT') !== false) {
        $ultimoId = $this->conexao->lastInsertId();

        if ($ultimoId) {
          $resposta = ['id' => $ultimoId];
        }
      }
      else {
        $linhasAfetadas = $stmt->rowCount();
        $resposta = ['linhasAfetadas' => $linhasAfetadas];
      }
    }
    catch (Exception $e){
      $resposta = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Essa solicitação não pode ser atendida',
        ],
      ];

      $erro = [
        'cod' => $e->getCode(),
        'msg' => $e->getMessage(),
      ];

      registrarSentry($e);
    }

    $log = [
      'sql' => $sqlFormatado,
      'resposta' => $resposta,
    ];

    if ($erro) {
      $log['erro'] = $erro;
    }

    registrarLog('database-operacoes', $log);

    return $resposta;
  }
}