<?php
require '../vendor/autoload.php';

use app\Core\Cache;
use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;
use app\Controllers\Components\PagamentoAsaasComponent;

// Rollbar
if (! HOST_LOCAL) {
  $config = array(
    'access_token' => ROLLBAR_TOKEN,
    'environment' => 'production',
  );

  \Rollbar\Rollbar::init($config);
}

// $sessaoUsuario = new SessaoUsuario();
// $roteador = new Roteador();

// $roteador->rotear();

$asaas = new PagamentoAsaasComponent();
$criarAssinatura = $asaas->criarAssinatura();
die;