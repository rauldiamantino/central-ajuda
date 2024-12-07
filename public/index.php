<?php
require '../vendor/autoload.php';

use app\Core\Cache;
use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;

// Sentry
if (! HOST_LOCAL) {
  \Sentry\init([
    'dsn' => SENTRY_TOKEN,
    'traces_sample_rate' => 1.0,
  ]);
}

// $sessaoUsuario = new SessaoUsuario();
// $debug = $sessaoUsuario->buscar('debug');

$roteador = new Roteador();
$roteador->rotear();
