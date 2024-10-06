<?php
namespace app\Controllers;
use app\Models\DashboardEmpresaModel;
use app\Controllers\Components\PagamentoStripeComponent;

class DashboardEmpresaController extends DashboardController
{
  protected $empresaModel;
  protected $pagamentoStripe;

  public function __construct()
  {
    parent::__construct();

    $this->empresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->pagamentoStripe = new PagamentoStripeComponent();
  }

  public function empresaEditarVer()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos', 'Você não tem permissão para realizar esta ação.');
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
      $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos', $empresa['erro']);
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('titulo', 'Editar empresa');
    $this->visao->renderizar('/empresa/index');
  }

  public function buscarEmpresaSemId(string $coluna, string $valor = ''): array
  {
    if (empty($valor)) {
      return [];
    }

    return $this->empresaModel->buscarEmpresaSemId($coluna, $valor);
  }

  public function atualizar(int $id)
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar', $resultado['erro']);
    }

    $colunas = [
      'Empresa.ativo',
    ];

    $empresa = $this->empresaModel->buscar($colunas);

    if (isset($empresa[0]['Empresa.ativo'])) {
      $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa.ativo'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    $this->redirecionarSucesso('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar', 'Registro alterado com sucesso');
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
          $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar', $resultado['erro']);
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

        $this->redirecionarSucesso('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar', 'Assinatura confirmada com sucesso');
      }
    }

    $this->redirecionar('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar');
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
          $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar', $resultado['erro']);
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

        $this->redirecionarSucesso('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar', 'Assinatura reprocessada com sucesso<br> Status: ' . strtoupper($status));
      }
    }

    $this->redirecionar('/dashboard/' . $this->usuarioLogado['empresaId'] . '/empresa/editar');
  }

  public function confirmarAssinaturaWebhook(string $sessionId, string $assinaturaId): bool
  {
    $resultado = $this->empresaModel->buscarEmpresaSemId('sessao_stripe_id', $sessionId);
    $empresaId = intval($resultado[0]['Empresa.id'] ?? 0);

    if ($empresaId == 0) {
      return false;
    }

    $campos = [
      'sessao_stripe_id' => NULL,
      'assinatura_id' => $assinaturaId,
      'ativo' => ATIVO,
    ];

    $webhook = true;
    $resultado = $this->empresaModel->atualizar($campos, $empresaId, $webhook);

    if (! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] != 1) {
      return false;
    }

    return true;
  }

  public function cancelarAssinaturaWebhook(string $assinaturaId): bool
  {
    $resultado = $this->empresaModel->buscarEmpresaSemId('assinatura_id', $assinaturaId);
    $empresaId = intval($resultado[0]['Empresa.id'] ?? 0);

    if ($empresaId == 0) {
      return false;
    }

    $campos = [
      'ativo' => INATIVO,
    ];

    $webhook = true;
    $resultado = $this->empresaModel->atualizar($campos, $empresaId, $webhook);

    if (! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] != 1) {
      return false;
    }

    return true;
  }

  public function buscarAssinatura()
  {
    if ($this->acessoPermitido() == false) {
      $this->responderJson('Acesso negado', 403);
    }

    $json = $this->receberJson();
    $assinaturaId = $json['assinatura_id'] ?? '';
    $respostaApi = $this->pagamentoStripe->buscarAssinatura($assinaturaId);

    if (! isset($respostaApi['id']) or empty($respostaApi['id'])) {
      $this->responderJson(['erro' => 'Assinatura não encontrada'], 404);
    }

    $this->responderJson(['ok' => $respostaApi]);
  }
}