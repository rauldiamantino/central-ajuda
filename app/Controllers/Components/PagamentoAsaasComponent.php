<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class PagamentoAsaasComponent extends DashboardController
{
  private $base;
  private $token;

  public function __construct()
  {
    $this->base = '';
    $this->token = '';

    if (HOST_LOCAL) {
      $this->base = 'https://sandbox.asaas.com';
      $this->token = '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTQ0NTU6OiRhYWNoXzUxN2Q2NTJhLWY2ZWQtNDQ5OC1hMTAzLWMyMjU3Y2ZmMGU3MA==';
    }
  }

  public function req(string $metodo, string $endpoint, array $camposJson = []): array
  {
    if (empty($metodo)) {
      ['erro' => 'Método não informado'];
    }

    if (empty($endpoint)) {
      ['erro' => 'Endpoint não informado'];
    }

    if (empty($params)) {
      ['erro' => 'Parâmetros não informados'];
    }

    $client = new \GuzzleHttp\Client();

    if (empty($client)) {
      ['erro' => 'Não foi possível se conectar com a Asaas'];
    }

    $resposta = [];
    $url = $this->base . $endpoint;

    $camposReq = [
      'metodo' => $metodo,
      'url' => $url,
      'dados' =>[
        'body' => json_encode($camposJson),
        'headers' => [
          'accept' => 'application/json',
          'access_token' => $this->token,
          'content-type' => 'application/json',
          'User-Agent' => 'rauldiamantino25@gmail.com',
        ],
      ],
    ];

    try {
      $client = new Client();
      $resposta = $client->request($metodo, $url, $camposReq['dados']);
      $resposta = $resposta->getBody();
      $resposta = json_decode($resposta);
    }
    catch (\Exception $e) {
      $mensagemErro = $e->getMessage();
      registrarLog('asaas', $mensagemErro);

      if ($e instanceof ClientException) {
        \Rollbar\Rollbar::error("Erro de Cliente (401): " . $mensagemErro, [
          'exception' => $e,
          'response' => $e->getResponse()->getBody(),
          'camposReq' => $camposReq,
        ]);
      }
      elseif ($e instanceof RequestException) {
        \Rollbar\Rollbar::error("Erro de Requisição: " . $mensagemErro, [
          'exception' => $e,
          'camposReq' => $camposReq,
        ]);
      }
      else {
        \Rollbar\Rollbar::error("Erro inesperado: " . $mensagemErro, [
          'exception' => $e,
          'camposReq' => $camposReq,
        ]);
      }
    }

    registrarLog('asaas', ['camposReq' => $camposReq, 'resposta' => $resposta]);

    return ['resposta' => $resposta];
  }

  public function criarCliente()
  {

  }

  public function criarAssinatura()
  {
    $campos = [
      'customer' => 6345745,
      'billingType' => 'UNDEFINED',
      'value' => 99,
      'nextDueDate' => '2024-11-14',
      'cycle' => 'MONTHLY',
      'description' => 'Plano mensal',
      'externalReference' => '1',
      // 'callback' => [
      //   'successUrl' => 'http://localhost/padrao/dashboard/empresa/editar',
      // ],
    ];

    $respostaApi = $this->req('POST', '/api/v3/subscriptions', $campos);
    pr($respostaApi, true);
  }
}