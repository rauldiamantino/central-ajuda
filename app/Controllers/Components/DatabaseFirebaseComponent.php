<?php
namespace app\Controllers\Components;
use app\Controllers\DashboardController;

class DatabaseFirebaseComponent extends DashboardController
{
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