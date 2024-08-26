<?php
namespace app\Models;
use app\Models\Model;

class DashboardLoginModel extends Model
{
  public $empresaPadraoId;
  public $usuarioLogadoId;

  public function __construct()
  {
    parent::__construct('Login');
  }

  public function login($params)
  {
    $campos = $this->validarCampos($params);

    if (isset($campos['erro'])) {
      return $campos;
    }

    $sql = 'SELECT 
              `Usuario`.`id`, `Usuario`.`nome`, `Usuario`.`email`, `Usuario`.`senha`, `Usuario`.`empresa_id`
            FROM
              `usuarios` AS `Usuario`
            WHERE
              `Usuario`.`email` = ?
            AND
              `Usuario`.`ativo` = ?
            ORDER BY
              `Usuario`.`id`ASC
            LIMIT 1';

    $sqlParam = [
      0 => $campos['email'],
      1 => 1,
    ];

    $usuario = parent::executarQueryLogin($sql, $sqlParam);

    $loginSucesso = true;

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

    if ($loginSucesso == false) {
      $msgErro = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Usuário não encontrado',
        ],
      ];

      return $msgErro;
    }

    $validarSenha = $this->validarSenha($campos['senha'], $usuario);

    if (isset($validarSenha['erro'])) {
      return $validarSenha;
    }

    // Aplica login
    $_SESSION['usuario'] = [
      'id' => $usuario[0]['id'],
      'nome' => $usuario[0]['nome'],
      'email' => $usuario[0]['email'],
      'empresa_id' => $usuario[0]['empresa_id'],
    ];

    return ['ok' => true];
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