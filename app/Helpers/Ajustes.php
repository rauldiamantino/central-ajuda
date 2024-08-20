<?php
namespace app\Helpers;

use app\Models\Model;

class Ajustes
{
  public static function nome(string $nome)
  {
    $ajusteModel = new Model('Ajuste');

    $condicoes = [
      'Ajuste.nome' => $nome,
    ];

    $colunas = [
      'Ajuste.ativo',
    ];

    $resultado = $ajusteModel->condicao($condicoes)
                             ->buscar($colunas);

    return $resultado;
  }
}
