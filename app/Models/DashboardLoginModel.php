<?php
namespace app\Models;
use DateTime;
use app\Models\Model;

class DashboardLoginModel extends Model
{
  public $empresaPadraoId;
  public $usuarioLogadoId;

  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, '');
  }

  public function login($params)
  {
    $campos = $this->validarCampos($params);

    if (isset($campos['erro'])) {
      return $campos;
    }

    $sql = 'SELECT
              Usuario.id,
              Usuario.nome,
              Usuario.email,
              Usuario.senha,
              Usuario.empresa_id,
              Usuario.nivel,
              Usuario.padrao,
              Usuario.tentativas_login,
              Usuario.foto,
              Empresa.subdominio AS "Empresa.subdominio",
              Empresa.subdominio_2 AS "Empresa.subdominio_2",
              Empresa.ativo AS "Empresa.ativo",
              Empresa.cor_primaria AS "Empresa.cor_primaria",
              Empresa.url_site AS "Empresa.url_site",
              Empresa.criado AS "Empresa.criado",
              Assinatura.gratis_prazo AS "Assinatura.gratis_prazo",
              Assinatura.asaas_id AS "Assinatura.asaas_id",
              Assinatura.status AS "Assinatura.status"
            FROM
              usuarios AS Usuario
            LEFT JOIN
              empresas AS Empresa ON Usuario.empresa_id = Empresa.id
            LEFT JOIN
              assinaturas AS Assinatura ON Usuario.empresa_id = Assinatura.empresa_id
            WHERE
              Usuario.email = ?
            AND
              Usuario.ativo = ?
            AND
              Usuario.empresa_id = ?
            ORDER BY
              Usuario.id ASC
            LIMIT ?';

    $sqlParam = [
      0 => $campos['email'],
      1 => ATIVO,
      3 => $this->empresaPadraoId,
      4 => 1,
    ];

    $usuario = parent::executarQueryLogin($sql, $sqlParam);

    $loginSucesso = true;

    if (empty($this->empresaPadraoId)) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['id']) or empty($usuario[0]['id'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['email']) or empty($usuario[0]['email'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['senha']) or empty($usuario[0]['senha'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['empresa_id']) or empty($usuario[0]['empresa_id'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['tentativas_login'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['Empresa.subdominio']) or empty($usuario[0]['Empresa.subdominio'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['Assinatura.asaas_id'])) {
      $loginSucesso = false;
    }

    if (! isset($usuario[0]['Assinatura.status'])) {
      $loginSucesso = false;
    }

    if ($loginSucesso == false) {
      $msgErro = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Usuário não encontrado',
        ],
      ];

      return $msgErro;
    }

    if ($usuario[0]['tentativas_login'] >= 10) {
      $msgErro = [
        'erro' => [
          'codigo' => 403,
          'bloqueio' => $usuario[0]['id'],
          'mensagem' => 'Para sua segurança, sua conta foi bloqueada após exceder o número permitido de tentativas de login. Para desbloqueá-la, entre em contato com nossa equipe de suporte enviando um e-mail para suporte@360help.com.br.',
        ],
      ];

      return $msgErro;
    }

    $validarSenha = $this->validarSenha($campos['senha'], $usuario);

    if (isset($validarSenha['erro'])) {
      $this->registrarTentativas($usuario[0]['id']);

      return $validarSenha;
    }

    // Teste grátis expirado
    $id = (int) $usuario[0]['Empresa.ativo'];
    $assinaturaStatus = (int) $usuario[0]['Assinatura.status'];
    $gratisPrazo = $usuario[0]['Assinatura.gratis_prazo'];

    if ($gratisPrazo) {
      $dataHoje = new DateTime('now');
      $dataGratis = new DateTime($gratisPrazo);

      if ((int) $assinaturaStatus == INATIVO and $dataHoje > $dataGratis) {
        $this->sessaoUsuario->definir('teste-expirado-' . $id, true);
      }
      else {
        $this->sessaoUsuario->apagar('teste-expirado-' . $id);
      }
    }

    $this->registrarUltimoLogin($usuario[0]['id']);

    return ['ok' => $usuario[0]];
  }

  private function registrarUltimoLogin(int $id)
  {
    $acesso = [
      'ip' => $_SERVER['REMOTE_ADDR'],
      'url' => $_SERVER['REQUEST_URI'],
      'dataHora' => date('Y-m-d H:i:s'),
      'referer' => $_SERVER['HTTP_REFERER'] ?? '',
      'navegador' => $_SERVER['HTTP_USER_AGENT'],
      'protocolo' => isset($_SERVER['HTTPS']) ? 'HTTPS' : 'HTTP',
      'idSessao' => session_id(),
      'tokenSessao' => bin2hex(random_bytes(32)),
    ];

    $sql = 'UPDATE
              usuarios
            SET
              tentativas_login = ?,
              ultimo_acesso = ?
            WHERE id = ?';

    $sqlParams = [
      0 => 0,
      1 => json_encode($acesso),
      2 => (int) $id,
    ];

    $this->executarQuery($sql, $sqlParams);
  }

  private function registrarTentativas(int $id)
  {
    $sql = 'UPDATE
              usuarios
            SET
              tentativas_login = tentativas_login + ?
            WHERE
              id = ?';

    $sqlParams = [
      0 => 1,
      1 => $id,
    ];

    $this->executarQuery($sql, $sqlParams);
  }

  // --- Métodos auxiliares
  private function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'email' => $params['email'] ?? '',
      'senha' => $params['senha'] ?? '',
    ];

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => [],
      ],
    ];

    foreach ($campos as $chave => $linha):

      if (empty($linha)) {
        // Campos vazios
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'vazio');
      }
      elseif (is_array($linha)) {
        // Previne injection via array
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'invalido');
      }
    endforeach;

    if (empty($msgErro['erro']['mensagem'])) {
      $campos['email'] = filter_Var($campos['email'], FILTER_SANITIZE_EMAIL);
      $emailValidado = filter_Var($campos['email'], FILTER_VALIDATE_EMAIL);
      $campos['senha'] = htmlspecialchars($campos['senha']);

      if (isset($params['email']) and $emailValidado == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'invalido');
      }

      $senhaCaracteres = 50;
      $emailCaracteres = 50;

      if (strlen($campos['senha']) > $senhaCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('senha', 'caracteres', $senhaCaracteres);
      }

      if (strlen($campos['email']) > $emailCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'caracteres', $emailCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'email' => $campos['email'],
      'senha' => $campos['senha'],
    ];

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';
      return $msgErro;
    }

    return $camposValidados;
  }

  private function validarSenha(string $senha, array $usuario): array
  {
    $senhaLogin = $senha;
    $senhaCadastro = $usuario[0]['senha'];

    if (! password_verify(trim($senhaLogin), trim($senhaCadastro))) {
      $msgErro = [
        'erro' => [
          'codigo' => 401,
          'mensagem' => 'Dados de acesso inválidos',
        ],
      ];

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