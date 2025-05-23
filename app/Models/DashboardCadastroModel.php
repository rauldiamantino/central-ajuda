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
    ];

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => [],
      ],
    ];

    foreach ($campos as $chave => $linha):
      $permitidos = [];

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

      // Sempre primeiro
      if ($campos['senha']) {
        $msgErro = $this->validarSenhaSegura($campos['senha']);
      }

      if ($campos['senha'] !== $campos['confirmar_senha']) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('confirmar_senha', 'valInvalido');
      }

      if (isset($params['email']) and $emailValidado == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'invalido');
      }

      $emailCaracteres = 50;
      $subdominioCaracteres = 15;
      $senhaCaracteres = 50;

      if (strlen($campos['subdominio']) > $subdominioCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio', 'caracteres', $subdominioCaracteres);
      }

      if (strlen($campos['email']) > $emailCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'caracteres', $emailCaracteres);
      }

      if (strlen($campos['senha']) > $senhaCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('senha', 'caracteres', $senhaCaracteres);
      }

      // Gera hash somente após todas as validações
      $campos['senha'] = password_hash(trim($campos['senha']), PASSWORD_DEFAULT);
    }

    if (isset($msgErro['erro']['mensagem']) and $msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => ATIVO,
      'nivel' => USUARIO_TOTAL,
      'padrao' => USUARIO_PADRAO,
      'subdominio' => $campos['subdominio'],
      'email' => $campos['email'],
      'senha' => $campos['senha'],
    ];

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';
      return $msgErro;
    }

    return $camposValidados;
  }

  private function validarSenhaSegura(string $senha): array
  {
    if (HOST_LOCAL) {
      return ['ok' => true];
    }

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => ['Sua senha não atingiu os seguintes critérios:'],
      ],
    ];

    if (strlen($senha) < 8) {
      $msgErro['erro']['mensagem'][] = " • Ter pelo menos 8 caracteres.";
    }

    if (! preg_match('/[A-Z]/', $senha)) {
      $msgErro['erro']['mensagem'][] = " • Conter pelo menos uma letra maiúscula.";
    }

    if (! preg_match('/[a-z]/', $senha)) {
      $msgErro['erro']['mensagem'][] = " • Conter pelo menos uma letra minúscula.";
    }

    if (! preg_match('/\d/', $senha)) {
      $msgErro['erro']['mensagem'][] = " • Conter pelo menos um número.";
    }

    if (! preg_match('/[\W_]/', $senha)) {
      $msgErro['erro']['mensagem'][] = " • Conter pelo menos um caractere especial (ex: !, @, #, $).";
    }

    if (count($msgErro['erro']['mensagem']) > 1) {
      return $msgErro;
    }

    return ['ok' => true];
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

  public function gerarEmpresa(string $subdominio): int
  {
    $sql = 'INSERT INTO `empresas` (`ativo`, `subdominio`) VALUES (?, ?)';

    $params = [
      0 => ATIVO,
      1 => $subdominio,
    ];

    $resultado = parent::executarQuery($sql, $params);

    return $resultado['id'] ?? 0;
  }

  public function gerarAssinatura(int $empresa_id): int
  {
    $sql = 'INSERT INTO `assinaturas` (`gratis_prazo`, `espaco`, `empresa_id`) VALUES (?, ?, ?)';

    $params = [
      0 => date('Y-m-d H:i:s', strtotime('+14 days')),
      1 => 2048, // 2GB
      2 => $empresa_id,
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

  public function apagarUsuario(int $usuarioId, int $empresaId): void
  {
    if (empty($usuarioId) or empty($empresaId)) {
      return;
    }

    $sql = 'DELETE FROM usuarios WHERE id = ? AND empresa_id = ?';

    $params = [
      0 => $usuarioId,
      1 => $empresaId,
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

  public function apagarAssinatura(int $assinaturaId, int $empresaId): void
  {
    $sql = 'DELETE FROM assinaturas WHERE id = ? AND empresa_id = ?';

    $params = [
      0 => $assinaturaId,
      1 => $empresaId,
    ];

    parent::executarQuery($sql, $params);
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
      return [];
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

  public function gerarUsuarioSuporte(array $params = []): array
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
              usuarios (ativo, nivel, empresa_id, padrao, nome, email, senha, foto)
            VALUES
              (?, ?, ?, ?, ?, ?, ?, ?);';

    $params = [
      0 => ATIVO,
      1 => USUARIO_TOTAL,
      2 => $params['empresa_id'],
      3 => USUARIO_SUPORTE,
      4 => 'Suporte 360Help',
      5 => 'suporte@360help.com.br',
      6 => HASH_SUPORTE,
      7 => '1/us-1.webp', // empresa teste
    ];

    $resultado = parent::executarQuery($sql, $params);

    if (! isset($resultado['id']) or empty($resultado['id'])) {
      return [];
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