<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;
use app\Controllers\DashboardEmpresaController;
use app\Controllers\Components\PagamentoStripeComponent;

class AssinaturaReceberComponent extends DashboardController
{
  protected $pagamentoStripe;
  protected $empresaController;

  public function __construct()
  {
    parent::__construct();

    $this->pagamentoStripe = new PagamentoStripeComponent();
    $this->empresaController = new DashboardEmpresaController();
  }

  public function receberWebhook(): void
  {
    $json = $this->receberJson();
    $evento = $json['type'] ?? '';
    $eventoAsaas = $json['event'] ?? '';

    if ($evento == 'checkout.session.completed') {
      $this->confirmarAssinatura($json);
    }

    if ($evento == 'customer.subscription.deleted') {
      $this->cancelarAssinatura($json);
    }

    // if ($eventoAsaas == 'PAYMENT_CREATED') {
    //   $this->confirmarPagamentoAsaas($json);
    // }
  }

  private function confirmarAssinatura(array $json): void
  {
    registrarLog('webhook-assinatura-confirmada', $json);

    $sessionId = $json['data']['object']['id'] ?? '';
    $objeto = $json['data']['object']['object'] ?? '';
    $status = $json['data']['object']['status'] ?? '';
    $assinaturaId = $json['data']['object']['subscription'] ?? '';

    if (empty($sessionId)) {
      return;
    }

    if (empty($assinaturaId)) {
      return;
    }

    if ($objeto == 'checkout.session' and $status == 'complete') {
      $this->empresaController->confirmarAssinaturaWebhook($sessionId, $assinaturaId);
    }
  }

  private function cancelarAssinatura(array $json): void
  {
    registrarLog('webhook-assinatura-cancelada', $json);

    $id = $json['data']['object']['id'] ?? '';
    $objeto = $json['data']['object']['object'] ?? '';
    $status = $json['data']['object']['status'] ?? '';

    if (empty($id)) {
      return;
    }

    if ($objeto == 'subscription' and $status == 'canceled') {
      $this->empresaController->cancelarAssinaturaWebhook($id);
    }
  }
}