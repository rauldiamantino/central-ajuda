<?php

namespace app\Controllers;
use app\Models\DashboardLoginModel;
use app\Models\DashboardEmpresaModel;

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

  public function loginSuporteVer(int $id = 0)
  {
    if ($this->usuarioLogado['padrao'] > 0) {
      header('Location: /' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
      exit();
    }

    $empresas = $this->loginModel->buscarEmpresas();

    if (isset($empresas['erro'])) {
      $this->sessaoUsuario->apagar('usuario');
      $this->redirecionarErro('/login', $empresas['erro']);
    }

    $empresas = $empresas['ok'];

    if (! is_array($empresas)) {
      $empresas = [];
    }

    if ($id) {
      // Empresa selecionada
      foreach($empresas as $linha):

        if (! isset($linha['id']) or (int) $linha['id'] == 0) {
          continue;
        }

        if (! isset($linha['subdominio']) or empty($linha['subdominio'])) {
          continue;
        }

        if ($id != $linha['id']) {
          continue;
        }

        $this->usuarioLogado['empresaId'] = $linha['id'];
        $this->usuarioLogado['subdominio'] = $linha['subdominio'];
        $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);

        $this->redirecionar('/' . $linha['subdominio'] . '/dashboard/artigos');
      endforeach;

      $this->redirecionarErro('/login/suporte', 'Empresa não encontrada');
    }

    $this->visao->variavel('titulo', 'Login Suporte');
    $this->visao->variavel('pagLoginSuporte', true);
    $this->visao->variavel('empresas', $empresas);
    $this->visao->renderizar('/login/suporte/index');
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

    if ($this->usuarioLogado['empresaId'] == 1 and $this->usuarioLogado['nivel'] == 0) {
      $this->redirecionar('/login/suporte');
    }

    $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
  }

  public function logout()
  {
    $this->sessaoUsuario->apagar('usuario');

    header('Location: /login');
    exit();
  }
}