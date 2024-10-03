<?php
namespace app\Controllers;
use app\Models\DashboardEmpresaModel;
use app\Controllers\PagamentoStripeController;

class DashboardEmpresaController extends DashboardController
{
  protected $empresaModel;
  protected $pagamentoStripe;

  public function __construct()
  {
    parent::__construct();

    $this->empresaModel = new DashboardEmpresaModel();
    $this->pagamentoStripe = new PagamentoStripeController();
  }

  public function empresaEditarVer()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.nome',
      'Empresa.subdominio',
      'Empresa.cnpj',
      'Empresa.telefone',
      'Empresa.logo',
      'Empresa.sessao_stripe_id',
      'Empresa.assinatura_id',
      'Empresa.criado',
      'Empresa.modificado',
    ];

    $empresa = $this->empresaModel->buscar($colunas);

    if (isset($empresa['erro']) and $empresa['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', $empresa['erro']);
    }

    $respostaApi = [];
    $assinaturaId = $empresa[0]['Empresa.assinatura_id'] ?? '';

    if ($assinaturaId) {
      $respostaApi = $this->pagamentoStripe->buscarAssinatura($assinaturaId);
    }

    $this->visao->variavel('stripeAssinatura', $respostaApi);
    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('titulo', 'Editar empresa');
    $this->visao->renderizar('/empresa/index');
  }

  public function atualizar(int $id)
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
    }

    $colunas = [
      'Empresa.ativo',
    ];

    $empresa = $this->empresaModel->buscar($colunas);

    if (isset($empresa[0]['Empresa.ativo'])) {
      $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa.ativo'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Registro alterado com sucesso');
  }

  public function buscarEmpresa(string $subdominio = ''): array
  {
    return $this->empresaModel->buscarEmpresa($subdominio);
  }

  public function confirmarAssinatura(): void
  {
    $sessaoStripe = $_GET['sessao_stripe_id'] ?? '';

    if ($sessaoStripe and $this->usuarioLogado['empresaId']) {
      $assinaturaId = $this->pagamentoStripe->buscarAssinaturaAtiva($sessaoStripe);

      if ($assinaturaId) {
        $campos = [
          'ativo' => ATIVO,
          'sessao_stripe_id' => '',
          'assinatura_id' => $assinaturaId,
        ];

        $resultado = $this->empresaModel->atualizar($campos, $this->usuarioLogado['empresaId']);

        if (isset($resultado['erro'])) {
          $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
        }

        $colunas = [
          'Empresa.ativo',
        ];

        $empresa = $this->empresaModel->buscar($colunas);

        // Atualiza sessão
        if (isset($empresa[0]['Empresa.ativo'])) {
          $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa.ativo'];
          $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
        }

        $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura confirmada com sucesso');
      }
    }

    $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar');
  }

  public function reprocessarAssinatura(): void
  {
    $assinaturaId = $_GET['assinatura_id'] ?? '';

    if ($assinaturaId and $this->usuarioLogado['empresaId']) {
      $assinatura = $this->pagamentoStripe->buscarAssinatura($assinaturaId);
      $status = $assinatura['status'] ?? '';

      if (in_array($status, ['canceled', 'active'])) {
        $campos['ativo'] = INATIVO;

        if ($status == 'active') {
          $campos['ativo'] = ATIVO;
        }

        $resultado = $this->empresaModel->atualizar($campos, $this->usuarioLogado['empresaId']);

        if (isset($resultado['erro'])) {
          $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
        }

        $colunas = [
          'Empresa.ativo',
        ];

        $empresa = $this->empresaModel->buscar($colunas);

        // Atualiza sessão
        if (isset($empresa[0]['Empresa.ativo'])) {
          $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa.ativo'];
          $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
        }

        $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura reprocessada com sucesso<br> Status: ' . strtoupper($status));
      }
    }

    $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar');
  }
}