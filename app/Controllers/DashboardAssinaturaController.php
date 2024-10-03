<?php
namespace app\Controllers;
use app\Models\DashboardAssinaturaModel;
use app\Controllers\PagamentoStripeController;

class DashboardAssinaturaController extends DashboardController
{
  protected $assinaturaModel;
  protected $pagamentoStripe;

  public function __construct()
  {
    parent::__construct();

    $this->assinaturaModel = new DashboardAssinaturaModel();
    $this->pagamentoStripe = new PagamentoStripeController();
  }

  public function receberWebhook()
  {
    $json = $this->receberJson();

    $evento = $json['type'] ?? '';
    $sessaoId = $json['data']['object']['id'] ?? '';
    $invoice = $json['data']['object']['invoice'] ?? '';

    if (empty($invoice)) {
      return;
    }

    if (is_array($sessaoId)) {
      $sessaoId = '';
    }

    $sessaoId = htmlspecialchars($sessaoId);

    // Primeiro pagamento realizado
    if ($sessaoId and $evento == 'checkout.session.completed') {
      $assinaturaId = $this->pagamentoStripe->buscarAssinaturaAtiva($sessaoId);

      if ($assinaturaId) {
        $this->assinaturaModel->confirmarAssinatura($sessaoId, $assinaturaId);
      }

      registrarLog('webhook-primeiro-pagamento', $json);

      return;
    }

    // Assinatura cancelada
    if ($evento == 'charge.refunded') {

    }
  }
}