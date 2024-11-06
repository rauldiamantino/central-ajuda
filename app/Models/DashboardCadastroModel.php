<?php
namespace app\Models;
use app\Models\Model;

class DashboardCadastroModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, '');;
  }

  public function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'email' => $params['email'] ?? '',
      'subdominio' => $params['subdominio'] ?? '',
      'senha' => $params['senha'] ?? '',
      'confirmar_senha' => $params['confirmar_senha'] ?? '',
      'plano_nome' => $params['plano_nome'] ?? '',
      'protocolo' => $params['protocolo'] ?? '',
    ];

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => [],
      ],
    ];

    foreach ($campos as $chave => $linha):
      $permitidos = [
        'protocolo',
      ];

      if (! in_array($chave, $permitidos) and empty($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'vazio');
      }

      // Previne injection via array
      if (is_array($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'invalido');
      }
    endforeach;

    if (empty($msgErro['erro']['mensagem'])) {
      $campos['subdominio'] = htmlspecialchars($campos['subdominio']);
      $campos['email'] = filter_Var($campos['email'], FILTER_SANITIZE_EMAIL);
      $emailValidado = filter_Var($campos['email'], FILTER_VALIDATE_EMAIL);
      $campos['plano_nome'] = htmlspecialchars($campos['plano_nome']);
      $campos['protocolo'] = htmlspecialchars($campos['protocolo']);

      if (isset($params['email']) and $emailValidado == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'invalido');
      }

      if ($campos['senha'] !== $campos['confirmar_senha']) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('confirmar_senha', 'valInvalido');
      }

      if (! in_array($campos['plano_nome'], ['Mensal', 'Anual'])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('plano_nome', 'valInvalido');
      }

      if ($campos['protocolo'] and ! preg_match('/^\d{8}\d{6}#\d+$/', $campos['protocolo'])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('protocolo', 'valInvalido');
      }

      $emailCaracteres = 50;
      $subdominioCaracteres = 15;
      $senhaCaracteres = 50;
      $planoCaracteres = 6;
      $protocoloCaracteres = 255;

      if (strlen($campos['subdominio']) > $subdominioCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio', 'caracteres', $subdominioCaracteres);
      }

      if (strlen($campos['email']) > $emailCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'caracteres', $emailCaracteres);
      }

      if (strlen($campos['senha']) > $senhaCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('senha', 'caracteres', $senhaCaracteres);
      }

      if (strlen($campos['plano_nome']) > $planoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('plano_nome', 'caracteres', $planoCaracteres);
      }

      if (strlen($campos['protocolo']) > $protocoloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('protocolo', 'caracteres', $protocoloCaracteres);
      }

      $campos['senha'] = password_hash(trim($campos['senha']), PASSWORD_DEFAULT);
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => ATIVO,
      'nivel' => USUARIO_TOTAL,
      'padrao' => USUARIO_PADRAO,
      'subdominio' => $campos['subdominio'],
      'email' => $campos['email'],
      'senha' => $campos['senha'],
      'plano_nome' => $campos['plano_nome'],
      'protocolo' => $campos['protocolo'],
    ];

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';
      return $msgErro;
    }

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
    }

    if ($campo == 'padrao') {
      $campo = 'padrão';
    }

    if ($campo == 'subdominio') {
      $campo = 'subdomínio';
    }

    if ($campo == 'plano_nome') {
      $campo = 'nome do plano';
    }

    $msgErro = [
      'vazio' => 'O campo ' . $campo . ' não pode ser vazio',
      'invalido' => 'Campo ' . $campo . ' com formato inválido',
      'valInvalido' => 'Campo ' . $campo . ' com valor inválido',
      'caracteres' => 'Campo ' . $campo . ' excedeu o limite de ' . $quantidade . ' caracteres',
    ];

    if ($campo == 'confirmar_senha') {
      $msgErro['valInvalido'] = 'As senhas precisam ser iguais';
    }

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }

  public function gerarEmpresa(string $subdominio, string $planoNome, string $procolo): int
  {
    $sql = 'INSERT INTO `empresas` (`ativo`, `subdominio`, `plano_nome`, `plano_valor`, `protocolo`) VALUES (?, ?, ?, ?, ?)';

    $params = [
      0 => 0,
      1 => $subdominio,
      2 => $planoNome,
      3 => 0.00, // plano_valor
      4 => $procolo,
    ];

    $resultado = parent::executarQuery($sql, $params);

    return $resultado['id'] ?? 0;
  }

  public function apagarEmpresa(int $empresaId): void
  {
    $sql = 'DELETE FROM empresas WHERE id = ?';

    $params = [
      0 => $empresaId,
    ];

    parent::executarQuery($sql, $params);
  }

  public function gravarSessaoStripe(int $empresaId, string $sessaoId, string $sessaoValor = '0.00'): void
  {
    $sql = 'UPDATE `empresas` SET `sessao_stripe_id` = ?, `plano_valor` = ? WHERE id = ?';

    $params = [
      0 => $sessaoId,
      1 => $sessaoValor,
      2 => $empresaId,
    ];

    parent::executarQuery($sql, $params);
  }

  public function usuarioExiste(string $email): int
  {
    $sql = 'SELECT 1 FROM usuarios WHERE email = ? LIMIT 1';
    $params = [ $email ];

    $resultado = parent::executarQuery($sql, $params);

    return boolval($resultado);
  }

  public function gerarUsuarioPadrao(array $params = []): array
  {
    $camposSucesso = true;

    // Revalida por segurança
    if (! isset($params['ativo']) or empty($params['ativo'])) {
      $camposSucesso = false;
    }

    if (! isset($params['nivel']) or empty($params['nivel'])) {
      $camposSucesso = false;
    }

    if (! isset($params['padrao']) or empty($params['padrao'])) {
      $camposSucesso = false;
    }

    if (! isset($params['email']) or empty($params['email'])) {
      $camposSucesso = false;
    }

    if (! isset($params['senha']) or empty($params['senha'])) {
      $camposSucesso = false;
    }

    if (! isset($params['empresa_id']) or empty($params['empresa_id'])) {
      $camposSucesso = false;
    }

    if ($camposSucesso == false) {
      return 0;
    }

    $sql = 'INSERT INTO
              usuarios (ativo, nivel, empresa_id, padrao, email, senha)
            VALUES
              (?, ?, ?, ?, ?, ?);';

    $params = [
      0 => $params['ativo'],
      1 => $params['nivel'],
      2 => $params['empresa_id'],
      3 => $params['padrao'],
      4 => $params['email'],
      5 => $params['senha'],
    ];

    $resultado = parent::executarQuery($sql, $params);

    if (! isset($resultado['id']) or empty($resultado['id'])) {

    }

    $sql = 'SELECT
              Usuario.id, Usuario.nome, Usuario.email, Usuario.empresa_id
            FROM
              usuarios AS Usuario
            WHERE
              Usuario.id = ?
            ORDER BY Usuario.id ASC
            LIMIT 1';

    $params = [ $resultado['id'] ];

    $resultado = parent::executarQuery($sql, $params);

    return $resultado;
  }
}