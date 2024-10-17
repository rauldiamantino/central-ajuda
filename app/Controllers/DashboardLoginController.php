<?php

namespace app\Controllers;
use app\Models\DashboardLoginModel;
use app\Models\DashboardEmpresaModel;

class DashboardLoginController extends DashboardController
{
  protected $loginModel;
  protected $empresaModel;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->loginModel = new DashboardLoginModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->empresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function loginVer()
  {
    if ($this->usuarioLogado['id'] > 0) {
      header('Location: ' . baseUrl('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos'));
      exit();
    }

    $this->visao->variavel('titulo', 'Login');
    $this->visao->variavel('pagLogin', true);
    $this->visao->variavel('paginaMenuLateral', 'login');
    $this->visao->renderizar('/login/index');
  }

  public function loginSuporteVer(int $id = 0)
  {
    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      header('Location: ' . baseUrl('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos'));
      exit();
    }

    $colunas = [
      'Empresa.id',
      'Empresa.subdominio',
      'Empresa.ativo',
      'Empresa.id',
    ];

    $empresas = $this->empresaModel->selecionar($colunas)
                                   ->executarConsulta();

    if (isset($empresas['erro'])) {
      $this->sessaoUsuario->apagar('usuario');
      $this->redirecionarErro('/login', $empresas['erro']);
    }

    if (! is_array($empresas)) {
      $empresas = [];
    }

    if ($id) {
      // Empresa selecionada
      foreach($empresas as $linha):

        if (! isset($linha['Empresa']['id']) or (int) $linha['Empresa']['id'] == 0) {
          continue;
        }

        if (! isset($linha['Empresa']['subdominio']) or empty($linha['Empresa']['subdominio'])) {
          continue;
        }

        if (! isset($linha['Empresa']['id']) or empty($linha['Empresa']['id'])) {
          continue;
        }

        if ($id != $linha['Empresa']['id']) {
          continue;
        }

        // Aplica empresa na sessão
        $this->usuarioLogado['empresaId'] = $linha['Empresa']['id'];
        $this->usuarioLogado['empresaAtivo'] = $linha['Empresa']['ativo'];
        $this->usuarioLogado['subdominio'] = $linha['Empresa']['subdominio'];
        $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);

        $this->redirecionar('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos');
      endforeach;

      $this->redirecionarErro('/login/suporte', 'Empresa não encontrada');
    }

    $this->visao->variavel('titulo', 'Login Suporte');
    $this->visao->variavel('pagLoginSuporte', true);
    $this->visao->variavel('empresas', $empresas);
    $this->visao->variavel('paginaMenuLateral', 'login');
    $this->visao->renderizar('/login/suporte/index');
  }

  public function login()
  {
    $json = $this->receberJson();
    $resultado = $this->loginModel->login($json);

    // Acesso será liberado somente via suporte
    if (isset($resultado['bloqueio']) and $resultado['bloqueio']) {
      $this->sessaoUsuario->definir('acessoBloqueado-' . $resultado['bloqueio'], true);
    }

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

    if ($this->usuarioLogado['empresaId'] == 1 and $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      $this->redirecionar('/login/suporte');
    }

    $this->redirecionar('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos');
  }

  public function logout()
  {
    $this->sessaoUsuario->apagar('usuario');

    header('Location: ' . baseUrl('/login'));
    exit();
  }
}