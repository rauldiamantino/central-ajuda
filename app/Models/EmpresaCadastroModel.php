<?php
namespace app\Models;
use app\Models\Model;

class EmpresaCadastroModel extends Model
{
  public function buscar(array $params = []): array
  {
    $base = $params['base'] ?? [];
    $dados = $params['dados'] ?? [];

    foreach ($dados as $chave =>  $linha):
      $tabelaColuna = explode('.', $chave);
      $tabela = $tabelaColuna[0] ?? '';
      $coluna = $tabelaColuna[1] ?? '';

      if (isset($base[ $tabela ])) {
        $base[ $tabela ][ $coluna ] = $linha;
      }
    endforeach;

    foreach ($base as $linha):
      if (empty($linha)) {
        $msgErro = [
          'erro' => [
            'codigo' => 404,
            'mensagem' => 'Recurso não encontrado',
          ],
        ];

        return $msgErro;
      }
    endforeach;

    if (in_array('', $base)) {
      $msgErro = [
        'erro' => [
          'codigo' => 500,
          'mensagem' => 'Erro ao realizar consulta',
        ],
      ];

      return $msgErro;
    }

    return $base;
  }
}