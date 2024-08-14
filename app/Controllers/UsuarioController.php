<?php
namespace app\Controllers;
use app\Models\UsuarioModel;
use app\Controllers\ViewRenderer;

class UsuarioController extends Controller
{
  protected $visao;
  protected $middleware;
  protected $usuarioModel;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/usuario');
    $this->usuarioModel = new UsuarioModel();

    parent::__construct($this->usuarioModel);
  }

  public function usuariosVer()
  {
    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);

    $condicoes = [
      'Usuario.empresa_id' => $this->empresaPadraoId,
    ];

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
    $this->visao->renderizar('/index');
  }

  public function adicionar(array $params = []): array
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }

    $dados = array_merge($dados, ['empresa_id' => $this->empresaPadraoId]);

    // Adiciona usuário
    $resultado = $this->usuarioModel->adicionar($dados);

    // Requisição interna
    if ($params and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif ($params and isset($resultado['id'])) {
      $condicao = [
        'Usuario.id' => $resultado['id'],
        'Usuario.empresa_id' => $this->empresaPadraoId,
      ];

      $colunas = [
        'Usuario.id',
        'Usuario.ativo',
        'Usuario.nivel',
        'Usuario.empresa_id',
        'Usuario.padrao',
        'Usuario.nome',
        'Usuario.email',
        'Usuario.telefone',
        'Usuario.criado',
        'Usuario.modificado',
      ];

      $usuario = $this->usuarioModel->condicao($condicao)
                                    ->buscar($colunas);
      
      return reset($usuario);
    }

    // Formulário via POST
    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/usuario/adicionar');
      exit();
    }
    elseif ($_POST and isset($resultado['id'])) { 
      $_SESSION['ok'] = 'Usuário criado com sucesso';

      header('Location: /dashboard/usuario/editar/' . $resultado['id']);
      exit();
    }
    
    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function usuarioEditarVer(int $id)
  {
    $id = (int) $id;

    $condicao = [
      'Usuario.id' => $id,
      'Usuario.empresa_id' => $this->empresaPadraoId,
    ];

    $colunas = [
      'Usuario.id',
      'Usuario.ativo',
      'Usuario.nivel',
      'Usuario.empresa_id',
      'Usuario.padrao',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.telefone',
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
    $this->visao->renderizar('/editar');
  }

  public function usuarioAdicionarVer()
  {
    $this->visao->variavel('titulo', 'Adicionar usuário');
    $this->visao->renderizar('/adicionar');
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao = [
        'Usuario.id' => $id,
        'Usuario.empresa_id' => $this->empresaPadraoId,
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
      'Usuario.telefone',
      'Usuario.criado',
      'Usuario.modificado',
    ];

    $resultado = $this->usuarioModel->condicao($condicao)
                                    ->buscar($colunas);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($id and count($resultado) == 1) {
      $resultado = reset($resultado);
    }

    $this->responderJson($resultado);
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();

    $resultado = $this->usuarioModel->atualizar($json, $id);

    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/usuario/editar/' . $id);
      exit();
    }
    elseif ($_POST) { 
      $_SESSION['ok'] = 'Registro alterado com sucesso';

      header('Location: /dashboard/usuarios');
      exit();
    }

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->usuarioModel->apagarUsuario($id, $this->empresaPadraoId);

    if ($rollback and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($rollback) {
      return $resultado;
    }

    $_SESSION['ok'] = 'Usuário excluído com sucesso';

    $this->responderJson($resultado);
  }
}