<?php
require '../vendor/autoload.php';

use app\Roteamento\Roteador;

session_start();

$roteador = new Roteador();
$roteador->rotear();