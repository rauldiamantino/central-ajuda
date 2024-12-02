<?php
namespace app\Models;
use DateTime;
use app\Models\Model;

class DashboardEmpresaModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Empresa');
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

  public function buscarEmpresaSemId(string $coluna, $valor): array
  {
    $permitidas = [
      'id',
      'subdominio',
      'subdominio_2',
    ];

    if (! in_array($coluna, $permitidas)) {
      return [];
    }

    // Previne injection
    $valor = htmlspecialchars($valor);

    if (is_array($valor)) {
      $valor = '';
    }

    $condicoes[] = [
      'campo' => 'Empresa.' . $coluna,
      'operador' => '=',
      'valor' => $valor,
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.subdominio',
      'Empresa.subdominio_2',
      'Empresa.assinatura_id_asaas',
      'Empresa.assinatura_status',
      'Empresa.gratis_prazo',
    ];

    $resultado = $this->selecionar($colunas)
                      ->condicao($condicoes)
                      ->executarConsulta();

    return $resultado;
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

    // CNPJ duplicado
    if (isset($campos['cnpj']) and $campos['cnpj'] !== null) {
      $condicao = [
        ['campo' => 'Empresa.id', 'operador' => '!=', 'valor' => $id],
        ['campo' => 'Empresa.cnpj', 'operador' => '=', 'valor' => $campos['cnpj']],
      ];

      $colunas = [
        'Empresa.id',
      ];

      $resultado = parent::selecionar($colunas)
                         ->condicao($condicao)
                         ->executarConsulta();

      if (isset($resultado[0]['Empresa']['id'])) {
        $msgErro = [
          'erro' => [
            'codigo' => 400,
            'mensagem' => 'O CNPJ <span class="font-semibold">' . $params['cnpj'] . '</span> não pode ser utilizado, pois está associado a outro cadastro',
          ],
        ];

        return $msgErro;
      }
    }

    // Não permite alterar subdominio
    if (isset($campos['subdominio'])) {
      unset($campos['subdominio']);
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
      'ativo' => $params['ativo'] ?? 0,
      'nome' => $params['nome'] ?? '',
      'subdominio' => $params['subdominio'] ?? '',
      'subdominio_2' => $params['subdominio_2'] ?? '',
      'telefone' => $params['telefone'] ?? '',
      'logo' => $params['logo'] ?? '',
      'favicon' => $params['favicon'] ?? '',
      'cnpj' => $params['cnpj'] ?? '',
      'assinatura_id_asaas' => $params['assinatura_id_asaas'] ?? '',
      'assinatura_status' => $params['assinatura_status'] ?? 0,
      'assinatura_ciclo' => $params['assinatura_ciclo'] ?? '',
      'assinatura_valor' => $params['assinatura_valor'] ?? 0.00,
      'gratis_prazo' => $params['gratis_prazo'] ?? '',
      'cor_primaria' => intval($params['cor_primaria'] ?? 1),
      'url_site' => $params['url_site'] ?? '',
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
        'nome',
        'cnpj',
        'telefone',
        'subdominio',
        'subdominio_2',
        'logo',
        'favicon',
        'assinatura_id_asaas',
        'assinatura_status',
        'assinatura_ciclo',
        'assinatura_valor',
        'gratis_prazo',
        'cor_primaria',
        'url_site',
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
      $campos['ativo'] = filter_var($campos['ativo'], FILTER_SANITIZE_NUMBER_INT);
      $campos['nome'] = htmlspecialchars($campos['nome']);
      $campos['logo'] = htmlspecialchars($campos['logo']);
      $campos['favicon'] = htmlspecialchars($campos['favicon']);
      $campos['subdominio'] = htmlspecialchars($campos['subdominio']);
      $campos['subdominio_2'] = filter_var($campos['subdominio_2'], FILTER_SANITIZE_URL);
      $campos['telefone'] = filter_var($campos['telefone'], FILTER_SANITIZE_NUMBER_INT);
      $cnpjValido = preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/', $campos['cnpj']);
      $campos['assinatura_id_asaas'] = htmlspecialchars($campos['assinatura_id_asaas']);
      $campos['assinatura_ciclo'] = htmlspecialchars($campos['assinatura_ciclo']);
      $campos['gratis_prazo'] = htmlspecialchars($campos['gratis_prazo']);
      $campos['assinatura_status'] = filter_var($campos['assinatura_status'], FILTER_SANITIZE_NUMBER_INT);
      $campos['cor_primaria'] = filter_var($campos['cor_primaria'], FILTER_SANITIZE_NUMBER_INT);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      if (isset($params['assinatura_status']) and ! in_array($campos['assinatura_status'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('assinatura_status', 'valInvalido');
      }

      if (isset($params['cnpj']) and $cnpjValido) {
        $campos['cnpj'] = preg_replace('/[^0-9]/', '', $campos['cnpj']);
      }
      elseif (isset($params['cnpj']) and $params['cnpj'] != '') {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('cnpj', 'valInvalido');
      }

      if ($campos['url_site'] and filter_var($campos['url_site'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('url_site', 'valInvalido');
      }

      if ($campos['subdominio_2'] and filter_var($campos['subdominio_2'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio_2', 'valInvalido');
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

      $ativoCaracteres = 1;
      $nomeCaracteres = 255;
      $subdominioCaracteres = 255;
      $subdominio2Caracteres = 255;
      $telefoneCaracteresMin = 10;
      $telefoneCaracteresMax = 11;
      $logoCaracteres = 50;
      $faviconCaracteres = 50;
      $urlSiteCaracteres = 255;
      $assinaturaIdAsaasCaracteres = 255;
      $assinaturaCicloCaracteres = 50;
      $assinaturaValorCaracteres = 12;
      $assinaturaStatusCaracteres = 1;
      $gratisPrazoCaracteres = 19;
      $corPrimariaCaracteres = 2;

      if (strlen($campos['assinatura_id_asaas']) > $assinaturaIdAsaasCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('assinatura_id_asaas', 'caracteres', $assinaturaIdAsaasCaracteres);
      }

      if (strlen($campos['assinatura_ciclo']) > $assinaturaCicloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('assinatura_ciclo', 'caracteres', $assinaturaCicloCaracteres);
      }

      if (strlen($campos['assinatura_valor']) > $assinaturaValorCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('assinatura_valor', 'caracteres', $assinaturaValorCaracteres);
      }

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['assinatura_status']) > $assinaturaStatusCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('assinatura_status', 'caracteres', $assinaturaStatusCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['subdominio']) > $subdominioCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio', 'caracteres', $subdominioCaracteres);
      }

      if (strlen($campos['subdominio_2']) > $subdominio2Caracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio_2', 'caracteres', $subdominio2Caracteres);
      }

      if ($campos['telefone'] and strlen($campos['telefone']) > $telefoneCaracteresMax) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('telefone', 'caracteres', $telefoneCaracteresMax);
      }

      if ($campos['telefone'] and strlen($campos['telefone']) < $telefoneCaracteresMin) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('telefone', 'caracteres', $telefoneCaracteresMin);
      }

      if (strlen($campos['logo']) > $logoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('logo', 'caracteres', $logoCaracteres);
      }

      if (strlen($campos['favicon']) > $faviconCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('favicon', 'caracteres', $faviconCaracteres);
      }

      if (strlen($campos['url_site']) > $urlSiteCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('url_site', 'caracteres', $urlSiteCaracteres);
      }

      if (strlen($campos['gratis_prazo']) > $gratisPrazoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('gratis_prazo', 'caracteres', $gratisPrazoCaracteres);
      }

      if (strlen($campos['cor_primaria']) > $corPrimariaCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('cor_primaria', 'caracteres', $corPrimariaCaracteres);
      }

      $campos['cnpj'] = trim($campos['cnpj']);
      $campos['subdominio'] = trim($campos['subdominio']);
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'nome' => $campos['nome'],
      'subdominio' => $campos['subdominio'],
      'subdominio_2' => $campos['subdominio_2'],
      'telefone' => $campos['telefone'],
      'cnpj' => $campos['cnpj'],
      'logo' => $campos['logo'],
      'favicon' => $campos['favicon'],
      'assinatura_id_asaas' => $campos['assinatura_id_asaas'],
      'assinatura_status' => $campos['assinatura_status'],
      'assinatura_ciclo' => $campos['assinatura_ciclo'],
      'assinatura_valor' => $campos['assinatura_valor'],
      'gratis_prazo' => $campos['gratis_prazo'],
      'cor_primaria' => $campos['cor_primaria'],
      'url_site' => $campos['url_site'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! array_key_exists($chave, $params)) {
          unset($camposValidados[ $chave ]);
        }
      endforeach;
    }

    if (isset($camposValidados['cnpj']) and empty($camposValidados['cnpj'])) {
      $camposValidados['cnpj'] = null;
    }

    if (isset($camposValidados['subdominio']) and empty($camposValidados['subdominio'])) {
      $camposValidados['subdominio'] = null;
    }

    if (isset($camposValidados['logo']) and empty($camposValidados['logo'])) {
      unset($camposValidados['logo']);
    }

    if (isset($camposValidados['favicon']) and empty($camposValidados['favicon'])) {
      unset($camposValidados['favicon']);
    }

    if (isset($camposValidados['gratis_prazo']) and empty($camposValidados['gratis_prazo'])) {
      unset($camposValidados['gratis_prazo']);
    }

    if ($sistema == false and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($camposValidados['ativo'])) {
      unset($camposValidados['ativo']);
    }

    if ($sistema == false and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($camposValidados['assinatura_status'])) {
      unset($camposValidados['assinatura_status']);
    }

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';

      return $msgErro;
    }

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'cnpj') {
      $campo = 'CNPJ';
    }

    if ($campo == 'assinatura_id_asaas') {
      $campo = 'Assinatura ID';
    }

    if ($campo == 'assinatura_status') {
      $campo = 'Status da assinatura';
    }

    if ($campo == 'assinatura_ciclo') {
      $campo = 'Ciclo da assinatura';
    }

    if ($campo == 'assinatura_valor') {
      $campo = 'Valor da assinatura';
    }

    if ($campo == 'gratis_prazo') {
      $campo = 'Prazo do teste grátis';
    }

    if ($campo == 'cor_primaria') {
      $campo = 'Cor primária';
    }

    if ($campo == 'url_site') {
      $campo = 'Link para o site';
    }

    if ($campo == 'subdominio_2') {
      $campo = 'Subdomínio personalizado';
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

  public function calcularConsumoBanco(int $id): array
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

    $sqlParams = [
      $id, // empresa_id
      $id, // empresa_id
      $id, // empresa_id
      $id, // empresa_id
    ];

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