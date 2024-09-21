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
      $this->redirecionarErro('/login', $resultado['erro']);
    }

    $this->usuarioLogado = [
      'id' => $resultado['ok']['id'],
      'nome' => $resultado['ok']['nome'],
      'email' => $resultado['ok']['email'],
      'empresaId' => $resultado['ok']['empresa_id'],
      'empresaAtivo' => $resultado['ok']['Empresa.ativo'],
      'subdominio' => $resultado['ok']['Empresa.subdominio'],
      'nivel' => $resultado['ok']['nivel'],
      'padrao' => $resultado['ok']['padrao'],
      'tentativasLogin' => $resultado['ok']['tentativas_login'],
    ];

    $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    $this->sessaoUsuario->regenerarId();

    $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
  }

  public function logout()
  {
    $this->sessaoUsuario->apagar('usuario');

    header('Location: /login');
    exit();
  }
}