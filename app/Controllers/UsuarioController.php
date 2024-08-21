<?php
namespace app\Controllers;
use app\Models\UsuarioModel;
use app\Controllers\ViewRenderer;

class UsuarioController extends Controller
{
  protected $visao;
  protected $usuarioModel;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/usuario');
    $this->usuarioModel = new UsuarioModel();
  }

  public function usuariosVer()
  {
    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);

    // Recupera quantidade de páginas
    $usuariosTotal = $this->usuarioModel->contar('Usuario.id');

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

    $resultado = $this->usuarioModel->pagina($limite, $pagina)
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
    $this->visao->renderizar('/index');
  }

  public function usuarioEditarVer(int $id)
  {
    $id = (int) $id;

    $condicao = [
      'Usuario.id' => $id,
    ];

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

    $usuario = $this->usuarioModel->condicao($condicao)
                                  ->buscar($colunas);
    
    if (isset($usuario['erro']) and $usuario['erro']) {
      $_SESSION['erro'] = $usuario['erro']['mensagem'] ?? '';

     header('Location: /dashboard/usuarios');
      exit();
    }

    $this->visao->variavel('usuario', reset($usuario));
    $this->visao->variavel('titulo', 'Editar usuario');
    $this->visao->renderizar('/editar/index');
  }

  public function usuarioAdicionarVer()
  {
    $this->visao->variavel('titulo', 'Adicionar usuário');
    $this->visao->renderizar('/adicionar/index');
  }

  public function adicionar(): array
  {
    $dados = $this->receberJson();
    $resultado = $this->usuarioModel->adicionar($dados);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/usuario/adicionar');
      exit();
    }
    
    $_SESSION['ok'] = 'Usuário criado com sucesso';
    header('Location: /dashboard/usuarios');
    exit();
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();
    $resultado = $this->usuarioModel->atualizar($json, $id);

    if (isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

     header('Location: /dashboard/usuario/editar/' . $id);
      exit();
    }

    $_SESSION['ok'] = 'Registro alterado com sucesso';

    header('Location: /dashboard/usuarios');
    exit();
  }

  public function apagar(int $id)
  {
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