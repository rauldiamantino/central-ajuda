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
    $this->visao->variavel('metaTitulo', 'Cadastro - 360Help');
    $this->visao->variavel('pagCadastro', true);
    $this->visao->renderizar('/cadastro/index');
  }

  public function adicionar()
  {
    $dados = $this->receberJson();
    $resultado = $this->cadastroModel->validarCampos($dados);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/cadastro', $resultado['erro']);
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
    $usuarioId = $this->cadastroModel->gerarUsuarioPadrao($resultado);

    if (intval($usuarioId) < 1) {
      $this->cadastroModel->apagarEmpresa($empresaId);
      $this->cadastroModel->apagarAssinatura($assinaturaId, $empresaId);

      $this->redirecionarErro('/cadastro', 'Erro ao cadastrar usuário (C500#USR)');
    }

    $usuarioSuporte = $this->cadastroModel->gerarUsuarioSuporte($resultado);

    if (intval($usuarioSuporte) < 1) {
      $this->cadastroModel->apagarEmpresa($empresaId);
      $this->cadastroModel->apagarAssinatura($assinaturaId, $empresaId);
      $this->cadastroModel->apagarUsuario($empresaId, $usuarioId);

      $this->redirecionarErro('/cadastro', 'Erro ao cadastrar usuário (C500#USR#SUP)');
    }

    $this->loginController->login($dados);
  }
}