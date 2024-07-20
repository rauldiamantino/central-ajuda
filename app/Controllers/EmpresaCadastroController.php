<?php
namespace app\Controllers;

use \Exception;
use app\Controllers\EmpresaController;
use app\Controllers\UsuarioController;
use app\Models\EmpresaCadastroModel;
use app\Models\EmpresaModel;

class EmpresaCadastroController extends Controller
{
  private $empresaController;
  private $usuarioController;
  private $empresaCadastroModel;
  private $empresaModel;

  public function __construct()
  {
    $this->empresaController = new EmpresaController();
    $this->usuarioController = new UsuarioController();
    $this->empresaCadastroModel = new EmpresaCadastroModel();
    $this->empresaModel = new EmpresaModel();
  }

  // --- CRUD ---
  public function adicionar(): void
  {
    $dados = $this->receberJson();
    $dadosEmpresa = $dados['empresa'] ?? [];
    $dadosUsuario = $dados['empresa']['usuario'] ?? [];

    $msg_erro = [];

    if (empty($dadosEmpresa)) {
      $msg_erro['erro'][] = 'Informações da empresa ausentes';
    }

    if (empty($dadosUsuario)) {
      $msg_erro['erro'][] = 'Informações do usuário ausentes';
    }

    if ($msg_erro) {
      $this->responderJson($msg_erro, 400);
    }

    header('Content-Type: application/json');

    try {
      $empresa = $this->adicionarEmpresa($dadosEmpresa);
      $usuario = $this->adicionarUsuario($dadosUsuario, $empresa['Empresa.id']);

      $resultado = [
        'empresa' => $empresa,
        'usuario' => $usuario,
      ];

      $this->responderJson($resultado);
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public function buscar(int $id): void
  {
    $condicao = [
      'Empresa.id' => (int) $id,
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.nome',
      'Empresa.cnpj',
      'Empresa.criado',
      'Empresa.modificado',
      'Usuario.id',
      'Usuario.ativo',
      'Usuario.nivel',
      'Usuario.empresa_id',
      'Usuario.padrao',
      'Usuario.nome',
      'Usuario.email',
      'Usuario.telefone',
    ];

    $uniao = [
      'Usuario',
    ];

    $empresa = $this->empresaModel->condicao($condicao)
                                  ->uniao($uniao)
                                  ->buscar($colunas);

    if (isset($empresa['erro'])) {
      $this->responderJson($empresa, 404);
    }

    $params = [
      'base' => [
        'Empresa' => [],
        'Usuario' => [],
      ],
      'dados' => $empresa[0] ?? [],
    ];

    $resultado = $this->empresaCadastroModel->buscar($params);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  // --- Métodos auxiliares ---
  private function adicionarEmpresa(array $empresa): array
  {
    $resultado = $this->empresaController->adicionar($empresa);

    if (! isset($resultado['Empresa.id'])) {
      $this->lancarExcecao('empresa', $resultado);
    }

    return $resultado;
  }

  private function adicionarUsuario(array $usuario, int $empresa_id): array
  {
    $usuario['empresa_id'] = $empresa_id;
    $resultado = $this->usuarioController->adicionar($usuario);

    if (! isset($resultado['Usuario.id'])) {
      $this->empresaController->apagar($empresa_id, true);
      $this->lancarExcecao('usuario', $resultado);
    }

    return $resultado;
  }

  private function lancarExcecao(string $tipo, array $dados): void
  {
    $codigo = $dados['erro']['codigo'] ?? 500;
    http_response_code($codigo);

    $dados = [ $tipo => $dados ];
    $msgErro = json_encode($dados, JSON_FORMATADO);

    throw new Exception($msgErro);
  }
}