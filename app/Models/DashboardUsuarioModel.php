<?php
namespace app\Models;
use app\Models\Model;

class DashboardUsuarioModel extends Model
{
  public function __construct()
  {
    parent::__construct('Usuario');
  }

  // --- CRUD ---
  public function adicionar(array $params = []): array
  {
    $campos = $this->validarCampos($params);

    if (isset($campos['erro'])) {
      return $campos;
    }

    // Apenas um usuário padrão por empresa
    if ($campos['padrao'] == 1) {
      $condicao = [
        'Usuario.padrao' => 1,
      ];

      $usuarioPadrao = parent::condicao($condicao)
                             ->contar('Usuario.id');

      if (isset($usuarioPadrao['total']) and (int) $usuarioPadrao['total'] > 0) {
        $msgErro = [
          'erro' => [
            'codigo' => 400,
            'mensagem' => 'Já existe um usuário padrão',
          ],
        ];

        return $msgErro;
      }
    }

    // Revisar para tornar dinâmico
    if ($campos['padrao'] == 0 or $campos['nivel'] == 0) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Usuário inválido',
        ],
      ];

      return $msgErro;
    }

    return parent::adicionar($campos, true);
  }

  public function buscar(array $params = []): array
  {
    return parent::buscar($params);
  }

  public function atualizar(array $params, int $id): array
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

    $novaSenha = $params['senha'] ?? '';
    $senhaAtual = $params['senha_atual'] ?? '';

    // Apenas se preencher nova senha
    if ($novaSenha or $senhaAtual) {
      $senhaValidada = $this->validarSenha($params, $id);

      if (isset($senhaValidada['erro'])) {
        return $senhaValidada;
      }
    }
    
    if (empty($novaSenha) and isset($params['senha'])) {
      unset($params['senha']);
    }
    
    if (empty($senhaValidada) and isset($params['senha_atual'])) {
      unset($params['senha_atual']);
    }

    $atualizar = true;
    $campos = $this->validarCampos($params, $atualizar);

    if (isset($campos['erro'])) {
      return $campos;
    }

    // Apenas um usuário padrão por empresa
    if ($campos['padrao'] == 1) {
      $condicao = [
        'Usuario.padrao' => 1,
        'Usuario.id !=' => $id,
      ];

      $usuarioPadrao = parent::condicao($condicao)
                             ->contar('Usuario.id');

      if (isset($usuarioPadrao['total']) and (int) $usuarioPadrao['total'] > 0) {
        $msgErro = [
          'erro' => [
            'codigo' => 400,
            'mensagem' => 'Já existe um usuário padrão',
          ],
        ];

        return $msgErro;
      }
    }

    if ($campos['padrao'] == 1 and isset($campos['ativo'])) {
      unset($campos['ativo']);
    }

    // Revisar para tornar dinâmico
    if ($campos['padrao'] == 0 or $campos['nivel'] == 0) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Usuário inválido',
        ],
      ];

      return $msgErro;
    }

    return parent::atualizar($campos, $id);
  }

  public function apagarUsuario(int $id): array
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

    // Usuário padrão
    $condicoes = [
      'Usuario.id' => $id,
      'Usuario.padrao' => 1,
    ];

    $usuarioPadrao = parent::condicao($condicoes)
                           ->contar('Usuario.id');

    if (isset($usuarioPadrao['total']) and (int) $usuarioPadrao['total'] > 0) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Não é permitido apagar o usuário padrão',
        ],
      ];

      return $msgErro;
    }

    // Usuário possui artigos
    $condicoes = [
      'Usuario.id' => $id,
    ];

    $uniao = [
      'Artigo',
    ];

    $usuarioArtigos = parent::condicao($condicoes)
                            ->uniao($uniao)
                            ->contar('Usuario.id');

    if (isset($usuarioArtigos['total']) and (int) $usuarioArtigos['total'] > 0) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Este usuário possui artigos publicados, não é possível apagá-lo',
        ],
      ];

      return $msgErro;
    }


    return parent::apagar($id);
  }

  // --- Métodos auxiliares
  private function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'ativo' => $params['ativo'] ?? 0,
      'nivel' => $params['nivel'] ?? 0,
      'empresa_id' => $this->empresaPadraoId,
      'padrao' => $params['padrao'] ?? 0,
      'nome' => $params['nome'] ?? '',
      'email' => $params['email'] ?? '',
      'senha' => $params['senha'] ?? '',
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
        'nivel',
        'nome',
        'padrao',
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
    endforeach;

    // Previne injection via array
    foreach ($campos as $chave => $linha):

      if (is_array($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'invalido');
      }
    endforeach;

    if (empty($msgErro['erro']['mensagem'])) {
      $campos['ativo'] = filter_var($campos['ativo'], FILTER_SANITIZE_NUMBER_INT);
      $campos['nivel'] = filter_var($campos['nivel'], FILTER_SANITIZE_NUMBER_INT);
      $campos['empresa_id'] = filter_var($campos['empresa_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['padrao'] = filter_var($campos['padrao'], FILTER_SANITIZE_NUMBER_INT);
      $campos['nome'] = htmlspecialchars($campos['nome']);
      $campos['email'] = filter_Var($campos['email'], FILTER_SANITIZE_EMAIL);
      $emailValidado = filter_Var($campos['email'], FILTER_VALIDATE_EMAIL);

      if (isset($params['email']) and $emailValidado == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'invalido');
      }

      if (isset($campos['senha'])) {
        $campos['senha'] = password_hash(trim($campos['senha']), PASSWORD_DEFAULT);
      }

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [0, 1])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      // 0 - Suporte, 1 - Padrão, 2 - Comum
      if (isset($params['padrao']) and ! in_array($campos['padrao'], [0, 1, 2])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('padrao', 'valInvalido');
      }

      // 0 - Suporte, 1 - Total, 2 - Restrito
      if (isset($params['nivel']) and ! in_array($campos['nivel'], [0, 1, 2])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nivel', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nivelCaracteres = 1;
      $empresaIdCaracteres = 999999999;
      $padraoCaracteres = 1;
      $nomeCaracteres = 25;
      $emailCaracteres = 50;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['nivel']) > $nivelCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nivel', 'caracteres', $nivelCaracteres);
      }

      if (strlen($campos['empresa_id']) > $empresaIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('empresa_id', 'caracteres', $empresaIdCaracteres);
      }

      if (strlen($campos['padrao']) > $padraoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('padrao', 'caracteres', $padraoCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['email']) > $emailCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'caracteres', $emailCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'nivel' => $campos['nivel'],
      'empresa_id' => $campos['empresa_id'],
      'padrao' => $campos['padrao'],
      'nome' => $campos['nome'],
      'email' => $campos['email'],
      'senha' => $campos['senha'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! isset($params[ $chave ])) {
          unset($camposValidados[ $chave ]);
        }
      endforeach;
    }

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';
      return $msgErro;
    }

    return $camposValidados;
  }

  private function validarSenha(array $params, int $id): array
  {
    $erroSenha = false;
    $novaSenha = $params['senha'] ?? '';
    $senhaAtual = $params['senha_atual'] ?? '';

    if ($novaSenha and empty($senhaAtual)) {
      $erroSenha = true;
    }
    elseif ($senhaAtual and empty($novaSenha)) {
      $erroSenha = true;
    }

    if ($erroSenha) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Para alterar a senha, é necessário preencher os campos Senha atual e Nova senha',
        ],
      ];

      return $msgErro;
    }

    $usuario = parent::condicao(['Usuario.id' => $id])
                     ->buscar(['Usuario.id', 'Usuario.senha']);

    if (empty($usuario)) {
      $msgErro = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Usuário não encontrado',
        ],
      ];

      return $msgErro;
    }

    $senhaCadastro = $usuario[0]['Usuario.senha'] ?? '';

    if (! password_verify(trim($senhaAtual), trim($senhaCadastro))) {
      $msgErro = [
        'erro' => [
          'codigo' => 401,
          'mensagem' => 'Senha atual incorreta',
        ],
      ];

      return $msgErro;
    }

    return [];
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

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }
}