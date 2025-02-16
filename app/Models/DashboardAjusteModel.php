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
  public function adicionar(array $params = []): array
  {
    $atualizar = true;
    $campos = $this->validarCampos($params, $atualizar);

    if (isset($campos['erro'])) {
      return $campos;
    }

    $sql = <<<SQL
            INSERT INTO
              `ajustes` (`nome`, `valor`, `empresa_id`)
            VALUES
              (?, ?, ?) ON DUPLICATE KEY
            UPDATE `valor` = VALUES (`valor`);
           SQL;

    $sqlParams = [
      0 => $campos['nome'],
      1 => $campos['valor'],
      2 => $campos['empresa_id'],
    ];

    $atualizar = $this->executarQuery($sql, $sqlParams);

    if (isset($atualizar['erro'])) {
      return $atualizar;
    }

    return ['ok' => intval($atualizar['linhasAfetadas'] ?? 0)];
  }

  public function apagarAjuste(array $params): array
  {
    $atualizar = true;
    $campos = $this->validarCampos($params, $atualizar);

    if (isset($campos['erro'])) {
      return $campos;
    }

    if (! isset($campos['nome'])) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Nome do ajuste não informado',
        ],
      ];

      return $msgErro;
    }

    $sql = <<<SQL
            DELETE FROM `ajustes` WHERE `nome` = ? AND `empresa_id` = ?
           SQL;

    $sqlParams = [
      0 => $campos['nome'],
      1 => $campos['empresa_id'],
    ];

    $apagar = $this->executarQuery($sql, $sqlParams);

    if (isset($apagar['erro'])) {
      return $apagar;
    }

    return ['ok' => intval($apagar['linhasAfetadas'] ?? 0)];
  }

  // Formulário com todos os ajustes
  public function atualizarTodos(array $json = []): array
  {
    $permitidos = array_keys($this->ajustesPadroes());

    $campos = [];
    foreach ($permitidos as $linha) :

      if (isset($json[ $linha ])) {
        $campos[ $linha ] = $json[ $linha ];
      }
    endforeach;

    if (empty($campos)) {
      return ['ok' => 0];
    }

    $camposValidados = [];
    foreach ($campos as $chave => $valor):
      $camposAtualizar = [
        'nome' => $chave,
        'valor' => $valor,
      ];

      $campoValidar = $this->validarCampos($camposAtualizar, true);

      if (isset($campoValidar['erro'])) {
        return $campoValidar;
      }

      $camposValidados[] = $campoValidar;
    endforeach;

    $placeholders = str_repeat('(?, ?, ?), ', count($camposValidados));
    $placeholders = rtrim($placeholders, ', ');

    $sql = <<<SQL
            INSERT INTO
              `ajustes` (`nome`, `valor`, `empresa_id`)
            VALUES
              $placeholders ON DUPLICATE KEY
            UPDATE `valor` = VALUES (`valor`);
           SQL;

    $sqlParams = [];
    foreach ($camposValidados as $linha):
      $sqlParams[] = $linha['nome'];
      $sqlParams[] = $linha['valor'];
      $sqlParams[] = $linha['empresa_id'];
    endforeach;

    $atualizar = $this->executarQuery($sql, $sqlParams);

    if (isset($atualizar['erro'])) {
      return $atualizar;
    }

    if (! isset($atualizar['linhasAfetadas']) and isset($atualizar['id'])) {
      $atualizar['linhasAfetadas'] = 1;
    }

    return $atualizar;
  }

  public function buscarTodos()
  {
    $colunas = [
      'Ajuste.nome',
      'Ajuste.valor',
    ];

    $buscar = parent::selecionar($colunas)
                    ->executarConsulta();

    $resultado = $this->ajustesPadroes();
    foreach ($buscar as $linha):

      if (isset($resultado[ $linha['Ajuste']['nome'] ])) {
        $resultado[ $linha['Ajuste']['nome'] ] = $linha['Ajuste']['valor'];
      }
    endforeach;

    return $resultado;
  }

  public function buscar(string $nome)
  {
    if (is_array($nome)) {
      $nome = '';
    }

    $condicao = [
      [
        'campo' => 'Ajuste.nome',
        'operador' => '=',
        'valor' => $nome,
      ],
    ];

    $colunas = [
      'Ajuste.nome',
      'Ajuste.valor',
    ];

    $buscar = parent::selecionar($colunas)
                    ->condicao($condicao)
                    ->limite(1)
                    ->executarConsulta();

    $resultado = $this->ajustesPadroes($nome);

    if (isset($buscar[0]['Ajuste']['nome']) and isset($resultado[ $buscar[0]['Ajuste']['nome'] ])) {
      $resultado = [$nome => $buscar[0]['Ajuste']['valor'] ?? ''];
    }

    return $resultado;
  }

  public function ajustesPadroes(string $ajuste = ''): array
  {
    $padrao = [
      'artigo_autor' => 1,
      'botao_whatsapp' => 1,
      'publico_cate_busca' => 1,
      'publico_cate_abrir_primeira' => 0,
      'publico_topo_fixo' => 0,
      'publico_inicio_foto' => '',
      'publico_cor_primaria' => 1,
      'publico_inicio_texto_cor' => '#000000',
      'publico_inicio_busca' => 1,
      'publico_inicio_template' => 1,
      'publico_inicio_template_alinhamento' => 1,
      'publico_inicio_titulo' => 'Olá, como podemos te ajudar hoje?',
      'publico_inicio_subtitulo' => 'Explore nossos guias, tutoriais e artigos para encontrar rapidamente as informações que você precisa.',
      'publico_inicio_busca_tamanho' => 2,
      'publico_inicio_busca_alinhamento' => 2,
    ];

    if (empty($ajuste)) {
      return $padrao;
    }

    return [$ajuste => $padrao[ $ajuste ] ?? ''];
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