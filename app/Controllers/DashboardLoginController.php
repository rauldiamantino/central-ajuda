<?php
namespace app\Controllers;
use app\Models\DashboardLoginModel;

class DashboardLoginController extends DashboardController
{
  protected $loginModel;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->loginModel = new DashboardLoginModel();
  }

  public function loginVer()
  {
    if ($this->usuarioLogado['id'] > 0) {
      header('Location: /' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
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

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->apagar('usuario');

      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';
      header('Location: /login');
      exit();
    }

    // Aplica login
    $usuarioLogin = [
      'id' => $resultado['ok']['id'],
      'nome' => $resultado['ok']['nome'],
      'email' => $resultado['ok']['email'],
      'empresa_id' => $resultado['ok']['empresa_id'],
      'empresa_ativo' => $resultado['ok']['Empresa.ativo'],
      'subdominio' => $resultado['ok']['Empresa.subdominio'],
      'nivel' => $resultado['ok']['nivel'],
      'padrao' => $resultado['ok']['padrao'],
    ];

    $this->sessaoUsuario->definir('usuario', $usuarioLogin);

    // Uso imediato
    $this->usuarioLogado['id'] = $resultado['ok']['id'];
    $this->usuarioLogado['email'] = $resultado['ok']['email'];
    $this->usuarioLogado['nivel'] = $resultado['ok']['nivel'];
    $this->usuarioLogado['padrao'] = $resultado['ok']['padrao'];
    $this->usuarioLogado['empresaId'] = $resultado['ok']['empresa_id'];
    $this->usuarioLogado['empresaAtivo'] = $resultado['ok']['Empresa.ativo'];
    $this->usuarioLogado['subdominio'] = $resultado['ok']['Empresa.subdominio'];


    header('Location: /' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
    exit();
  }

  public function logout()
  {
    $this->sessaoUsuario->apagar('usuario');

    header('Location: /login');
    exit();
  }
}