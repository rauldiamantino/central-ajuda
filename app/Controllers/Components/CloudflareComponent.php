<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;

class CloudflareComponent extends DashboardController
{
  private $zona;
  private $token;
  private $base = 'https://api.cloudflare.com/client/v4/zones/';

  public function __construct() {
    $this->token = CLOUDFLARE_TOKEN;
    $this->zona = CLOUDFLARE_ZONA;
  }

  public function criarSubdominio(string $subdominio) {

    if (HOST_LOCAL) {
      return true;
    }

    $url = $this->base . $this->zona . '/dns_records';

    $campos = [
      'type' => 'CNAME',
      'name' => $subdominio . '.360help.com.br',
      'content' => '360help.com.br',
      'ttl' => 120,
      'proxied' => true,
    ];

    $headers = [
      'Authorization: Bearer ' . $this->token,
      'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($campos));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $resposta_api = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($statusCode != 200) {
      $msgErro = 'novo-subdominio-cloudflare';

      $resposta_api = [
        'resposta' => $resposta_api,
        'cod' => $statusCode,
      ];

      registrarLog($msgErro, $resposta_api);
      registrarSentry($msgErro, $resposta_api);

      return false;
    }

    return false;
  }
}
