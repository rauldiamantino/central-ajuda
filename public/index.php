<?php
require_once '../vendor/autoload.php';

use app\Roteamento\Roteador;
class_alias('app\Core\Helper', 'Helper');

// Sentry
if (! HOST_LOCAL) {
  \Sentry\init([
    'dsn' => SENTRY_TOKEN,
    'traces_sample_rate' => 1.0,
  ]);
}

$roteador = new Roteador();
$roteador->rotear();
// Testes
// self::iniciarMemcached();
// self::$memcached->flush();