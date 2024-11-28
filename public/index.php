<?php
require '../vendor/autoload.php';

use app\Core\Cache;
use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;
use app\Controllers\Components\PagamentoAsaasComponent;

// Rollbar
if (! HOST_LOCAL) {
  $config = [
    'access_token' => ROLLBAR_TOKEN,
    'environment' => 'production',
  ];

  \Rollbar\Rollbar::init($config);
}

$sessaoUsuario = new SessaoUsuario();
$sessaoUsuario->apagar('debug');

$roteador = new Roteador();
$roteador->rotear();

// Obtém o contador de requisições da sessão
// $contadorRequisicoes = (int) $sessaoUsuario->buscar('contadorReq');
// $contadorRequisicoes++;
// $sessaoUsuario->definir('contadorReq', $contadorRequisicoes);
// registrarLog('requisicoesQtde', $contadorRequisicoes);