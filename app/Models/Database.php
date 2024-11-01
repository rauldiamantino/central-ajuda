<?php
namespace app\Models;

use Exception;
use \PDO;

class Database
{
  private $conexao;

  public function __construct()
  {
    $host = getenv('POSTGRES_HOST');
    $dbname = getenv('POSTGRES_DATABASE');
    $user = getenv('POSTGRES_USER');
    $password = getenv('POSTGRES_PASSWORD');
    $port = getenv('POSTGRES_PORT');

    try {

      if (SGBD == MYSQL) {
        $host = getenv('MYSQL_HOST');
        $dbname = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASSWORD');

        if (HOST_LOCAL) {
          $host = getenv('MYSQL_HOST_DEV');
          $dbname = getenv('MYSQL_DATABASE_DEV');
          $user = getenv('MYSQL_USER_DEV');
          $password = getenv('MYSQL_PASSWORD_DEV');
        }

        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8';
        $this->conexao = new PDO($dsn, $user, $password);
      }
      elseif (SGBD == POSTGRES) {
        $dsn = 'pgsql:host=' . $host . ';dbname=' . $dbname . ';port=' . $port;
        $this->conexao = new PDO($dsn, $user, $password);
      }
      else {
        throw new Exception('Banco de dados invalido');
      }

      $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e) {

      if (HOST_LOCAL) {
        registrarLog('database-conexao', ['erro' => $e->getMessage()]);
      }
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
    $erro = [];
    $resposta = [];
    $sqlFormatado = $sql;

    if ($parametros) {
      foreach ($parametros as $valor) :

        if ($valor == '') {
          $valor = '""';
        }

        $valorFormatado = strip_tags(is_int($valor) ? $valor : $valor);

        if (is_string($valorFormatado)) {
          $valorFormatado = '\'' . $valorFormatado . '\'';
        }
        elseif (is_int($valorFormatado)) {
          $valorFormatado = (int) $valorFormatado;
        }

        $sqlFormatado = preg_replace('/\?/', $valorFormatado, $sqlFormatado, 1);
      endforeach;
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
      elseif (stripos($sql, 'INSERT') === 0) {
        $ultimoId = $this->conexao->lastInsertId();

        if ($ultimoId) {
          $resposta = ['id' => $ultimoId];
        }
      }
      else {
        $linhasAfetadas = $stmt->rowCount();

        if ($linhasAfetadas) {
          $resposta = ['linhasAfetadas' => $linhasAfetadas];
        }
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
    }

    if (HOST_LOCAL) {
      registrarLog('database', ['sql' => $sqlFormatado, 'resposta' => $resposta, 'erro' => $erro]);
    }

    return $resposta;
  }
}