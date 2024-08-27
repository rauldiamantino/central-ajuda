<?php
namespace app\Models;
use app\Models\Model;

class DashboardAjusteModel extends Model
{
  public function __construct()
  {
    parent::__construct('Ajuste');
  }

  // --- CRUD ---
  public function atualizarAjustes(array $json = []): array
  {
    $permitidos = [
      'artigo_autor',
      'artigo_criado',
      'artigo_modificado',
      'botao_whatsapp',
      'publico_cate_busca',
      'publico_topo_fixo',
    ];

    $total = 0;
    $campos = [];
    foreach ($permitidos as $linha) :
      
      if (isset($json[ $linha ])) {
        $campos[ $linha ] = (int) $json[ $linha ];
      }
    endforeach;

    foreach ($campos as $chave => $valor) :
      $camposAtualizar = [
        'nome' => $chave,
        'ativo' => $valor,
      ];

      $campos = $this->validarCampos($camposAtualizar, true);

      if (isset($campos['erro'])) {
        return $campos;
      }

      $sql = 'INSERT INTO
                `ajustes` (`nome`, `ativo`, `empresa_id`)
              VALUES
                (?, ?, ?) ON DUPLICATE KEY
              UPDATE
                `ativo` =
              VALUES
                (`ativo`)';
      
      $sqlParams = [
        0 => $campos['nome'],
        1 => $campos['ativo'],
        2 => $campos['empresa_id'],
      ];

      $atualizar = $this->executarQuery($sql, $sqlParams);

      if (isset($atualizar['erro'])) {
        return $atualizar;
      }

      $total += intval($atualizar['linhasAfetadas'] ?? 0);
    endforeach;

    return ['ok' => $total];
  }

  public function buscar(array $colunas = []): array
  {
    return parent::buscar($colunas);
  }

  // --- Métodos auxiliares
  private function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'nome' => $params['nome'] ?? '',
      'ativo' => $params['ativo'] ?? 0,
      'empresa_id' => $this->empresaPadraoId,
    ];

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => [],
      ],
    ];

    // Campos vazios
    foreach ($campos as $chave => $linha):
      $permitidos = [
        'ativo',
      ];

      if ($atualizar and ! isset($params[ $chave ])) {

        // Sempre precisa do ID da empresa
        if ($chave != 'empresa_id') {
          continue;
        }
      }

      if (! in_array($chave, $permitidos) and empty($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'vazio');
      }

      // Previne injection via array
      if (is_array($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'invalido');
      }
    endforeach;

    if (empty($msgErro['erro']['mensagem'])) {
      $campos['ativo'] = filter_var($campos['ativo'], FILTER_SANITIZE_NUMBER_INT);
      $campos['nome'] = htmlspecialchars($campos['nome']);
      $campos['empresa_id'] = filter_var($campos['empresa_id'], FILTER_SANITIZE_NUMBER_INT);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [0, 1])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nomeCaracteres = 255;
      $empresaIdCaracteres = 999999999;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['empresa_id']) > $empresaIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('empresa_id', 'caracteres', $empresaIdCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'nome' => $campos['nome'],
      'empresa_id' => $campos['empresa_id'],
    ];

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
    }

    $msgErro = [
      'vazio' => 'O campo ' . $campo . ' não pode ser vazio',
      'invalido' => 'Campo ' . $campo . ' com formato inválido',
      'valInvalido' => 'Campo ' . $campo . ' com valor inválido',
      'caracteres' => 'Campo ' . $campo . ' excedeu o limite de ' . $quantidade . ' caracteres',
    ];

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }
}