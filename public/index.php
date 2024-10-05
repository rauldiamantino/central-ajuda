<?php
require './vendor/autoload.php';

use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;
use app\Controllers\DashboardEmpresaController;

$sessaoUsuario = new SessaoUsuario();
$roteador = new Roteador();

$roteador->rotear();