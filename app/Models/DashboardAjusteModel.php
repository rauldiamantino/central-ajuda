<?php
namespace app\Models;
use app\Models\Model;

class DashboardAjusteModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Ajuste');
  }

  // --- CRUD ---
  public function atualizarAjustes(array $json = []): array
  {
    $permitidos = [
      'artigo_autor',
      'botao_whatsapp',
      'publico_cate_busca',
      'publico_cate_abrir_primeira',
      'publico_topo_fixo',
      'publico_inicio_template',
      'publico_inicio_template_alinhamento',
      'publico_inicio_titulo',
      'publico_inicio_subtitulo',
      'publico_inicio_busca_tamanho',
      'publico_inicio_busca_alinhamento',
      'publico_inicio_foto',
      'publico_inicio_texto_cor',
      'publico_inicio_busca_cor',
      'publico_inicio_busca_borda',
    ];

    $total = 0;
    $campos = [];
    foreach ($permitidos as $linha) :

      if (isset($json[ $linha ])) {
        $campos[ $linha ] = $json[ $linha ];
      }
    endforeach;

    foreach ($campos as $chave => $valor):

      // Previne injection de robô
      if (is_array($valor)) {
        continue;
      }

      $camposAtualizar = [
        'nome' => $chave,
        'valor' => $valor,
      ];

      $campos = $this->validarCampos($camposAtualizar, true);

      if (isset($campos['erro'])) {
        return $campos;
      }

      $sql = 'INSERT INTO
                ajustes (nome, valor, empresa_id)
              VALUES
                (?, ?, ?) ON DUPLICATE KEY
              UPDATE
                valor =
              VALUES
                (valor)';

      $sqlParams = [
        0 => $campos['nome'],
        1 => $campos['valor'],
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

  public function buscarAjustes()
  {
    $colunas = [
      'Ajuste.nome',
      'Ajuste.valor',
    ];

    $buscar = parent::selecionar($colunas)
                    ->executarConsulta();

    $resultado = [
      ['Ajuste' => ['nome' => 'artigo_autor', 'valor' => ATIVO]],
      ['Ajuste' => ['nome' => 'botao_whatsapp', 'valor' => ATIVO]],
      ['Ajuste' => ['nome' => 'publico_cate_busca', 'valor' => ATIVO]],
      ['Ajuste' => ['nome' => 'publico_cate_abrir_primeira', 'valor' => INATIVO]],
      ['Ajuste' => ['nome' => 'publico_topo_fixo', 'valor' => INATIVO]],
      ['Ajuste' => ['nome' => 'publico_inicio_template', 'valor' => 1]],
      ['Ajuste' => ['nome' => 'publico_inicio_template_alinhamento', 'valor' => 1]],
      ['Ajuste' => ['nome' => 'publico_inicio_titulo', 'valor' => 'Olá, como podemos te ajudar hoje?']],
      ['Ajuste' => ['nome' => 'publico_inicio_subtitulo', 'valor' => 'Explore nossos guias, tutoriais e artigos para encontrar rapidamente as informações que você precisa.']],
      ['Ajuste' => ['nome' => 'publico_inicio_busca_tamanho', 'valor' => 2]],
      ['Ajuste' => ['nome' => 'publico_inicio_busca_alinhamento', 'valor' => 2]],
      ['Ajuste' => ['nome' => 'publico_inicio_foto', 'valor' => '']],
      ['Ajuste' => ['nome' => 'publico_inicio_texto_cor', 'valor' => '#000000']],
      ['Ajuste' => ['nome' => 'publico_inicio_busca_cor', 'valor' => '#000000']],
      ['Ajuste' => ['nome' => 'publico_inicio_busca_borda', 'valor' => '']],
    ];

    // Substitui ajuste conforme DB
    foreach ($buscar as $chave => $linha):
      foreach ($resultado as $subChave => $subLinha):

        if ($linha['Ajuste']['nome'] == $subLinha['Ajuste']['nome']) {
          $resultado[ $subChave ]['Ajuste']['valor'] = $linha['Ajuste']['valor'];
        }
      endforeach;
    endforeach;

    return $resultado;
  }

  // --- Métodos auxiliares
  private function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'nome' => $params['nome'] ?? '',
      'valor' => $params['valor'] ?? '',
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
        'valor',
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
      $campos['valor'] = htmlspecialchars($campos['valor']);
      $campos['nome'] = htmlspecialchars($campos['nome']);
      $campos['empresa_id'] = filter_var($campos['empresa_id'], FILTER_SANITIZE_NUMBER_INT);

      $ativoCaracteres = 110;
      $nomeCaracteres = 255;
      $empresaIdCaracteres = 999999999;

      if (strlen($campos['valor']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('valor', 'caracteres', $ativoCaracteres);
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
      'valor' => $campos['valor'],
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