<?php
namespace app\Controllers;
use app\Models\DashboardCadastroModel;

class DashboardCadastroController extends DashboardController
{
  private $cadastroModel;
  private $loginController;

  public function __construct()
  {
    parent::__construct();

    $this->cadastroModel = new DashboardCadastroModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->loginController = new DashboardLoginController();
  }

  public function cadastroVer()
  {
    if ($this->usuarioLogado['id'] > 0) {
      header('Location: ' . baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard'));
      exit();
    }

    $this->visao->variavel('metaTitulo', 'Cadastro - 360Help');
    $this->visao->variavel('pagCadastro', true);
    $this->visao->renderizar('/cadastro/index');
  }

  public function cadastroSucessoVer()
  {
    if ($this->usuarioLogado['id'] > 0 and $this->usuarioLogado['empresaAtivo'] == ATIVO) {
      $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard');
    }

    $protocolo = $this->sessaoUsuario->buscar('protocolo');
    $this->sessaoUsuario->apagar('protocolo');

    if (empty($protocolo)) {
      $this->redirecionar('/login');
    }

    $this->visao->variavel('protocolo', $protocolo);
    $this->visao->variavel('metaTitulo', 'Cadastro - 360Help');
    $this->visao->variavel('pagCadastro', true);
    $this->visao->variavel('pagCadastroSucesso', true);
    $this->visao->variavel('paginaMenuLateral', 'cadastro');
    $this->visao->renderizar('/cadastro/sucesso');
  }

  public function adicionar()
  {
    $dados = $this->receberJson();
    $resultado = $this->cadastroModel->validarCampos($dados);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/cadastro', $resultado['erro']);
    }

    $usuarioExiste = $this->cadastroModel->usuarioExiste($resultado['email']);

    if ($usuarioExiste) {
      $this->redirecionarErro('/cadastro', 'Email já cadastrado');
    }

    $empresaId = $this->cadastroModel->gerarEmpresa($resultado['subdominio']);

    if (intval($empresaId) < 1) {
      $this->redirecionarErro('/cadastro', 'Erro ao realizar cadastro (C500#EMP)');
    }

    $assinaturaId = $this->cadastroModel->gerarAssinatura($empresaId);

    if (intval($assinaturaId) < 1) {
      $this->redirecionarErro('/cadastro', 'Erro ao realizar cadastro (C500#EMP#ASS)');
    }

    // Somente para gerar empresa
    unset($resultado['subdominio']);

    $resultado = array_merge($resultado, ['empresa_id' => $empresaId]);
    $usuario = $this->cadastroModel->gerarUsuarioPadrao($resultado);

    if (intval($usuario) < 1) {
      $this->cadastroModel->apagarEmpresa($empresaId);
      $this->cadastroModel->apagarAssinatura($assinaturaId, $empresaId);

      $this->redirecionarErro('/cadastro', 'Erro ao cadastrar usuário (C500#USR)');
    }

    $this->loginController->login($dados);
  }
}