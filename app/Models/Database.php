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

      if (HOST_LOCAL) {
        registrarLog('database-conexao', $log);
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
      foreach ($parametros as $index => $valor):
        $placeholder = ':val' . ($index + 1);
        $sqlFormatado = preg_replace('/\?/', $placeholder, $sqlFormatado, 1);
      endforeach;

      foreach ($parametros as $index => $valor):

        if (is_null($valor)) {
          $valorFormatado = 'NULL';
        }
        elseif ($valor === '') {
          $valorFormatado = "''";
        }
        else {
          $valorFormatado = is_int($valor) ? (int) $valor : ("'" . addslashes(strip_tags((string)$valor)) . "'");
        }

        $sqlFormatado = str_replace(':val' . ($index + 1), $valorFormatado, $sqlFormatado);
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
    }

    $log = [
      'sql' => $sqlFormatado,
      'resposta' => $resposta,
    ];

    if ($erro) {
      $log['erro'] = $erro;
    }

    if (HOST_LOCAL) {
      registrarLog('database-operacoes', $log);
    }

    return $resposta;
  }
}