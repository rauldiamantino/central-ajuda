<?php
namespace app\Controllers\Components;
use Rollbar\Rollbar;
use Rollbar\Payload\Level;
use app\Controllers\DashboardController;
use Google\Cloud\Storage\StorageClient;

class DatabaseFirebaseComponent extends DashboardController
{
  public $bucket;

  public function __construct()
  {
    $credenciais = '../app/Config/firebase.json';
    $storage = new StorageClient(['keyFilePath' => $credenciais]);
    $this->bucket = $storage->bucket(FIREBASE_BUCKET);
  }

  public function adicionarImagem(int $empresaId, array $arquivo, string $nome, array $params = []): bool
  {
    if (empty($empresaId)) {
      return false;
    }

    if (empty($arquivo)) {
      return false;
    }

    if (empty($nome)) {
      return false;
    }

    if (! isset($arquivo['imagem']) or $arquivo['imagem']['error'] !== UPLOAD_ERR_OK) {
      return false;
    }

    // Upload no navegador OK
    $arquivoTemp = $arquivo['imagem']['tmp_name'];

    // Endereço padrão
    $caminhoImagem = $empresaId . '/';

    // Somente para inserção de conteúdo
    $artigoId = $params['artigoId'] ?? 0;
    $conteudoId = $params['conteudoId'] ?? 0;

    if ($artigoId and $conteudoId) {
      $caminhoImagem .= $artigoId . '/' . $conteudoId;
    }

    // Endereço final
    $caminhoImagem .= $nome;

    try {
      $this->bucket->upload(fopen($arquivoTemp, 'r'), ['name' => $caminhoImagem]);

      return true;
    }
    catch (Google\Cloud\Core\Exception\ServiceException $e) {
      registrarLog('Erro no upload', $e->getMessage());
      registrarSentry($e);

      return false;
    }
  }

  public function apagarImagem(int $empresaId, string $nome, array $params = []): bool
  {
    if (empty($empresaId)) {
      return false;
    }

    if (empty($nome)) {
      return false;
    }

    // Endereço padrão
    $caminhoImagem = $empresaId . '/';

    // Somente para remoção de conteúdo
    $artigoId = $params['artigoId'] ?? 0;
    $conteudoId = $params['conteudoId'] ?? 0;

    if ($artigoId and $conteudoId) {
      $caminhoImagem .= $artigoId . '/' . $conteudoId;
    }

    // Endereço final
    $caminhoImagem .= $nome;

    try {
      $objeto = $this->bucket->object($caminhoImagem);

      if (! $objeto->exists()) {
        return true;
      }
      else {
        $objeto->delete();

        return true;
      }
    }
    catch (Google\Cloud\Core\Exception\NotFoundException $e) {
      registrarLog('Arquivo não encontrado', $e->getMessage());
      registrarSentry($e);

      return false;
    }
    catch (Google\Cloud\Core\Exception\ServiceException $e) {
      registrarLog('Erro no serviço', $e->getMessage());
      registrarSentry($e);

      return false;
    }
    catch (\Exception $e) {
      registrarLog('Erro ao apagar', $e->getMessage());
      registrarSentry($e);

      return false;
    }
  }

  public function apagarImagens(int $empresaId, int $artigoId): bool
  {
    if (empty($empresaId)) {
      return false;
    }

    if (empty($artigoId)) {
      return false;
    }

    // Endereço final
    $caminhoImagem = $empresaId . '/' . $artigoId;

    try {
      $objetos = $this->bucket->objects(['prefix' => $caminhoImagem]);

      if (iterator_count($objetos) === 0) {
        return true;
      }

      foreach ($objetos as $linha):
        $linha->delete();
      endforeach;

      return true;
    }
    catch (Google\Cloud\Core\Exception\NotFoundException $e) {
      registrarLog('Arquivos não encontrados', $e->getMessage());
      registrarSentry($e);

      return false;
    }
    catch (Google\Cloud\Core\Exception\ServiceException $e) {
      registrarLog('Erro no serviço', $e->getMessage());
      registrarSentry($e);

      return false;
    }
    catch (\Exception $e) {
      registrarLog('Erro ao apagar', $e->getMessage());
      registrarSentry($e);

      return false;
    }
  }

  public function credenciais()
  {
    $credenciais = [
      'firebase' => [
        'apiKey' => 'AIzaSyBXAg4u_hFmkaEaqifkknJaD4Lnx42EvHE',
        'authDomain' => 'central-ajuda-5f40a.firebaseapp.com',
        'projectId' => 'central-ajuda-5f40a',
        'storageBucket' => 'central-ajuda-5f40a.appspot.com',
        'messagingSenderId' => '83629854813',
        'appId' => '1:83629854813:web:3c99764aef3aba36a27db4'
      ],
    ];

    if ($this->acessoPermitido() == false) {
      // Rollbar::log(Level::ERROR, 'Firebase - Acesso não permitido', $_REQUEST);
      registrarSentry('Firebase - Acesso negado', $_REQUEST, 'warning');

      $this->responderJson('Acesso negado', 403);
    }

    $this->responderJson($credenciais);
  }

  public function uploadLocal()
  {
    header('Content-Type: application/json');

    if (! isset($_FILES['file']) or $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
      http_response_code(400);
      echo json_encode(['error' => 'Nenhum arquivo enviado ou erro no upload.']);
      exit;
    }

    $empresaId = $_POST['empresaId'] ?? 0;
    $artigoId = $_POST['artigoId'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    $caminho = 'img/local/empresa-' . $empresaId;

    if ($artigoId) {
      $caminho .= '/artigo-' . $artigoId;
    }

    if (! is_dir($caminho)) {
      mkdir($caminho, 0777, true);
    }

    $extensao = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if ($tipo == 'logo') {
      $arquivo = $caminho . '/logo.' . $extensao;
    }
    elseif ($tipo == 'favicon') {
      $arquivo = $caminho . '/favicon.' . $extensao;
    }
    else {
      $arquivo = $caminho . '/' . uniqid() . '.' . $extensao;
    }

    if (! move_uploaded_file($_FILES['file']['tmp_name'], $arquivo)) {
      http_response_code(500);
      echo json_encode(['erro' => 'Erro ao mover o arquivo para o diretório de destino.']);
      exit;
    }

    echo json_encode(['ok' => PROTOCOLO . $_SERVER['HTTP_HOST'] . '/' . $arquivo]);
    exit;
  }

  public function apagarLocal()
  {
    header('Content-Type: application/json');

    // Recupera o que vem após localhost/
    $json = json_decode(file_get_contents('php://input'), true);
    $caminhoImagem = $json['caminhoImagem'] ?? '';
    $caminhoImagem = parse_url($caminhoImagem);
    $caminhoImagem = $caminhoImagem['path'];
    $caminhoImagem = ltrim($caminhoImagem, '/');

    if (! $caminhoImagem or ! file_exists($caminhoImagem)) {
      echo json_encode(['ok' => 'Arquivo não existe.']);
      exit;
    }

    if (! unlink($caminhoImagem)) {
      http_response_code(500);
      echo json_encode(['erro' => 'Erro ao apagar o arquivo.']);
      exit;
    }

    echo json_encode(['ok' => 'Arquivo apagado com sucesso.']);
    exit;
  }

  public function apagarArtigosLocal()
  {
    header('Content-Type: application/json');

    $json = json_decode(file_get_contents('php://input'), true);
    $caminhoPasta = $json['caminhoPasta'] ?? '';
    $caminhoPasta = str_replace('imagens', 'img/local', $caminhoPasta);

    if (empty($caminhoPasta)) {
      echo json_encode(['success' => false, 'message' => 'Caminho não fornecido']);
      exit;
    }

    if (! is_dir($caminhoPasta)) {
      echo json_encode(['success' => false, 'message' => 'Diretório do artigo não encontrado']);
      exit;
    }

    $sucesso = true;
    $arquivos = scandir($caminhoPasta);
    foreach ($arquivos as $arquivo):

      if (in_array($arquivo, ['.', '..'])) {
        continue;
      }

      if (! unlink($caminhoPasta . '/' . $arquivo)) {
        $sucesso = false;
      }
    endforeach;

    if ($sucesso == false) {
      echo json_encode(['erro' => 'Erro ao apagar os arquivos']);
      exit;
    }

    rmdir($caminhoPasta);
    echo json_encode(['ok' => 'Arquivos excluídos localmente']);
    exit;
  }
}