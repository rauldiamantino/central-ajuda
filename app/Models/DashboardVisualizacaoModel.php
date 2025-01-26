<?php
namespace app\Models;
use app\Models\Model;

class DashboardVisualizacaoModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Visualizacao');
  }

  public function atualizar(array $params, int $id): array
  {
    $artigoId = (int) $id;
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

    $sql = 'INSERT INTO `visualizacoes` (`artigo_id`, `sessao_id`, `empresa_id`)
            VALUES (?, ?, ?)';

    $sqlParams = [
      0 => $artigoId,
      1 => session_id(),
      2 => $this->empresaPadraoId,
    ];

    return parent::executarQuery($sql, $sqlParams);
  }
}