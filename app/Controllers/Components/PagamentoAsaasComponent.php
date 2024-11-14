<?php
namespace app\Controllers\Components;
use Rollbar\Rollbar;
use Rollbar\Payload\Level;
use app\Controllers\DashboardController;
use DateTime;

class PagamentoAsaasComponent extends DashboardController
{
  private $base;
  private $token;

  public function __construct()
  {
    $this->base = 'https://api.asaas.com/v3';
    $this->token = ASAAS_TOKEN;

    if (HOST_LOCAL) {
      $this->base = 'https://sandbox.asaas.com/api/v3';
    }
  }

  public function buscarCliente(array $empresa = [])
  {
    $cnpj = $empresa[0]['Empresa']['cnpj'];

    if (empty($cnpj)) {
      return ['erro' => 'CNPJ não informado'];
    }

    $resposta = $this->req('GET', '/api/v3/customers?cpfCnpj=' . $cnpj);

    if (isset($resposta['dados']['errors']) and $resposta['dados']['errors']) {
      return $resposta['dados']['errors'];
    }

    if (! isset($resposta['dados']['data'][0]['id'])) {
      return [];
    }

    return $resposta['dados']['data'][0];
  }

  public function criarCliente(array $empresa = [])
  {
    $empresaId = $empresa[0]['Empresa']['id'] ?? '';
    $empresaCnpj = $empresa[0]['Empresa']['cnpj'] ?? '';
    $empresaNome = $empresa[0]['Empresa']['nome'] ?? '';
    $usuarioPadraoEmail = $empresa[0]['Usuario']['email'] ?? '';
    $empresaTelefone = $empresa[0]['Empresa']['telefone'] ?? '';

    if (empty($empresaId)) {
      return ['erro' => 'Dados da empresa não encontrados'];
    }

    if (empty($empresaCnpj)) {
      return ['erro' => 'CNPJ da empresa não informado'];
    }

    if (empty($empresaNome)) {
      return ['erro' => 'Nome da empresa não informado'];
    }

    $campos = [
      'name' => $empresaNome,
      'cpfCnpj' => $empresaCnpj,
      'email' => $usuarioPadraoEmail,
      'mobilePhone' => $empresaTelefone,
      'externalReference' => $empresaId,
    ];

    $resposta = $this->req('POST', '/customers', $campos);

    if (isset($resposta['dados']['errors'][0]['description']) and $resposta['dados']['errors'][0]['description']) {
      return ['erro' => $resposta['dados']['errors'][0]['description']];
    }

    if (! isset($resposta['dados']['id']) or empty($resposta['dados']['id'])) {
      return ['erro' => 'Não foi possível cadastrar o cliente'];
    }

    return $resposta['dados'];
  }

  public function criarAssinatura(array $empresa, string $plano)
  {
    if (! isset($empresa[0]['Empresa']['subdominio']) or empty($empresa[0]['Empresa']['subdominio'])) {
      return ['erro' => 'Erro ao buscar empresa'];
    }

    $resposta = $this->buscarCliente($empresa);

    if (isset($resposta['erro'])) {
      return $resposta;
    }

    if (! isset($resposta['id']) or empty($resposta['id'])) {
      $resposta = $this->criarCliente($empresa);
    }

    if (isset($resposta['erro'])) {
      return $resposta;
    }

    if (! in_array($plano, ['mensal', 'anual'])) {
      return ['erro' => 'Plano inválido'];
    }

    // Criar tabela
    $planoValor = 99;
    $ciclo = 'MONTHLY';

    if ($plano == 'anual') {
      $planoValor = 768;
      $ciclo = 'YEARLY';
    }

    $data = new DateTime('now');
    $data->modify('+2 days');  // Adiciona 2 dias à data atual
    $vencimento = $data->format('Y-m-d');

    $campos = [
      'customer' => $resposta['id'],
      'billingType' => 'CREDIT_CARD',
      'value' => $planoValor,
      'nextDueDate' => $vencimento,
      'cycle' => $ciclo,
      'description' => 'Plano ' . $plano,
      'externalReference' => '1',
      'callback' => [
        'successUrl' => 'https://360help.com.br/' . $empresa[0]['Empresa']['subdominio'] . '/dashboard/empresa/editar',
        'autoRedirect' => true
      ],
    ];

    $resposta = $this->req('POST', '/subscriptions', $campos);

    if (isset($resposta['dados']['errors'][0]['description']) and $resposta['dados']['errors'][0]['description']) {
      return ['erro' => $resposta['dados']['errors'][0]['description']];
    }

    if (! isset($resposta['dados']['id']) or empty($resposta['dados']['id'])) {
      return ['erro' => 'Não foi possível obter o ID da assinatura'];
    }

    return $resposta['dados'];
  }

  public function buscarAssinatura(string $assinaturaId)
  {
    if (empty($assinaturaId)) {
      return ['erro' => 'Assinatura ID não informado'];
    }

    $resposta = $this->req('GET', '/subscriptions/' . $assinaturaId);

    if (isset($resposta['dados']['errors']) and $resposta['dados']['errors']) {
      return $resposta['dados']['errors'];
    }

    if (! isset($resposta['dados']['id'])) {
      return [];
    }

    return $resposta['dados'];
  }

  public function buscarCobrancas(string $assinaturaId)
  {
    if (empty($assinaturaId)) {
      return ['erro' => 'Assinatura ID não informado'];
    }

    $resposta = $this->req('GET', '/subscriptions/' . $assinaturaId . '/payments');

    if (isset($resposta['dados']['errors']) and $resposta['dados']['errors']) {
      return $resposta['dados']['errors'];
    }

    if (! isset($resposta['dados']['data'][0]['id'])) {
      return [];
    }

    return $resposta['dados']['data'];
  }

  public function req(string $metodo, string $endpoint, array $params = []): array
  {
    if (empty($metodo)) {
      return ['erro' => 'Método não informado'];
    }

    if (empty($endpoint)) {
      return ['erro' => 'Endpoint não informado'];
    }

    $camposReq = [
      'metodo' => $metodo,
      'url' => $this->base . $endpoint,
      'headers' => [
        'access_token' => $this->token,
        'accept' => 'application/json',
        'content-type' => 'application/json',
      ],
      'body' => json_encode($params),
      'resposta' => [
        'codigo' => 0,
        'dados' => '',
      ],
    ];

    $client = new \GuzzleHttp\Client();

    try {
      $campos = [
        'body' => $camposReq['body'],
        'headers' => $camposReq['headers'],
      ];

      if (empty($params)) {
        unset($campos['body']);
        unset($campos['content-type']);
      }

      $resposta = $client->request($metodo, $this->base . $endpoint, $campos);

      $camposReq['resposta']['codigo'] = $resposta->getStatusCode();
      $camposReq['resposta']['dados'] = json_decode($resposta->getBody(), true);
    }
    catch (\GuzzleHttp\Exception\ClientException $e) {
      $camposReq['resposta']['codigo'] = $e->getCode();
      $camposReq['resposta']['dados'] = json_decode($e->getResponse()->getBody()->getContents(), true);

      Rollbar::log(Level::ERROR, $e, $camposReq);
    }

    $camposReq['body'] = json_decode($camposReq['body'], true);
    registrarLog('requisicoes', $camposReq);

    return $camposReq['resposta'];
  }
}