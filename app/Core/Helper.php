<?php
namespace app\Core;

use app\Controllers\DashboardAjusteController;

class Helper
{
  public static function ajuste(string $nome)
  {
    $ajusteController = new DashboardAjusteController();
    $buscar = $ajusteController->buscar($nome);

    return $buscar[0]['Ajuste']['valor'] ?? '';
  }
}