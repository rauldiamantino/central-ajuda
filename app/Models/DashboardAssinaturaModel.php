<?php
namespace app\Models;
use app\Models\Model;

class DashboardAssinaturaModel extends Model
{
  public function __construct()
  {
    parent::__construct('Assinatura');
  }

  public function confirmarAssinatura(string $sessionId, string $assinaturaId): void
  {
    // Recupera empresaID
    $sql = 'SELECT `Empresa`.`id` AS `Empresa.id` FROM `empresas` AS `Empresa` WHERE `sessao_stripe_id` = ?';

    $params = [
      0 => $sessionId,
    ];

    $resultado = $this->executarQuery($sql, $params);
    $empresaId = intval($resultado[0]['Empresa.id'] ?? 0);

    // Confirma assinatura
    if ($empresaId) {
      $sql = 'UPDATE
                `empresas`
              SET
                `sessao_stripe_id` = ?,
                `assinatura_id` = ?,
                `ativo` = ?
              WHERE
                `id` = ?';

      $params = [
          0 => null,
          1 => $assinaturaId,
          2 => ATIVO,
          3 => $empresaId,
      ];

      $this->executarQuery($sql, $params);
    }
  }
}