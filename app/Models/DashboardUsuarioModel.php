<?php
namespace app\Models;
use app\Models\Model;

class DashboardUsuarioModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Usuario');
  }

  // --- CRUD ---
  public function adicionar(array $params = []): array
  {
    $campos = $this->validarCampos($params);

    if (isset($campos['erro'])) {
      return $campos;
    }

    // Apenas um usuário padrão por empresa
    if ($campos['padrao'] == USUARIO_PADRAO) {
      $condicao = [
        'Usuario.padrao' => USUARIO_PADRAO,
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

    // Evita atribuir padrão de suporte
    if (isset($campos['padrao']) and $campos['padrao'] == USUARIO_SUPORTE and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $msgErro = [
        'erro' => [
          'codigo' => 401,
          'mensagem' => 'Você não tem permissão para realizar esta ação.',
        ],
      ];

      return $msgErro;
    }

    // Evita adicionar usuário com nível superior ao que está criando
    if (isset($campos['nivel']) and $campos['nivel'] < $this->usuarioLogado['nivel']) {
      $msgErro = [
        'erro' => [
          'codigo' => 401,
          'mensagem' => 'Você não tem permissão para realizar esta ação.',
        ],
      ];

      return $msgErro;
    }

    // Cria usuário de Suporte somente na loja 1 - padrao
    if (isset($campos['padrao']) and $campos['padrao'] == USUARIO_SUPORTE and $this->usuarioLogado['empresaId'] > 1) {
      $msgErro = [
        'erro' => [
          'codigo' => 401,
          'mensagem' => 'Você não tem permissão para realizar esta ação.',
        ],
      ];

      return $msgErro;
    }

    return parent::adicionar($campos, true);
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

    $condicao[] = [
      'campo' => 'Usuario.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Usuario.id',
      'Usuario.nivel',
      'Usuario.padrao',
    ];

    $usuarioEditado = $this->selecionar($colunas)
                           ->condicao($condicao)
                           ->executarConsulta();

    if (! isset($usuarioEditado[0]['Usuario']['id'])) {
      $msgErro = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Usuário não encontrado',
        ],
      ];

      return $msgErro;
    }

    // Alterar senha
    $novaSenha = $params['senha'] ?? '';
    $senhaAtual = $params['senha_atual'] ?? '';

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

    // Validar
    $atualizar = true;
    $campos = $this->validarCampos($params, $atualizar);

    if (isset($campos['erro'])) {
      return $campos;
    }

    $permissoes = $this->validarPermissoes($campos, $usuarioEditado, $id);

    if (isset($permissoes['erro'])) {
      return $permissoes;
    }

    $resultado = parent::atualizar($campos, $id);

    // Atualiza sessão do usuário
    if (! isset($resultado['erro']) and $this->usuarioLogado['id'] == $id) {
      $condicoes[] = [
        'campo' => 'Usuario.id',
        'operador' => '=',
        'valor' => $id,
      ];

      $colunas = [
        'Usuario.id',
        'Usuario.nome',
        'Usuario.email',
        'Usuario.empresa_id',
        'Usuario.nivel',
        'Usuario.padrao',
        'Empresa.subdominio',
        'Empresa.ativo',
      ];

      $juntar = [
        'tabelaJoin' => 'Empresa',
        'campoA' => 'Empresa.id',
        'campoB' => 'Usuario.empresa_id',
      ];

      $usuario = $this->selecionar($colunas)
                      ->juntar($juntar)
                      ->condicao($condicoes)
                      ->executarConsulta();

      if (isset($usuario[0]['Usuario']['id'])) {
        $this->sessaoUsuario->definir('usuario', [
          'id' => $usuario[0]['Usuario']['id'],
          'nome' => $usuario[0]['Usuario']['nome'],
          'email' => $usuario[0]['Usuario']['email'],
          'empresaId' => $usuario[0]['Usuario']['empresa_id'],
          'empresaAtivo' => $usuario[0]['Empresa']['ativo'],
          'subdominio' => $usuario[0]['Empresa']['subdominio'],
          'nivel' => $usuario[0]['Usuario']['nivel'],
          'padrao' => $usuario[0]['Usuario']['padrao'],
        ]);
      }
    }

    return $resultado;
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

    $condicao[] = [
      'campo' => 'Usuario.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $colunas = [
      'Usuario.id',
      'Usuario.nivel',
      'Usuario.padrao',
    ];

    $usuarioEditado = $this->selecionar($colunas)
                           ->condicao($condicao)
                           ->executarConsulta();

    if (! isset($usuarioEditado[0]['Usuario']['id'])) {
      $msgErro = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Usuário não encontrado',
        ],
      ];

      return $msgErro;
    }

    // Evita editar usuário de Suporte
    if ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_SUPORTE and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $msgErro = [
        'erro' => [
          'codigo' => 401,
          'mensagem' => 'Você não tem permissão para realizar esta ação.',
        ],
      ];

      return $msgErro;
    }

    // Evita remover o próprio usuário
    if ($this->usuarioLogado['id'] == $id) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Não é possível remover o próprio usuário',
        ],
      ];

      return $msgErro;
    }

    // Evita remover usuário padrão
    if ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_PADRAO) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Não é permitido apagar o usuário padrão',
        ],
      ];

      return $msgErro;
    }

    // Usuário possui artigos
    $condicoes[] = [
      'campo' => 'Usuario.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $juntar = [
      'tabelaJoin' => 'Artigo',
      'campoA' => 'Artigo.usuario_id',
      'campoB' => 'Usuario.id',
    ];

    $usuarioArtigos = parent::contar('Usuario.id')
                            ->condicao($condicoes)
                            ->juntar($juntar)
                            ->executarConsulta();

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
      'tentativas_login' => $params['tentativas_login'] ?? 0,
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
        'tentativas_login',
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
      $campos['tentativas_login'] = filter_var($campos['tentativas_login'], FILTER_SANITIZE_NUMBER_INT);
      $campos['nome'] = htmlspecialchars($campos['nome']);
      $campos['email'] = filter_Var($campos['email'], FILTER_SANITIZE_EMAIL);
      $emailValidado = filter_Var($campos['email'], FILTER_VALIDATE_EMAIL);

      // Sempre primeiro
      if (isset($campos['senha'])) {
        $msgErro = $this->validarSenhaSegura($campos['senha']);
        $campos['senha'] = password_hash(trim($campos['senha']), PASSWORD_DEFAULT);
      }

      if (isset($params['email']) and $emailValidado == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'invalido');
      }

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      // 0 - Suporte, 1 - Padrão, 2 - Comum
      if (isset($params['padrao']) and ! in_array($campos['padrao'], [USUARIO_SUPORTE, USUARIO_PADRAO, USUARIO_COMUM])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('padrao', 'valInvalido');
      }

      // 1 - Total, 2 - Restrito
      if (isset($params['nivel']) and ! in_array($campos['nivel'], [USUARIO_TOTAL, USUARIO_RESTRITO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nivel', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nivelCaracteres = 1;
      $empresaIdCaracteres = 999999999;
      $padraoCaracteres = 2;
      $tentativasCaracteres = 20;
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

      if (strlen($campos['tentativas_login']) > $tentativasCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('tentativas_login', 'caracteres', $tentativasCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['email']) > $emailCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('email', 'caracteres', $emailCaracteres);
      }
    }

    if (isset($msgErro['erro']['mensagem']) and $msgErro['erro']['mensagem']) {
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
      'tentativas_login' => $campos['tentativas_login'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! array_key_exists($chave, $params)) {
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

    $condicoes[] = [
      'campo' => 'Usuario.id',
      'operador' => '=',
      'valor' => (int) $id,
    ];

    $colunas = [
      'Usuario.id',
      'Usuario.senha',
    ];

    $usuario = $this->selecionar($colunas)
                    ->condicao($condicoes)
                    ->executarConsulta();

    if (empty($usuario)) {
      $msgErro = [
        'erro' => [
          'codigo' => 404,
          'mensagem' => 'Usuário não encontrado',
        ],
      ];

      return $msgErro;
    }

    $senhaCadastro = $usuario[0]['Usuario']['senha'] ?? '';

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

  private function validarSenhaSegura(string $senha): array
  {
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

  private function validarPermissoes(array $campos, array $usuarioEditado, int $id): array
  {
    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => '',
      ],
    ];

    $resultado = [];

    if (isset($campos['padrao']) and $campos['padrao'] == USUARIO_PADRAO) {
      $condicao = [
        ['campo' => 'Usuario.padrao', 'operador' => '=', 'valor' => USUARIO_PADRAO],
        ['campo' => 'Usuario.id', 'operador' => '!=', 'valor' => (int) $id],
      ];

      $resultado = $this->contar('Usuario.id')
                        ->condicao($condicao)
                        ->executarConsulta();
    }

    if (isset($resultado['total']) and (int) $resultado['total'] > 0) {
      // Apenas um usuário padrão por empresa
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('padrao', 'permissaoCampo');
    }
    elseif ($usuarioEditado[0]['Usuario']['nivel'] < $this->usuarioLogado['nivel']) {
      // Evita editar usuário de nível superior
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('', 'permissao');
    }
    elseif ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_SUPORTE and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      // Evita editar usuário de Suporte
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('', 'permissao');
    }
    elseif ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($campos['padrao']) and $campos['padrao'] == USUARIO_SUPORTE) {
      // Evita atribuir padrão de suporte
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('', 'permissao');
    }
    elseif ($this->usuarioLogado['id'] == $id and isset($campos['ativo']) and $campos['ativo'] == INATIVO) {
      // Evita desativar o próprio usuário
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('', 'permissaoDesativarProprio');
    }
    elseif ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_PADRAO and isset($campos['ativo']) and $campos['ativo'] == INATIVO) {
      // Evita desativar usuário padrão
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('padrao', 'permissaoDesativar');
    }
    elseif ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_PADRAO and isset($campos['nivel']) and $campos['nivel'] != $usuarioEditado[0]['Usuario']['nivel']) {
      // Evita alterar nível de usuário padrão
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('nivel', 'permissaoCampo');
    }
    elseif ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_PADRAO and isset($campos['padrao']) and $campos['padrao'] != $usuarioEditado[0]['Usuario']['padrao']) {
      // Evita alterar o tipo do usuário padrão
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('padrao', 'permissaoCampo');
    }
    elseif ($usuarioEditado[0]['Usuario']['padrao'] == USUARIO_SUPORTE and isset($campos['padrao']) and $campos['padrao'] != $usuarioEditado[0]['Usuario']['padrao']) {
      // Evita alterar o tipo do usuário de suporte
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('padrao', 'permissaoCampo');
    }
    elseif ($this->usuarioLogado['id'] == $id and isset($campos['nivel']) and $campos['nivel'] != $this->usuarioLogado['nivel']) {
      // Evita alterar o próprio nível de usuário
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('nivel', 'permissaoCampoProprio');
    }
    elseif ($this->usuarioLogado['id'] == $id and isset($campos['padrao']) and $campos['padrao'] != $this->usuarioLogado['padrao']) {
      // Evita alterar o próprio tipo de usuário
      $msgErro['erro']['mensagem'] = $this->gerarMsgErro('padrao', 'permissaoCampoProprio');
    }
    else {
      return [];
    }

    return $msgErro;
  }

  private function gerarMsgErro(string $campo = '', string $tipo = '', int $quantidade = 0): string
  {
    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
    }

    if ($campo == 'padrao') {
      $campo = 'padrão';
    }

    if ($campo == 'tentativas_login') {
      $campo = 'tentativas de login';
    }

    if ($campo == 'nivel') {
      $campo = 'nível de acesso';
    }

    if ($campo == 'padrao' and $tipo == 'permissaoDesativar') {
      $campo = 'padrão';
    }
    elseif ($campo == 'padrao') {
      $campo = 'tipo';
    }

    $msgErro = [
      'vazio' => 'O campo ' . $campo . ' não pode ser vazio',
      'invalido' => 'Campo ' . $campo . ' com formato inválido',
      'valInvalido' => 'Campo ' . $campo . ' com valor inválido',
      'caracteres' => 'Campo ' . $campo . ' excedeu o limite de ' . $quantidade . ' caracteres',
      'permissao' => 'Você não tem permissão para realizar esta ação',
      'permissaoCampo' => 'Não é permitido alterar o ' . $campo . ' deste usuário',
      'permissaoCampoProprio' => 'Não é permitido alterar o ' . $campo . ' do próprio usuário',
      'permissaoDesativar' => 'Não é permitido desativar o usuário ' . $campo,
      'permissaoDesativarProprio' => 'Não é permitido desativar o próprio usuário',
    ];

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }
}