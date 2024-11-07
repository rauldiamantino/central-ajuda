<?php
require '../vendor/autoload.php';

use app\Core\Cache;
use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;
use \Rollbar\Rollbar;

$sessaoUsuario = new SessaoUsuario();
$roteador = new Roteador();

$roteador->rotear();

// Rollbar
if (! HOST_LOCAL) {
  $set_exception_handler = false;
  $set_error_handler = false;
  Rollbar::init($config, $set_exception_handler, $set_error_handler);

  $config = array(
    'access_token' => ROLLBAR_TOKEN,
    'environment' => 'production',
  );
}