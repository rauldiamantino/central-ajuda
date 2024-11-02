<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;
use \Stripe\Exception\ApiErrorException;

class PagamentoStripeComponent extends DashboardController
{
  private $stripe;

  public function __construct()
  {
    $this->stripe = new \Stripe\StripeClient('sk_test_51Q5HN6GEvsdXh8q65PqVx8njWqSmjlDkTu8h3u4b3C5j2k66MZdHaZhVv8YLK2hJdL4I4QwlfaqAS0KMvnBOi9fH00NC4yQClI');
  }

  public function criarSessao(array $usuario)
  {
    $usuarioId = intval($usuario['id'] ?? 0);
    $usuarioEmail = $usuario['email'] ?? '';
    $empresaId = intval($usuario['empresa_id'] ?? 0);

    if (empty($empresaId) || empty($usuarioId) || empty($usuarioEmail)) {
      return [];
    }

    $urlSucesso = baseUrl('/cadastro/sucesso');

    if (HOST_LOCAL) {
      $urlSucesso = 'http://localhost' . baseUrl('/cadastro/sucesso');
    }

    $campos = [
      'client_reference_id' => 'E' . $empresaId . 'U' . $usuarioId,
      'customer_email' => $usuarioEmail,
      'success_url' => $urlSucesso,
      'line_items' => [
        [
          'price' => 'price_1Q5HXyGEvsdXh8q6qABYqPDW',
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
      registrarLog('stripe', $e->getMessage());
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
      registrarLog('stripe', $e->getMessage());
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
      registrarLog('stripe', $e->getMessage());
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
      return $buscarAssinatura->toArray();
    }
    catch (ApiErrorException $e) {
      registrarLog('stripe', $e->getMessage());
      return [];
    }
  }
}