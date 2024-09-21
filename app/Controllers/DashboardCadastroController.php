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
    if ($this->usuarioLogado['id'] > 0) {
      header('Location: /' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
      exit();
    }

    $this->visao->variavel('titulo', 'Cadastro');
    $this->visao->variavel('pagCadastro', true);
    $this->visao->renderizar('/cadastro/index');
  }

  public function cadastroSucessoVer()
  {
    if ($this->usuarioLogado['id'] > 0 and $this->usuarioLogado['empresaAtivo'] == 1) {
      $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos');
    }

    $protocolo = $this->sessaoUsuario->buscar('protocolo');
    $this->sessaoUsuario->apagar('protocolo');

    if (empty($protocolo)) {
      $this->redirecionar('/cadastro');
    }

    $this->visao->variavel('protocolo', $protocolo);
    $this->visao->variavel('titulo', 'Cadastro');
    $this->visao->variavel('pagCadastro', true);
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

    // Somente para gerar empresa
    unset($resultado['subdominio']);

    $resultado = array_merge($resultado, ['empresa_id' => $empresaId]);
    $usuario = $this->cadastroModel->gerarUsuarioPadrao($resultado);

    if (isset($usuario['erro']['mensagem'])) {
      $this->redirecionarErro('/cadastro', $usuario['erro']);
    }

    if (intval($usuario) < 1) {
      $this->redirecionarErro('/cadastro', 'Erro ao cadastrar usuário (C500#USR)');
    }

    $this->sessaoUsuario->definir('protocolo', date('YmdHis') . '#' . $empresaId);
    $this->redirecionar('/cadastro/sucesso');
  }
}