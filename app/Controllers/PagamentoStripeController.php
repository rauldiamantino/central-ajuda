<?php
namespace app\Controllers;

class PagamentoStripeController extends DashboardController
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

    if (empty($empresaId)) {
      return [];
    }

    if (empty($usuarioId)) {
      return [];
    }

    if (empty($usuarioEmail)) {
      return [];
    }

    $campos = [
      'client_reference_id' => 'E' . $empresaId . 'U' . $usuarioId,
      'customer_email' => $usuarioEmail,
      'success_url' => 'http://localhost/cadastro/sucesso',
      'line_items' => [
        [
          'price' => 'price_1Q5HXyGEvsdXh8q6qABYqPDW',
          'quantity' => 1,
        ],
      ],
      'mode' => 'subscription',
    ];

    $resposta_api = $this->stripe->checkout->sessions->create($campos);

    return $resposta_api;
  }

  public function buscarSessao(string $sessaoId): array
  {
    if (empty($sessaoId)) {
      return [];
    }

    $resposta_api = $this->stripe->checkout->sessions->retrieve($sessaoId);

    if (! is_array($resposta_api)) {
      $resposta_api = [];
    }

    if (! isset($resposta_api['id']) or empty($resposta_api['id'])) {
      return [];
    }

    return $resposta_api;
  }

  public function buscarAssinaturaAtiva(string $sessaoId): string
  {
    if (empty($sessaoId)) {
      return '';
    }

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

  public function buscarAssinatura(string $assinaturaId): array
  {
    if (empty($assinaturaId)) {
      return [];
    }

    $buscarAssinatura = $this->stripe->subscriptions->retrieve($assinaturaId);

    if (! isset($buscarAssinatura['id'])) {
      return [];
    }

    $buscarAssinatura = $buscarAssinatura->toArray();

    return $buscarAssinatura;
  }
}