<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardAjusteModel;

class Controller
{
  protected $sessaoUsuario;
  protected $usuarioLogado;
  protected $empresaPadraoId;

  public function __construct()
  {
    $this->recuperarSessao();
  }

  private function recuperarSessao()
  {
    global $sessaoUsuario;
    $this->sessaoUsuario = $sessaoUsuario;

    $resultado = $this->sessaoUsuario->buscar('usuario');

    $this->usuarioLogado = [
      'id' => intval($resultado['id'] ?? 0),
      'nome' => $resultado['nome'] ?? '',
      'email' => $resultado['email'] ?? '',
      'nivel' => intval($resultado['nivel'] ?? 0),
      'padrao' => intval($resultado['padrao'] ?? 0),
      'empresaId' => intval($resultado['empresaId'] ?? 0),
      'empresaAtivo' => intval($resultado['empresaAtivo'] ?? 0),
      'empresaCriado' => $resultado['empresaCriado'] ?? '',
      'gratisPrazo' => $resultado['gratisPrazo'] ?? '',
      'corPrimaria' => intval($resultado['corPrimaria'] ?? 1),
      'urlSite' => $resultado['urlSite'] ?? '',
      'assinaturaIdAsaas' => $resultado['assinaturaIdAsaas'] ?? '',
      'assinaturaStatus' => intval($resultado['assinaturaStatus'] ?? 0),
      'subdominio' => $resultado['subdominio'] ?? '',
      'subdominio_2' => $resultado['subdominio_2'] ?? '',
      'tentativasLogin' => intval($resultado['tentativas_login'] ?? 0),
    ];

    $this->empresaPadraoId = (int) $this->sessaoUsuario->buscar('empresaPadraoId');
  }

  protected function redirecionarErro(string $rota, $mensagem = [], $rotaOriginal = false): void
  {
    if (isset($mensagem['mensagem'])) {
      $mensagem = $mensagem['mensagem'];
    }

    $this->sessaoUsuario->definir('erro', $mensagem);

    if ($rotaOriginal == false) {
      $rota = baseUrl($rota);
    }

    header('Location: ' . $rota);
    exit();
  }

  protected function redirecionarSucesso(string $rota, $mensagem = [], $rotaOriginal = false): void
  {
    if (isset($mensagem['mensagem'])) {
      $mensagem = $mensagem['mensagem'];
    }

    $this->sessaoUsuario->definir('ok', $mensagem);

    if ($rotaOriginal == false) {
      $rota = baseUrl($rota);
    }

    header('Location: ' . $rota);
    exit();
  }

  protected function redirecionar(string $rota, $mensagem = [], $rotaOriginal = false): void
  {
    if (isset($mensagem['mensagem'])) {
      $mensagem = $mensagem['mensagem'];
    }

    if ($mensagem) {
      $this->sessaoUsuario->definir('neutra', $mensagem);
    }

    if ($rotaOriginal == false) {
      $rota = baseUrl($rota);
    }

    header('Location: ' . $rota);
    exit();
  }

  protected function receberJson(): array
  {
    $dados = $_POST;

    if (empty($dados)) {
      $json = file_get_contents("php://input");
      $dados = json_decode(trim($json), true);
    }

    if (json_last_error() != JSON_ERROR_NONE) {
      $this->responderJson(['erro' => 'Requisição Inválida'], 400);
      exit;
    }

    return $dados;
  }

  protected function responderJson(array $dados, int $codigoStatus = 200): void
  {
    header('Content-Type: application/json');
    http_response_code($codigoStatus);
    echo json_encode($dados, JSON_FORMATADO);
    exit;
  }

  protected function renderizarView(string $template, array $dados = [])
  {
    echo $this->view->render($template, $dados);
    exit;
  }

  protected function limparCacheTodos(array $nomes, int $empresaId)
  {
    if (empty($nomes)) {
      return;
    }

    if (empty($empresaId)) {
      return;
    }

    foreach ($nomes as $linha):
      Cache::apagarTodos($linha, $empresaId);
    endforeach;
  }

  protected function acessoPermitido()
  {
    if (! isset($_SERVER['HTTP_REFERER'])) {
      return false;
    }

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $urlPartes = explode('/', $url);
    $empresa = $urlPartes[1] ?? '';

    if ($empresa and $empresa == $this->usuarioLogado['subdominio']) {
      return true;
    }

    return false;
  }

  public function buscarAjuste(string $nome)
  {
    $ajusteModel = new DashboardAjusteModel($this->usuarioLogado, $this->empresaPadraoId, 'Ajuste');

    $cacheTempo = 60 * 60;
    $cacheNome = 'ajustes';
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $ajusteModel->buscarAjustes();
      Cache::definir($cacheNome, $resultado, $cacheTempo, $this->empresaPadraoId);
    }

    foreach ($resultado as $linha):

      if (! isset($linha['Ajuste']['nome'])) {
        continue;
      }

      if (! isset($linha['Ajuste']['ativo'])) {
        continue;
      }

      if ($linha['Ajuste']['nome'] != $nome) {
        continue;
      }

      return (int) $linha['Ajuste']['ativo'];
    endforeach;

    return 0;
  }

  public function buscarIcones()
  {
    $diretorio = './icones';
    $arquivos = array_diff(scandir($diretorio), ['.', '..']);

    $icones = [];
    foreach ($arquivos as $arquivo):

      if (pathinfo($arquivo, PATHINFO_EXTENSION) === 'svg') {
        $icones[] = [
          'nome' => pathinfo($arquivo, PATHINFO_FILENAME),
          'caminho' => PROTOCOLO . $_SERVER['HTTP_HOST'] . '/icones/' . $arquivo,
        ];
      }
    endforeach;

    return $icones;
  }

  public function iconeExiste($iconeCaminho)
  {
    if (filter_var($iconeCaminho, FILTER_VALIDATE_URL)) {
      $cabecalhos = @get_headers($iconeCaminho);
  
      if ($cabecalhos && strpos($cabecalhos[0], '200') !== false) {
        return true;
      }
    }
  
    return false;
  }
}
