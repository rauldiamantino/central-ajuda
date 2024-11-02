<?php
namespace app\Core;

class SessaoUsuario
{
  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start([
        'cookie_lifetime' => 0,
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'use_strict_mode' => true,
        'use_only_cookies' => true,
        'use_trans_sid' => false,
      ]);
    }
  }

  public function definir($chave, $valor, $array = false)
  {
    if ($array) {
      $_SESSION[ $chave ][] = $valor;
    }
    else {
      $_SESSION[ $chave ] = $valor;
    }
  }

  public function buscar($chave)
  {
    return $_SESSION[ $chave ] ?? null;
  }

  public function apagar($caminho)
  {
    if (is_string($caminho)) {
      $caminho = [ $caminho ];
    }

    $sessao = $_SESSION;
    $ultimaChave = array_pop($caminho);

    foreach ($caminho as $chave):

      if (!isset($sessao[ $chave ])) {
        return;
      }

      $sessao = $sessao[ $chave ];
    endforeach;

    if (isset($sessao) and isset($sessao[ $ultimaChave ])) {
      unset($_SESSION[ $ultimaChave ]);
    }
  }

  public function destruir()
  {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
  }

  public function regenerarId()
  {
    session_regenerate_id(true);
  }
}