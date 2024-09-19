<?php
namespace app\Controllers;
use app\Models\DashboardCadastroModel;

class DashboardCadastroController extends DashboardController
{
  private $cadastroModel;

  public function __construct()
  {
    parent::__construct();

    $this->cadastroModel = new DashboardCadastroModel();
  }

  public function cadastroVer()
  {
    if ($this->buscarUsuarioLogado('id') > 0) {
      header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/artigos');
      exit();
    }

    $this->visao->variavel('titulo', 'Cadastro');
    $this->visao->variavel('pagCadastro', true);
    $this->visao->renderizar('/cadastro/index');
  }

  public function cadastroSucessoVer()
  {
    if ($this->buscarUsuarioLogado('id') > 0 and $this->buscarUsuarioLogado('empresa_ativo') == 1) {
      header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/artigos');
      exit();
    }

    if (! isset($_SESSION['ok']) or $_SESSION['ok'] != 'Cadastro realizado com sucesso!') {
      header('Location: /cadastro');
      exit();
    }

    $this->visao->variavel('titulo', 'Cadastro');
    $this->visao->variavel('pagCadastro', true);
    $this->visao->renderizar('/cadastro/sucesso');
  }

  public function adicionar()
  {
    $dados = $this->receberJson();
    $resultado = $this->cadastroModel->validarCampos($dados);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';
      header('Location: /cadastro');
      exit();
    }

    $usuarioExiste = $this->cadastroModel->usuarioExiste($resultado['email']);

    if ($usuarioExiste) {
      $_SESSION['erro'] = 'Email já cadastrado';
      header('Location: /cadastro');
      exit();
    }

    $empresaId = $this->cadastroModel->gerarEmpresa($resultado['subdominio']);

    if (intval($empresaId) < 1) {
      $_SESSION['erro'] = 'Erro ao realizar cadastro (C500#EMP)';
      header('Location: /cadastro');
      exit();
    }

    // Somente para gerar empresa
    unset($resultado['subdominio']);

    $resultado = array_merge($resultado, ['empresa_id' => $empresaId]);
    $usuario = $this->cadastroModel->gerarUsuarioPadrao($resultado);

    if (isset($usuario['erro']['mensagem'])) {
      $_SESSION['erro'] = $usuario['erro']['mensagem'];
      header('Location: /cadastro');
      exit();
    }

    if (intval($usuario) < 1) {
      $_SESSION['erro'] = 'Erro ao cadastrar usuário (C500#USR)';
      header('Location: /cadastro');
      exit();
    }

    $_SESSION['ok'] = 'Cadastro realizado com sucesso!';
    header('Location: /cadastro/sucesso');
    exit();
  }
}