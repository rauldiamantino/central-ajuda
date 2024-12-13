<?php
namespace app\Core;

class SessaoUsuario
{
  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      $lifetime = 14400; // 4 horas
      $cookieParams = session_get_cookie_params();

      ini_set('session.gc_maxlifetime', $lifetime);
      ini_set('session.cookie_lifetime', $lifetime);

      $dominio = '.360help.com.br';

      if (HOST_LOCAL) {
        $dominio = $_SERVER['SERVER_NAME'];
      }

      $dominio = $_SERVER['SERVER_NAME'];

      session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => $cookieParams['path'],
        'domain' => $dominio,
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict', // Ou 'Strict'
      ]);

      session_start();
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
