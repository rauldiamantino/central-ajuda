<?php
namespace app\Core;

class SessaoUsuario
{
  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start([
        'cookie_lifetime' => 18400, // 4 horas
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

    $sessao = &$_SESSION;
    $ultimaChave = array_pop($caminho);

    foreach ($caminho as $chave) {

      if (! isset($sessao[ $chave ])) {
        return;
      }

      $sessao = &$sessao[ $chave ];
    }

    if (isset($sessao[ $ultimaChave ])) {
      unset($sessao[ $ultimaChave ]);
    }
  }

  public function destruir()
  {
    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
  }

  public function regenerarId()
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_regenerate_id(true);
    }
  }
}