<?php
namespace app\Controllers;
use app\Models\DashboardUsuarioModel;

class DashboardUsuarioController extends DashboardController
{
  protected $usuarioModel;

  public function __construct()
  {
    parent::__construct();

    $this->usuarioModel = new DashboardUsuarioModel();
  }

  public function usuariosVer()
  {
    if ($this->usuarioLogadoNivel == 2) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
      exit;
    }

    $condicoes = [];

    // Oculta usuários de suporte
    if ($this->usuarioLogadoNivel != 0) {
      $condicoes['Usuario.nivel !='] = 0;
    }

    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);

    // Recupera quantidade de páginas
    $usuariosTotal = $this->usuarioModel->condicao($condicoes)
                                        ->contar('Usuario.id');

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
    ];

    $resultado = $this->usuarioModel->condicao($condicoes)
                                    ->pagina($limite, $pagina)
                                    ->ordem(['Usuario.id' => 'DESC'])
                                    ->buscar($colunas);

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
    $this->visao->renderizar('/usuario/index');
  }

  public function usuarioEditarVer(int $id)
  {
    if ($this->usuarioLogadoId == 2 and $this->usuarioLogadoId != $id) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
      exit;
    }

    $id = (int) $id;

    $condicoes = [
      'Usuario.id' => $id,
    ];

    // Impede acesso a usuário de suporte
    if ($this->usuarioLogadoNivel != 0) {
      $condicoes['Usuario.nivel !='] = 0;
    }

    $colunas = [
      'Usuario.id',
      'Usuario.ativo',
      'Usuario.nivel',
      'Usuario.empresa_id',
      'Usuario.padrao',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.criado',
      'Usuario.modificado',
    ];

    $usuario = $this->usuarioModel->condicao($condicoes)
                                  ->buscar($colunas);

    if (isset($usuario['erro']) and $usuario['erro']) {
      $_SESSION['erro'] = $usuario['erro']['mensagem'] ?? '';

     header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/usuarios');
      exit();
    }

    $this->visao->variavel('usuario', reset($usuario));
    $this->visao->variavel('titulo', 'Editar usuario');
    $this->visao->renderizar('/usuario/editar/index');
  }

  public function usuarioAdicionarVer()
  {
    if ($this->usuarioLogadoNivel == 2) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
      exit;
    }

    $this->visao->variavel('titulo', 'Adicionar usuário');
    $this->visao->renderizar('/usuario/adicionar/index');
  }

  public function adicionar(): array
  {
    if ($this->usuarioLogadoNivel == 2) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
      exit;
    }

    $dados = $this->receberJson();
    $resultado = $this->usuarioModel->adicionar($dados);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/usuario/adicionar');
      exit();
    }

    $_SESSION['ok'] = 'Usuário criado com sucesso';
    header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/usuarios');
    exit();
  }

  public function atualizar(int $id)
  {
    if ($this->usuarioLogadoId == 2 and $this->usuarioLogadoId != $id) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
      exit;
    }

    $json = $this->receberJson();
    $resultado = $this->usuarioModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

     header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/usuario/editar/' . $id);
      exit();
    }

    $_SESSION['ok'] = 'Registro alterado com sucesso';

    header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/usuario/editar/' . $id);
    exit();
  }

  public function apagar(int $id)
  {
    if ($this->usuarioLogadoNivel == 2) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->usuarioLogadoSubdominio . '/dashboard/artigos');
      exit;
    }

    $resultado = $this->usuarioModel->apagarUsuario($id);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $_SESSION['ok'] = 'Usuário excluído com sucesso';
    $this->responderJson($resultado);
  }
}