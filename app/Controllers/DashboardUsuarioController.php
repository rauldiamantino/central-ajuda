<?php
namespace app\Controllers;
use app\Models\DashboardUsuarioModel;

class DashboardUsuarioController extends DashboardController
{
  protected $usuarioModel;

  public function __construct()
  {
    parent::__construct();

    $this->usuarioModel = new DashboardUsuarioModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function usuariosVer()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $condicoes = [];

    // Oculta usuários de suporte
    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $condicoes[] = [
        'campo' => 'Usuario.padrao',
        'operador' => '!=',
        'valor' => USUARIO_SUPORTE,
      ];
    }

    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);

    // Recupera quantidade de páginas
    $usuariosTotal = $this->usuarioModel->contar('Usuario.id')
                                        ->condicao($condicoes)
                                        ->executarConsulta();

    $usuariosTotal = $usuariosTotal['total'] ?? 0;
    $paginasTotal = ceil($usuariosTotal / $limite);

    $pagina = abs($pagina);
    $pagina = max($pagina, 1);
    $pagina = min($pagina, $paginasTotal);

    $colunas = [
      'Usuario.id',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.padrao',
      'Usuario.nivel',
      'Usuario.criado',
      'Usuario.ativo',
      'Usuario.tentativas_login',
    ];

    $ordem = [
      'Usuario.id' => 'DESC',
    ];

    $resultado = $this->usuarioModel->selecionar($colunas)
                                    ->condicao($condicoes)
                                    ->pagina($limite, $pagina)
                                    ->ordem($ordem)
                                    ->executarConsulta();

    // Calcular início e fim do intervalo
    $intervaloInicio = 0;
    $intervaloFim = 0;

    if ($usuariosTotal) {
      $intervaloInicio = ($pagina - 1) * $limite + 1;
      $intervaloFim = min($pagina * $limite, $usuariosTotal);
    }

    $this->visao->variavel('usuarios', $resultado);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('usuariosTotal', $usuariosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Usuários');
    $this->visao->variavel('paginaMenuLateral', 'usuarios');
    $this->visao->renderizar('/usuario/index');
  }

  public function usuarioEditarVer(int $id)
  {
    if ($this->usuarioLogado['id'] == 2 and $this->usuarioLogado['id'] != $id) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $id = (int) $id;

    $condicoes[] = [
      'campo' => 'Usuario.id',
      'operador' => '=',
      'valor' => (int) $id,
    ];

    // Impede acesso a usuário de suporte
    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $condicoes[] = [
        'campo' => 'Usuario.padrao',
        'operador' => '!=',
        'valor' => USUARIO_SUPORTE,
      ];
    }

    $colunas = [
      'Usuario.id',
      'Usuario.ativo',
      'Usuario.nivel',
      'Usuario.empresa_id',
      'Usuario.padrao',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.tentativas_login',
      'Usuario.ultimo_acesso',
      'Usuario.criado',
      'Usuario.modificado',
    ];

    $usuario = $this->usuarioModel->selecionar($colunas)
                                  ->condicao($condicoes)
                                  ->executarConsulta();

    if (isset($usuario['erro']) and $usuario['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuarios', $usuario['erro']);
    }

    if (empty($usuario)) {
      $mensagem = 'Usuário não encontrado.';
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuarios', $mensagem);
    }

    $this->visao->variavel('usuario', reset($usuario));
    $this->visao->variavel('titulo', 'Editar usuario');
    $this->visao->renderizar('/usuario/editar/index');
  }

  public function usuarioAdicionarVer()
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $this->visao->variavel('titulo', 'Adicionar usuário');
    $this->visao->variavel('paginaMenuLateral', 'usuarios');
    $this->visao->renderizar('/usuario/adicionar/index');
  }

  public function desbloquear(int $id)
  {
    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuarios', 'Você não tem permissão para realizar esta ação.');
    }

    $json = [
      'tentativas_login' => 0,
    ];

    $resultado = $this->usuarioModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/editar/' . $id, $resultado['erro']);
    }

    $this->sessaoUsuario->apagar('acessoBloqueado-' . $id);
    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/editar/' . $id, 'Acesso desbloqueado com sucesso');
  }

  public function adicionar(): array
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $dados = $this->receberJson();
    $resultado = $this->usuarioModel->adicionar($dados);

    // Formulário via POST
    if (! REQUISICAO_FETCH and isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/adicionar', $resultado['erro']);
    }
    elseif (! REQUISICAO_FETCH and isset($resultado['id'])) {
      $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuarios', 'Usuário criado com sucesso');
    }

    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->sessaoUsuario->definir('ok', 'Usuário criado com sucesso');
    $this->responderJson($resultado);
  }

  public function atualizar(int $id)
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO and $this->usuarioLogado['id'] != $id) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $json = $this->receberJson();
    $resultado = $this->usuarioModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/editar/' . $id, $resultado['erro']);
    }

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/editar/' . $id, 'Registro alterado com sucesso');
  }

  public function apagar(int $id)
  {
    if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard', 'Você não tem permissão para realizar esta ação.');
    }

    $resultado = $this->usuarioModel->apagarUsuario($id);

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->definir('erro', $resultado['erro']['mensagem'] ?? $resultado['erro']);

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->sessaoUsuario->definir('ok', 'Usuário excluído com sucesso');
    $this->responderJson($resultado);
  }
}