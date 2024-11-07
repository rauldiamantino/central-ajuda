<?php
namespace app\Models;
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
      'assinatura_id',
      'sessao_stripe_id',
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
    ];

    $resultado = $this->selecionar($colunas)
                      ->condicao($condicoes)
                      ->executarConsulta();

    return $resultado;
  }

  public function atualizar(array $params, int $id, bool $webhook = false): array
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
    $campos = $this->validarCampos($params, $atualizar, $webhook);

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
  private function validarCampos(array $params, bool $atualizar = false, bool $webhook = false): array
  {
    $campos = [
      'ativo' => $params['ativo'] ?? 0,
      'nome' => $params['nome'] ?? '',
      'subdominio' => $params['subdominio'] ?? '',
      'telefone' => $params['telefone'] ?? '',
      'logo' => $params['logo'] ?? '',
      'favicon' => $params['favicon'] ?? '',
      'cnpj' => $params['cnpj'] ?? '',
      'sessao_stripe_id' => $params['sessao_stripe_id'] ?? '',
      'assinatura_id' => $params['assinatura_id'] ?? '',
      'plano_nome' => $params['plano_nome'] ?? '',
      'plano_valor' => $params['plano_valor'] ?? 0,
      'protocolo' => $params['protocolo'] ?? '',
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
        'logo',
        'favicon',
        'sessao_stripe_id',
        'assinatura_id',
        'plano_nome',
        'plano_valor',
        'protocolo',
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
      $campos['subdominio'] = htmlspecialchars($campos['subdominio']);
      $campos['telefone'] = filter_var($campos['telefone'], FILTER_SANITIZE_NUMBER_INT);
      $cnpjValido = preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/', $campos['cnpj']);
      $campos['logo'] = filter_var($campos['logo'], FILTER_SANITIZE_URL);
      $campos['favicon'] = filter_var($campos['favicon'], FILTER_SANITIZE_URL);
      $campos['sessao_stripe_id'] = htmlspecialchars($campos['sessao_stripe_id']);
      $campos['assinatura_id'] = htmlspecialchars($campos['assinatura_id']);
      $campos['plano_nome'] = htmlspecialchars($campos['plano_nome']);
      $campos['plano_valor'] = htmlspecialchars($campos['plano_valor']);
      $campos['protocolo'] = htmlspecialchars($campos['protocolo']);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      if (isset($params['cnpj']) and $cnpjValido) {
        $campos['cnpj'] = preg_replace('/[^0-9]/', '', $campos['cnpj']);
      }
      elseif (isset($params['cnpj']) and $params['cnpj'] != '') {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('cnpj', 'valInvalido');
      }

      if ($campos['logo'] == 'undefined') {
        $campos['logo'] = '';
      }

      if ($campos['favicon'] == 'undefined') {
        $campos['favicon'] = '';
      }

      if ($campos['logo'] and filter_var($campos['logo'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('logo', 'valInvalido');
      }

      if ($campos['favicon'] and filter_var($campos['favicon'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('favicon', 'valInvalido');
      }

      if ($campos['plano_nome'] and ! in_array($campos['plano_nome'], ['Mensal', 'Anual'])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('plano_nome', 'valInvalido');
      }

      if ($campos['plano_valor'] and ! preg_match('/^\d{1,8}(\.\d{1,2})?$/', $campos['plano_valor'])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('plano_valor', 'valInvalido');
      }

      if ($campos['protocolo'] and ! preg_match('/^\d{8}\d{6}#\d+$/', $campos['protocolo'])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('protocolo', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nomeCaracteres = 255;
      $subdominioCaracteres = 255;
      $telefoneCaracteresMin = 10;
      $telefoneCaracteresMax = 11;
      $logoCaracteres = 255;
      $faviconCaracteres = 255;
      $sessaoStripeCaracteres = 255;
      $assinaturaId = 255;
      $planoNomeCaracteres = 6;
      $planoValorCaracteres = 12;
      $protocoloCaracteres = 255;

      if (strlen($campos['sessao_stripe_id']) > $sessaoStripeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $sessaoStripeCaracteres);
      }

      if (strlen($campos['assinatura_id']) > $assinaturaId) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $assinaturaId);
      }

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['subdominio']) > $subdominioCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio', 'caracteres', $subdominioCaracteres);
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

      if (strlen($campos['plano_nome']) > $planoNomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('plano_nome', 'caracteres', $planoNomeCaracteres);
      }

      if (strlen($campos['plano_valor']) > $planoValorCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('plano_valor', 'caracteres', $planoValorCaracteres);
      }

      if (strlen($campos['protocolo']) > $protocoloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('protocolo', 'caracteres', $protocoloCaracteres);
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
      'telefone' => $campos['telefone'],
      'cnpj' => $campos['cnpj'],
      'logo' => $campos['logo'],
      'favicon' => $campos['favicon'],
      'sessao_stripe_id' => $campos['sessao_stripe_id'],
      'assinatura_id' => $campos['assinatura_id'],
      'plano_nome' => $campos['plano_nome'],
      'plano_valor' => $campos['plano_valor'],
      'protocolo' => $campos['protocolo'],
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
      $camposValidados['logo'] = null;
    }

    if (isset($camposValidados['favicon']) and empty($camposValidados['favicon'])) {
      $camposValidados['favicon'] = null;
    }

    if (isset($camposValidados['sessao_stripe_id']) and empty($camposValidados['sessao_stripe_id'])) {
      $camposValidados['sessao_stripe_id'] = null;
    }

    if (isset($camposValidados['assinatura_id']) and empty($camposValidados['assinatura_id'])) {
      $camposValidados['assinatura_id'] = null;
    }

    if (isset($camposValidados['assinatura_id']) and $camposValidados['assinatura_id']) {
      $camposValidados['sessao_stripe_id'] = null;
    }

    if ($webhook == false and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($camposValidados['ativo'])) {
      unset($camposValidados['ativo']);
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

    if ($campo == 'sessao_stripe_id') {
      $campo = 'Sessao ID do Stripe';
    }

    if ($campo == 'assinatura_id') {
      $campo = 'Assinatura ID';
    }

    if ($campo == 'plano_nome') {
      $campo = 'nome do plano';
    }

    if ($campo == 'plano_valor') {
      $campo = 'valor do plano';
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
}