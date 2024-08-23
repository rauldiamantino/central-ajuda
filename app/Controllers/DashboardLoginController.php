<?php
namespace app\Controllers;
use app\Models\LoginModel;

class DashboardLoginController extends DashboardController
{
  protected $loginModel;
  protected $visao;

  public function __construct()
  {
    parent::__construct();
    $this->loginModel = new LoginModel();
  }

  public function loginVer()
  {
    if ($this->usuarioLogadoId > 0) {
      header('Location: /dashboard/artigos');
      exit();
    }

    $this->visao->variavel('titulo', 'Login');
    $this->visao->variavel('pagLogin', true);
    $this->visao->renderizar('/login/index');
  }

  public function login()
  {
    $json = $this->receberJson();
    $resultado = $this->loginModel->login($json);

    if (isset($resultado['ok'])) { 
      header('Location: /dashboard/artigos');
      exit();
    }

    $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';
    header('Location: /login');
    exit();
  }

  public function logout()
  {
    $_SESSION = [];
    session_destroy();

    // Remove o cookie da sessão
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();

      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }

    header('Location: /login');
    exit();
  }
}