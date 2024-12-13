<?php
require '../vendor/autoload.php';

use app\Roteamento\Roteador;

// Sentry
if (! HOST_LOCAL) {
  \Sentry\init([
    'dsn' => SENTRY_TOKEN,
    'traces_sample_rate' => 1.0,
  ]);
}

$roteador = new Roteador();
$roteador->rotear();