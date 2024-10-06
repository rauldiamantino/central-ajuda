<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;

class DatabaseFirebaseComponent extends DashboardController
{
  public function credenciais()
  {
    $credenciais = [
      'firebase' => [
        'apiKey' => 'AIzaSyBXAg4u_hFmkaEaqifkknJaD4Lnx42EvHE',
        'authDomain' => 'central-ajuda-5f40a.firebaseapp.com',
        'projectId' => 'central-ajuda-5f40a',
        'storageBucket' => 'central-ajuda-5f40a.appspot.com',
        'messagingSenderId' => '83629854813',
        'appId' => '1:83629854813:web:3c99764aef3aba36a27db4'
      ],
    ];

    if ($this->acessoPermitido() == false) {
      $this->responderJson('Acesso negado', 403);
    }

    $this->responderJson($credenciais);
  }
}