<?php
namespace app\Controllers;
use app\Core\Cache;
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
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $condicao[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $this->empresaPadraoId,
    ];

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

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->executarConsulta();

    if (isset($empresa['erro']) and $empresa['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', $empresa['erro']);
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('titulo', 'Editar empresa');
    $this->visao->variavel('paginaMenuLateral', 'empresa');
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
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
    }

    $condicao[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $id,
    ];

    $colunas = [
      'Empresa.ativo',
      'Empresa.subdominio',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->executarConsulta();

    if (isset($empresa[0]['Empresa']['ativo'])) {
      $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa']['ativo'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    $nomesCache = ['publico_'];
    $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);


    // Cache sem EmpresaID
    Cache::apagar('roteador_subdominio-' . md5('id' . $this->empresaPadraoId));
    Cache::apagar('roteador_subdominio-' . md5('subdominio' . $this->usuarioLogado['subdominio']));

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Registro alterado com sucesso');
  }


  public function reprocessarAssinatura(): void
  {
    $assinaturaId = $_GET['assinatura_id'] ?? '';
    $sessaoStripe = $_GET['sessao_stripe_id'] ?? '';

    if ($sessaoStripe) {
      $this->confirmarAssinatura();
      return;
    }

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

        $condicao[] = [
          'campo' => 'Empresa.id',
          'operador' => '=',
          'valor' => $this->usuarioLogado['empresaId'],
        ];

        $colunas = [
          'Empresa.ativo',
        ];

        $empresa = $this->empresaModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->executarConsulta();

        // Atualiza sessão
        if (isset($empresa[0]['Empresa']['ativo'])) {
          $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa']['ativo'];
          $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
        }

        $nomesCache = ['publico_'];
        $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

        // Cache sem EmpresaID
        Cache::apagar('roteador_subdominio-' . md5('id' . $this->empresaPadraoId));
        Cache::apagar('roteador_subdominio-' . md5('subdominio' . $this->usuarioLogado['subdominio']));

        $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura reprocessada com sucesso');
      }
    }

    $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar');
  }

  private function confirmarAssinatura(): void
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

        $condicao[] = [
          'campo' => 'Empresa.id',
          'operador' => '=',
          'valor' => $this->usuarioLogado['empresaId'],
        ];

        $colunas = [
          'Empresa.ativo',
        ];

        $empresa = $this->empresaModel->selecionar($colunas)
                                      ->condicao($condicao)
                                      ->executarConsulta();

        // Atualiza sessão
        if (isset($empresa[0]['Empresa']['ativo'])) {
          $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa']['ativo'];
          $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
        }

        $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura confirmada com sucesso');
      }
    }

    $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar');
  }

  public function confirmarAssinaturaWebhook(string $sessionId, string $assinaturaId): bool
  {
    $resultado = $this->empresaModel->buscarEmpresaSemId('sessao_stripe_id', $sessionId);
    $empresaId = intval($resultado[0]['Empresa']['id'] ?? 0);

    if ($empresaId == 0) {
      return false;
    }

    $campos = [
      'sessao_stripe_id' => NULL,
      'assinatura_id' => $assinaturaId,
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
    $empresaId = intval($resultado[0]['Empresa']['id'] ?? 0);

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

    $nomesCache = ['publico_'];
    $this->limparCacheTodos($nomesCache, $this->usuarioLogado['empresaId']);

    // Cache sem EmpresaID
    Cache::apagar('roteador_subdominio-' . md5('id' . $this->empresaPadraoId));
    Cache::apagar('roteador_subdominio-' . md5('subdominio' . $this->usuarioLogado['subdominio']));

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