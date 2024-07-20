<?php
require '../vendor/autoload.php';

use app\Roteamento\Roteador;
$roteador = new Roteador();
$roteador->rotear();