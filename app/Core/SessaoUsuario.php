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

    // Evitar sequestro de Sessão
    $this->verificarUserAgent();
    $this->verificarIp();
  }

  public function definir($chave, $valor)
  {
    $_SESSION[ $chave ] = $valor;
  }

  public function buscar($chave)
  {
    return $_SESSION[ $chave ] ?? null;
  }

  public function apagar($chave)
  {
    unset($_SESSION[ $chave ]);
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

  private function verificarUserAgent()
  {
    if (!isset($_SESSION['user_agent'])) {
      $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
    elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
      $this->destruir();
      exit('Sessão inválida');
    }
  }

  private function verificarIp()
  {
    if (! isset($_SESSION['user_ip'])) {
      $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    }
    elseif ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
      $this->destruir();
      exit('Sessão inválida');
    }
  }
}