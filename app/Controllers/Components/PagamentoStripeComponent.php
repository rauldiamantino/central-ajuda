<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;
use \Stripe\Exception\ApiErrorException;

class PagamentoStripeComponent extends DashboardController
{
  private $stripe;
  private $planoAnualId;
  private $planoMensalId;
  private $planoAnualNome;
  private $planoMensalNome;

  public function __construct()
  {
    $token = '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';

    if (HOST_LOCAL) {
      $token = 'sk_test_51Q5HN6GEvsdXh8q65PqVx8njWqSmjlDkTu8h3u4b3C5j2k66MZdHaZhVv8YLK2hJdL4I4QwlfaqAS0KMvnBOi9fH00NC4yQClI';
    }

    $this->planoAnualId = 'price_1QHTfpGEvsdXh8q6JuQw8hnj';
    $this->planoMensalId = 'price_1Q5HXyGEvsdXh8q6qABYqPDW';

    $this->planoAnualNome = 'Anual';
    $this->planoMensalNome = 'Mensal';

    $this->stripe = new \Stripe\StripeClient($token);
  }

  public function criarSessao(array $usuario, string $planoNome)
  {
    $usuarioId = intval($usuario['id'] ?? 0);
    $usuarioEmail = $usuario['email'] ?? '';
    $empresaId = intval($usuario['empresa_id'] ?? 0);

    if (empty($empresaId) || empty($usuarioId) || empty($usuarioEmail)) {
      return [];
    }

    if (! in_array($planoNome, [$this->planoAnualNome, $this->planoMensalNome])) {
      return [];
    }

    $urlSucesso = 'https://www.360help.com.br/cadastro/sucesso';

    if (HOST_LOCAL) {
      $urlSucesso = 'http://localhost/cadastro/sucesso';
    }

    $plano = '';

    if ($planoNome == $this->planoMensalNome) {
      $plano = $this->planoMensalId;
    }
    elseif ($planoNome == $this->planoAnualNome) {
      $plano = $this->planoAnualId;
    }

    $campos = [
      'client_reference_id' => 'E' . $empresaId . 'U' . $usuarioId,
      'customer_email' => $usuarioEmail,
      'success_url' => $urlSucesso,
      'line_items' => [
        [
          'price' => $plano,
          'quantity' => 1,
        ],
      ],
      'mode' => 'subscription',
    ];

    try {
      $resposta_api = $this->stripe->checkout->sessions->create($campos);

      return $resposta_api;
    }
    catch (ApiErrorException $e) {
      registrarLog('stripe-criar-sessao', $e->getMessage());
      return [];
    }
  }

  public function buscarSessao(string $sessaoId): array
  {
    if (empty($sessaoId)) {
      return [];
    }

    try {
      $resposta_api = $this->stripe->checkout->sessions->retrieve($sessaoId);
      return $resposta_api->toArray();
    }
    catch (ApiErrorException $e) {
      registrarLog('stripe-buscar-sessao', $e->getMessage());
      return [];
    }
  }

  public function buscarAssinaturaAtiva(string $sessaoId): string
  {
    if (empty($sessaoId)) {
      return '';
    }

    try {
      $buscarSessao = $this->stripe->checkout->sessions->retrieve($sessaoId);
      $assinaturaId = $buscarSessao['subscription'] ?? '';

      if (empty($assinaturaId)) {
        return '';
      }

      $buscarAssinatura = $this->stripe->subscriptions->retrieve($assinaturaId);
      $status = $buscarAssinatura['status'] ?? '';

      if ($status == 'active') {
        return $assinaturaId;
      }

      return '';
    }
    catch (ApiErrorException $e) {
      registrarLog('stripe-buscar-assinatura-ativa', $e->getMessage());
      return '';
    }
  }

  public function buscarAssinatura(string $assinaturaId): array
  {
    if (empty($assinaturaId)) {
      return [];
    }

    try {
      $buscarAssinatura = $this->stripe->subscriptions->retrieve($assinaturaId);
      $buscarAssinatura = $buscarAssinatura->toArray();

      $planoNome = '';
      $planoAssinatura = $buscarAssinatura['plan']['id'];

      if ($planoAssinatura == $this->planoMensalId) {
        $planoNome = $this->planoMensalNome;
      }
      elseif ($planoAssinatura == $this->planoAnualId) {
        $planoNome = $this->planoAnualNome;
      }

      $buscarAssinatura['plano_nome'] = $planoNome;
      return $buscarAssinatura;
    }
    catch (ApiErrorException $e) {
      registrarLog('stripe-buscar-assinatura', $e->getMessage());
      return [];
    }
  }
}