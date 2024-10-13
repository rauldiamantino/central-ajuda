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
      else {
        $ultimoId = $this->conexao->lastInsertId();
        $linhasAfetadas = $stmt->rowCount();

        if ($ultimoId) {
          $resposta = ['id' => $ultimoId];
        }
        elseif ($linhasAfetadas) {
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