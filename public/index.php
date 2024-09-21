<?php
require './vendor/autoload.php';

use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;

$sessaoUsuario = new SessaoUsuario();
$roteador = new Roteador();

$roteador->rotear();