<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;

class FirebaseComponent extends DashboardController
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
      header('Content-Type: application/json');
      http_response_code(403);
      echo json_encode(['erro' => 'Acesso negado']);
      exit;
    }

    header('Content-Type: application/json');
    echo json_encode($credenciais);
    exit;
  }

  private function acessoPermitido()
  {
    if (! isset($_SERVER['HTTP_REFERER'])) {
      return false;
    }

    if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== DB_HOST) {
      return false;
    }

    return true;
  }
}