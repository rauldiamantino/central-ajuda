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
    if ($this->usuarioLogadoId > 0) {
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
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
    $this->usuarioLogadoId = $resultado['ok']['id'];
    $this->usuarioLogadoEmail = $resultado['ok']['email'];
    $this->usuarioLogadoNivel = $resultado['ok']['nivel'];
    $this->usuarioLogadoPadrao = $resultado['ok']['padrao'];
    $this->usuarioLogadoEmpresaId = $resultado['ok']['empresa_id'];
    $this->usuarioLogadoEmpresaAtivo = $resultado['ok']['Empresa.ativo'];
    $this->usuarioLogadoSubdominio = $resultado['ok']['Empresa.subdominio'];


    header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
    exit();
  }

  public function logout()
  {
    $this->sessaoUsuario->apagar('usuario');

    header('Location: /login');
    exit();
  }
}