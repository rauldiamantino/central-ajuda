<?php
namespace app\Controllers;

use app\Controllers\Components\DatabaseFirebaseComponent as ComponentsDatabaseFirebaseComponent;
use DateTime;
use app\Core\Cache;
use app\Models\DashboardEmpresaModel;
use app\Models\DashboardAssinaturaModel;
use app\Models\DatabaseFirebaseComponent;
use app\Controllers\DashboardController;
use app\Controllers\Components\PagamentoAsaasComponent;

class DashboardAssinaturaController extends DashboardController
{
  protected $firebase;
  protected $empresaModel;
  protected $pagamentoAsaas;
  protected $assinaturaModel;

  public function __construct()
  {
    parent::__construct();

    $this->pagamentoAsaas = new PagamentoAsaasComponent();
    $this->firebase = new ComponentsDatabaseFirebaseComponent();
    $this->empresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->assinaturaModel = new DashboardAssinaturaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function assinaturaEditarVer()
  {
    $condicao[] = [
      'campo' => 'Assinatura.empresa_id',
      'operador' => '=',
      'valor' => (int) $this->empresaPadraoId,
    ];

    $colunas = [
      'Assinatura.id',
      'Assinatura.asaas_id',
      'Assinatura.gratis_prazo',
      'Assinatura.valor',
      'Assinatura.ciclo',
      'Assinatura.espaco',
      'Assinatura.empresa_id',
      'Assinatura.status',
      'Assinatura.criado',
      'Assinatura.modificado',
    ];

    $assinatura = $this->assinaturaModel->selecionar($colunas)
                                        ->condicao($condicao)
                                        ->executarConsulta();

    if (isset($assinatura['erro']) and $assinatura['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', $assinatura['erro']);
    }

    // Recupera assinatura e cobranças no Asaas
    $asaasId = $assinatura[0]['Assinatura']['asaas_id'] ?? '';

    // Teste grátis expirado
    $assinaturaStatus = intval($assinatura[0]['Assinatura']['status'] ?? 0);
    $gratisPrazo = $assinatura[0]['Assinatura']['gratis_prazo'] ?? '';

    if ($gratisPrazo) {
      $dataHoje = new DateTime('now');
      $dataGratis = new DateTime($gratisPrazo);

      if ((int) $assinaturaStatus == INATIVO and $dataHoje > $dataGratis) {
        $this->sessaoUsuario->definir('teste-expirado-' . $this->empresaPadraoId, true);
      }
      else {
        $this->sessaoUsuario->apagar('teste-expirado-' . $this->empresaPadraoId);
      }
    }

    $this->visao->variavel('assinaturaId', $asaasId);
    $this->visao->variavel('assinatura', reset($assinatura));
    $this->visao->variavel('metaTitulo', 'Editar assinatura - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'assinatura');
    $this->visao->renderizar('/assinatura/index');
  }

  public function reprocessarAssinaturaAsaas(): void
  {
    $asaasId = $_GET['asaas_id'] ?? '';

    if (empty($asaasId)) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'ID da assinatura não informado.');
    }

    $buscarAssinatura = $this->pagamentoAsaas->buscarAssinatura($asaasId);
    $assinaturaStatus = $buscarAssinatura['status'] ?? '';
    $assinaturaCiclo = $buscarAssinatura['cycle'] ?? '';
    $assinaturaValor = $buscarAssinatura['value'] ?? 0;

    if (isset($buscarAssinatura['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', $buscarAssinatura['erro']);
    }

    if ($assinaturaStatus == 'ACTIVE') {
      $novoStatus = ATIVO;
    }
    else {
      $novoStatus = INATIVO;
    }

    if ($assinaturaCiclo == 'YEARLY') {
      $novoCiclo = 'anual';
    }
    elseif ($assinaturaCiclo == 'MONTHLY') {
      $novoCiclo = 'mensal';
    }
    else {
      $novoCiclo = '';
    }

    // Atualiza banco de dados
    if ($assinaturaStatus) {
      $campos = [
        'status' => $novoStatus,
        'valor' => $assinaturaValor,
        'ciclo' => $novoCiclo,
      ];

      $this->assinaturaModel->atualizar($campos, $this->empresaPadraoId);

      // Atualiza sessão para uso imediato
      $this->usuarioLogado['assinaturaStatus'] = $novoStatus;
      $this->usuarioLogado['assinaturaId'] = $asaasId;
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
      Cache::apagarSemId('roteador-' . $this->usuarioLogado['subdominio']);

      $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Assinatura reprocessada');
    }

    $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Assinatura não encontrada');
  }

  public function criarAssinaturaAsaas(int $id)
  {
    $id = (int) $id;
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
      'Empresa.subdominio_2',
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
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Falha ao buscar dados da empresa.');
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
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', $msgErro['erro']);
    }

    $this->pagamentoAsaas = new PagamentoAsaasComponent();
    $criarAssinatura = $this->pagamentoAsaas->criarAssinatura($empresa, $plano);

    if (isset($criarAssinatura['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Não foi possível gerar a assinatura, por favor, entre em contato com o nosso suporte');
    }

    $campos = [
      'status' => ATIVO,
      'asaas_id' => $criarAssinatura['id'],
      'ciclo' => $plano,
      'valor' => floatval($criarAssinatura['value'] ?? 0),
    ];

    // Atualiza no banco de dados
    $resultado = $this->assinaturaModel->atualizar($campos, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', $resultado['erro']);
    }

    if (! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] < 1) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Não foi possível gravar os dados da sua assinatura, por favor, entre em contato com o nosso suporte');
    }

    // Atualiza para uso imediato
    $this->usuarioLogado['assinaturaStatus'] = ATIVO;
    $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    $this->sessaoUsuario->apagar('teste-expirado-' . $this->empresaPadraoId);

    Cache::apagarSemId('roteador-' . $this->usuarioLogado['subdominio']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Assinatura criada com sucesso!');
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();

    if (isset($json['valor'])) {
      $json['valor'] = str_replace(',', '.', $json['valor']);
    }

    $resultado = $this->assinaturaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', $resultado['erro']);
    }

    if (! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] == 0) {
      $this->redirecionar('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Nenhuma alteração realizada');
    }

    $condicao[] = [
      'campo' => 'Assinatura.id',
      'operador' => '=',
      'valor' => (int) $id,
    ];

    $colunas = [
      'Assinatura.id',
      'Assinatura.gratis_prazo',
      'Assinatura.espaco',
      'Assinatura.status',
    ];

    $assinatura = $this->assinaturaModel->selecionar($colunas)
                                        ->condicao($condicao)
                                        ->executarConsulta();

    if (isset($assinatura[0]['Assinatura']['id'])) {
      $this->usuarioLogado['gratisPrazo'] = $assinatura[0]['Assinatura']['gratis_prazo'];
      $this->usuarioLogado['assinaturaStatus'] = $assinatura[0]['Assinatura']['status'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    Cache::apagar('calcular-consumo', $this->empresaPadraoId);
    Cache::apagarSemId('roteador-' . $this->usuarioLogado['subdominio']);

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar', 'Registro alterado com sucesso');
  }

  public function buscarCobrancas()
  {
    $asaasId = $_GET['asaas_id'] ?? '';
    $asaasId = htmlspecialchars($asaasId);

    $this->pagamentoAsaas = new PagamentoAsaasComponent();
    $buscarCobrancas = $this->pagamentoAsaas->buscarCobrancas($asaasId);
    $this->responderJson($buscarCobrancas);
  }

  public function calcularConsumo()
  {
    $cacheTempo = 60*60*24;
    $cacheNome = 'calcular-consumo';
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      // Máximo
      $condicao[] = [
        'campo' => 'Assinatura.empresa_id',
        'operador' => '=',
        'valor' => (int) $this->empresaPadraoId,
      ];

      $colunas = [
        'Assinatura.espaco',
      ];

      $assinatura = $this->assinaturaModel->selecionar($colunas)
                                          ->condicao($condicao)
                                          ->executarConsulta();

      $maximoMb = $assinatura[0]['Assinatura']['espaco'] ?? 0;

      // Consumo MySQL
      $consumoBanco = $this->assinaturaModel->calcularConsumoBanco($this->empresaPadraoId);
      $consumoBancoTotal = $consumoBanco[0]['total_mb'] ?? 0;
      $consumoBancoTotal = (float) $consumoBancoTotal;

      // Consumo Firebase
      $consumoFirebase = $this->firebase->calcularConsumoFirebase($this->empresaPadraoId);
      $consumoFirebaseTotal = (float) $consumoFirebase;

      $totalMb = $consumoBancoTotal + $consumoFirebaseTotal;
      $totalMb = (float) number_format($totalMb, 2, '.');

      $resultado = [
        'total' => $totalMb,
        'maximo' => $maximoMb,
      ];

      Cache::definir($cacheNome, $resultado, $cacheTempo, $this->empresaPadraoId);
    }

    if ($resultado['total'] >= $resultado['maximo']) {
      $this->sessaoUsuario->definir('bloqueio-espaco-' . $this->empresaPadraoId, true);
    }
    else {
      $this->sessaoUsuario->apagar('bloqueio-espaco-' . $this->empresaPadraoId);
    }

    $this->responderJson($resultado);
  }
}