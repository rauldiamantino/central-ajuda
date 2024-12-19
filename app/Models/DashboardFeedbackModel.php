<?php
namespace app\Models;
use app\Models\Model;

class DashboardFeedbackModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Feedback');
  }

  public function adicionarAtualizar(array $params): array
  {
    $util = $params['util'] ?? 0;
    $artigoId = $params['artigo_id'] ?? 0;

    $util = (int) $util;
    $artigoId = (int) $artigoId;
    $msgErro = [];

    if (empty($artigoId)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Artigo não informado',
        ],
      ];
    }

    if (empty($this->empresaPadraoId)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Empresa não informada',
        ],
      ];
    }

    if ($msgErro) {
      return $msgErro;
    }

    $sql = 'INSERT INTO `feedbacks` (`artigo_id`, `sessao_id`, `empresa_id`, `util`)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE `util` = VALUES(`util`)';

    $sqlParams = [
      0 => $artigoId,
      1 => session_id(),
      2 => $this->empresaPadraoId,
      3 => $util,
    ];

    return parent::executarQuery($sql, $sqlParams);
  }
}