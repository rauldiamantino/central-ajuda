<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardEmpresaModel;
use app\Controllers\DashboardController;
use app\Controllers\Components\PagamentoAsaasComponent;

class DashboardEmpresaController extends DashboardController
{
  protected $empresaModel;
  protected $pagamentoAsaas;
  protected $pagamentoStripe;

  public function __construct()
  {
    parent::__construct();

    $this->pagamentoAsaas = new PagamentoAsaasComponent();
    $this->empresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function empresaEditarVer()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
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
      'Empresa.favicon',
      'Empresa.cor_primaria',
      'Empresa.gratis_prazo',
      'Empresa.assinatura_id_asaas',
      'Empresa.assinatura_status',
      'Empresa.criado',
      'Empresa.modificado',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->executarConsulta();

    if (isset($empresa['erro']) and $empresa['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', $empresa['erro']);
    }

    // Recupera assinatura e cobranças no Asaas
    $buscarCobrancas = [];
    $assinaturaId = $empresa[0]['Empresa']['assinatura_id_asaas'];

    if ($assinaturaId) {
      $this->pagamentoAsaas = new PagamentoAsaasComponent();
      $buscarCobrancas = $this->pagamentoAsaas->buscarCobrancas($assinaturaId);
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('assinaturaId', $assinaturaId);
    $this->visao->variavel('cobrancas', $buscarCobrancas);
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

  public function reprocessarAssinaturaAsaas(): void
  {
    $assinaturaId = $_GET['assinatura_id'] ?? '';

    if (empty($assinaturaId)) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'ID da assinatura não informado.');
    }

    // sub_s7e1bsgfijoaw7qp
    $buscarAssinatura = $this->pagamentoAsaas->buscarAssinatura($assinaturaId);
    $assinaturaStatus = $buscarAssinatura['status'] ?? '';
    $assinaturaCiclo = $buscarAssinatura['cycle'] ?? '';
    $assinaturaValor = $buscarAssinatura['value'] ?? 0;
    $assinaturaValor = number_format($assinaturaValor, 2, '.', '');

    if (isset($buscarAssinatura['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $buscarAssinatura['erro']);
    }

    if ($assinaturaStatus == 'ACTIVE') {
      $novoStatus = ATIVO;
    }
    else {
      $novoStatus = INATIVO;
    }

    if ($assinaturaCiclo == 'YEARLY') {
      $novoCiclo = 'Anual';
    }
    elseif ($assinaturaCiclo == 'MONT') {
      $novoCiclo = 'Mensal';
    }
    else {
      $novoCiclo = '';
    }

    // Atualiza banco de dados
    if ($assinaturaStatus) {
      $campos = [
        'assinatura_status' => $novoStatus,
        'assinatura_ciclo' => $novoCiclo,
        'assinatura_valor' => $assinaturaValor,
      ];

      $this->empresaModel->atualizar($campos, $this->empresaPadraoId);

      // Atualiza sessão para uso imediato
      $this->usuarioLogado['assinaturaStatus'] = $novoStatus;
      $this->usuarioLogado['assinaturaId'] = $assinaturaId;
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
      $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura reprocessada');
    }

    $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura não encontrada');
  }

  public function criarAssinaturaAsaas()
  {
    $plano = strtolower($_GET['plano'] ?? '');

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => '',
      ],
    ];

    $condicao[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $this->empresaPadraoId,
    ];

    $condicao[] = [
      'campo' => 'Usuario.padrao',
      'operador' => '=',
      'valor' => (int) USUARIO_PADRAO,
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.nome',
      'Empresa.cnpj',
      'Empresa.telefone',
      'Empresa.subdominio',
      'Usuario.email',
      'Usuario.padrao',
    ];

    $juntarUsuario = [
      'tabelaJoin' => 'Usuario',
      'campoA' => 'Usuario.empresa_id',
      'campoB' => 'Empresa.id',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->juntar($juntarUsuario, 'LEFT')
                                  ->executarConsulta();

    if (empty($empresa)) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Não foi possível gerar a assinatura, por favor, entre em contato com o nosso suporte.');
    }

    if (is_array($plano) or ! in_array($plano, ['mensal', 'anual'])) {
      $msgErro['erro']['mensagem'] = 'Plano inválido';
    }

    if (! isset($empresa[0]['Empresa']['nome'])) {
      $msgErro['erro']['mensagem'] = 'Para realizar a assinatura, é necessário preencher o nome da empresa';
    }

    if (! isset($empresa[0]['Empresa']['cnpj'])) {
      $msgErro['erro']['mensagem'] = 'Para realizar a assinatura, é necessário preencher o CNPJ da empresa';
    }

    if ($msgErro['erro']['mensagem']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $msgErro['erro']);
    }

    $this->pagamentoAsaas = new PagamentoAsaasComponent();
    $criarAssinatura = $this->pagamentoAsaas->criarAssinatura($empresa, $plano);

    if (isset($criarAssinatura['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Não foi possível gerar a assinatura, por favor, entre em contato com o nosso suporte');
    }

    $campos = ['assinatura_id_asaas' => $criarAssinatura['id']];
    $resultado = $this->empresaModel->atualizar($campos, $this->empresaPadraoId);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
    }

    if (! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] != 1) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Não foi possível gravar os dados da sua assinatura, por favor, entre em contato com o nosso suporte');
    }

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Assinatura criada com sucesso!');
  }

  public function atualizar(int $id)
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
    }

    if (! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] == 0) {
      $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Nenhuma alteração realizada');
    }

    $condicao[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $id,
    ];

    $colunas = [
      'Empresa.ativo',
      'Empresa.subdominio',
      'Empresa.gratis_prazo',
      'Empresa.cor_primaria',
      'Empresa.assinatura_status',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->executarConsulta();

    if (isset($empresa[0]['Empresa']['ativo'])) {
      $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa']['ativo'];
      $this->usuarioLogado['gratisPrazo'] = $empresa[0]['Empresa']['gratis_prazo'];
      $this->usuarioLogado['corPrimaria'] = $empresa[0]['Empresa']['cor_primaria'];
      $this->usuarioLogado['assinaturaStatus'] = $empresa[0]['Empresa']['assinatura_status'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    Cache::apagar('publico-dados-empresa', $this->usuarioLogado['empresaId']);
    Cache::apagar('roteador_subdominio-' . md5('id' . $this->empresaPadraoId));
    Cache::apagar('roteador_subdominio-' . md5('subdominio' . $this->usuarioLogado['subdominio']));

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Registro alterado com sucesso');
  }
}