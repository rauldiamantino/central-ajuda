<?php
namespace app\Controllers;
use app\Models\DashboardCadastroModel;
use app\Controllers\Components\CloudflareComponent;

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
    $subdominio = $resultado['subdominio'];
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

    if (! HOST_LOCAL) {
      $this->gerarSubdominio($subdominio);
    }

    $this->redirecionarSucesso('/login', 'Cadastro realizado com sucesso');

    // Revisar em produção
    // $this->loginController->login($dados);
  }

  private function gerarSubdominio(string $subdominio): bool
  {
    // $cloudflare = new CloudflareComponent();
    // return $cloudflare->criarSubdominio($subdominio);
  }
}