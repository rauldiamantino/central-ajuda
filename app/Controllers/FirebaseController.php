<?php
namespace app\Controllers;

class FirebaseController extends Controller
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

    header('Content-Type: application/json');
    echo json_encode($credenciais);
    exit();
  }
}