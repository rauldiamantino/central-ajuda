<?php
require '../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\Core\Cache;
use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;

$sessaoUsuario = new SessaoUsuario();
$roteador = new Roteador();

$roteador->rotear();
