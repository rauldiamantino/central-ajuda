<?php
namespace app\Models;
use DateTime;
use app\Models\Model;

class DashboardAssinaturaModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Assinatura');
  }

  // --- CRUD ---
  public function adicionar(array $params = []): array
  {
    $campos = $this->validarCampos($params);

    if (isset($campos['erro'])) {
      return $campos;
    }

    return parent::adicionar($campos, true);
  }

  public function buscar(array $params = []): array
  {
    return parent::selecionar($params)
                 ->executarConsulta();
  }

  public function atualizar(array $params, int $id, bool $sistema = false): array
  {
    if (! is_int($id) or empty($id)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'ID não informado',
        ],
      ];

      return $msgErro;
    }

    $atualizar = true;
    $campos = $this->validarCampos($params, $atualizar, $sistema);

    if (isset($campos['erro'])) {
      return $campos;
    }

    $retorno = parent::atualizar($campos, $id);

    return $retorno;
  }

  public function apagar(int $id): array
  {
    if (! is_int($id) or empty($id)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'ID não informado',
        ],
      ];

      return $msgErro;
    }

    return parent::apagar($id);
  }

  // --- Métodos auxiliares
  private function validarCampos(array $params, bool $atualizar = false, bool $sistema = false): array
  {
    $campos = [
      'asaas_id' => $params['asaas_id'] ?? '',
      'status' => intval($params['status'] ?? 0),
      'valor' => floatval($params['valor'] ?? 0.00),
      'ciclo' => $params['ciclo'] ?? '',
      'gratis_prazo' => $params['gratis_prazo'] ?? '',
      'espaco' => intval($params['espaco'] ?? 0),
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
        'asaas_id',
        'status',
        'ciclo',
        'valor',
        'gratis_prazo',
        'espaco',
      ];

      if ($atualizar and ! isset($params[ $chave ])) {
        continue;
      }

      if (! in_array($chave, $permitidos) and empty($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'vazio');
      }
    endforeach;

    // Previne injection via array
    foreach ($campos as $chave => $linha):

      if (is_array($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'invalido');
      }
    endforeach;

    if (empty($msgErro['erro']['mensagem'])) {
      $campos['asaas_id'] = htmlspecialchars($campos['asaas_id']);
      $campos['ciclo'] = htmlspecialchars($campos['ciclo']);
      $campos['gratis_prazo'] = htmlspecialchars($campos['gratis_prazo']);
      $campos['status'] = filter_var($campos['status'], FILTER_SANITIZE_NUMBER_INT);
      $campos['valor'] = filter_var($campos['valor'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $campos['espaco'] = filter_var($campos['espaco'], FILTER_SANITIZE_NUMBER_INT);

      if (isset($params['status']) and ! in_array($campos['status'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('status', 'valInvalido');
      }

      if ($campos['valor'] and filter_var($campos['valor'], FILTER_VALIDATE_FLOAT) === false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('valor', 'valInvalido');
      }
      elseif ($campos['valor']) {
        $campos['valor'] = (float) $campos['valor'];
      }

      if ($campos['gratis_prazo']) {

        try {
          $campos['gratis_prazo'] = new DateTime($campos['gratis_prazo']);
          $campos['gratis_prazo'] = $campos['gratis_prazo']->format('Y-m-d H:i:s');
        }
        catch (Exception $e) {
          $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('gratis_prazo', 'valInvalido');
        }
      }

      $asaasIdCaracteres = 255;
      $cicloCaracteres = 50;
      $valorCaracteres = 12;
      $statusCaracteres = 1;
      $asaasStatusCaracteres = 1;
      $gratisPrazoCaracteres = 19;
      $espacoCaracteres = 51200;

      if (strlen($campos['asaas_id']) > $asaasIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('asaas_id', 'caracteres', $asaasIdCaracteres);
      }

      if (strlen($campos['ciclo']) > $cicloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ciclo', 'caracteres', $cicloCaracteres);
      }

      if (strlen($campos['valor']) > $valorCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('valor', 'caracteres', $valorCaracteres);
      }

      if (strlen($campos['status']) > $statusCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('status', 'caracteres', $statusCaracteres);
      }

      if (strlen($campos['gratis_prazo']) > $gratisPrazoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('gratis_prazo', 'caracteres', $gratisPrazoCaracteres);
      }

      if (strlen($campos['espaco']) > $espacoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('espaco', 'caracteres', $espacoCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'asaas_id' => $campos['asaas_id'],
      'status' => $campos['status'],
      'valor' => $campos['valor'],
      'ciclo' => $campos['ciclo'],
      'gratis_prazo' => $campos['gratis_prazo'],
      'espaco' => $campos['espaco'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! array_key_exists($chave, $params)) {
          unset($camposValidados[ $chave ]);
        }
      endforeach;
    }

    if (isset($camposValidados['gratis_prazo']) and empty($camposValidados['gratis_prazo'])) {
      unset($camposValidados['gratis_prazo']);
    }

    if ($sistema == false and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($camposValidados['status'])) {
      unset($camposValidados['status']);
    }

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';

      return $msgErro;
    }

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'asaas_id') {
      $campo = 'ID da Assinatura Asaas';
    }

    if ($campo == 'gratis_prazo') {
      $campo = 'Prazo do teste grátis';
    }

    if ($campo == 'espaco') {
      $campo = 'Espaço de armazenamento';
    }

    $msgErro = [
      'vazio' => 'O campo ' . $campo . ' não pode ser vazio',
      'invalido' => 'Campo ' . $campo . ' com formato inválido',
      'valInvalido' => 'Campo ' . $campo . ' com valor inválido',
      'caracteres' => 'Campo ' . $campo . ' excedeu o limite de ' . $quantidade . ' caracteres',
    ];

    if ($campo == 'telefone') {
      $msgErro['caracteres'] = 'Telefone com tamanho inválido';
    }

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }

  public function calcularConsumoBanco(int $empresa_id): array
  {
    $sql = <<<SQL
            SELECT
                ROUND(SUM(tamanho_artigos), 2) AS `artigos_mb`,
                ROUND(SUM(tamanho_categorias), 2) AS `categorias_mb`,
                ROUND(SUM(tamanho_usuarios), 2) AS `usuarios_mb`,
                ROUND(SUM(tamanho_conteudos), 2) AS `conteudos_mb`,
                ROUND(SUM(tamanho_artigos + tamanho_categorias + tamanho_usuarios + tamanho_conteudos), 2) AS `total_mb`
            FROM (
                -- Tamanho dos artigos
                SELECT
                    empresa_id,
                    SUM(
                        CHAR_LENGTH(titulo) +
                        IFNULL(CHAR_LENGTH(titulo), 0) +
                        4 + 4 + 4 + 4 + 8 + 8 -- Tamanho fixo dos campos INT e TIMESTAMP
                    ) / 1024 / 1024 AS tamanho_artigos,
                    0 AS tamanho_categorias,
                    0 AS tamanho_usuarios,
                    0 AS tamanho_conteudos
                FROM artigos
                WHERE empresa_id = ?
                GROUP BY empresa_id

                UNION ALL

                -- Tamanho das categorias
                SELECT
                    empresa_id,
                    0 AS tamanho_artigos,
                    SUM(
                        CHAR_LENGTH(nome) +
                        IFNULL(CHAR_LENGTH(descricao), 0) +
                        IFNULL(CHAR_LENGTH(icone), 0) +
                        4 + 4 + 4 + 4 + 8 + 8 -- Tamanho fixo dos campos INT e TIMESTAMP
                    ) / 1024 / 1024 AS tamanho_categorias,
                    0 AS tamanho_usuarios,
                    0 AS tamanho_conteudos
                FROM categorias
                WHERE empresa_id = ?
                GROUP BY empresa_id

                UNION ALL

                -- Tamanho dos usuários
                SELECT
                    empresa_id,
                    0 AS tamanho_artigos,
                    0 AS tamanho_categorias,
                    SUM(
                        IFNULL(CHAR_LENGTH(nome), 0) +
                        CHAR_LENGTH(email) +
                        CHAR_LENGTH(senha) +
                        IFNULL(LENGTH(ultimo_acesso), 0) +
                        4 + 4 + 4 + 4 + 4 + 1 + 8 + 8 -- Tamanho fixo de INT, TINYINT e TIMESTAMP
                    ) / 1024 / 1024 AS tamanho_usuarios,
                    0 AS tamanho_conteudos
                FROM usuarios
                WHERE empresa_id = ?
                GROUP BY empresa_id

                UNION ALL

                -- Tamanho dos conteudos
                SELECT
                    empresa_id,
                    0 AS tamanho_artigos,
                    0 AS tamanho_categorias,
                    0 AS tamanho_usuarios,
                    SUM(
                        CHAR_LENGTH(titulo) +
                        CHAR_LENGTH(conteudo) +
                        IFNULL(CHAR_LENGTH(url), 0) +
                        4 + 4 + 4 + 4 + 8 + 8 -- Tamanho fixo dos campos INT e TIMESTAMP
                    ) / 1024 / 1024 AS tamanho_conteudos
                FROM conteudos
                WHERE empresa_id = ?
                GROUP BY empresa_id
            ) AS resultados
            GROUP BY empresa_id;
           SQL;

    $sqlParams = [ $empresa_id, $empresa_id, $empresa_id, $empresa_id ];
    $busca = $this->executarQuery($sql, $sqlParams);

    if (is_array($busca) and ! isset($busca['erro'])) {
      $busca = $this->organizarResultado($busca);
    }
    else {
      $busca = [];;
    }

    return $busca;
  }
}