<?php
namespace app\Controllers\Components;
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

  public function adicionarImagem(int $empresaId, array $arquivo, array $params = []): bool
  {
    if (empty($empresaId)) {
      return false;
    }

    if (empty($arquivo)) {
      return false;
    }

    if (! isset($arquivo['tmp_name']) or $arquivo['error'] !== UPLOAD_ERR_OK) {
      return false;
    }

    $arquivoTemp = $arquivo['tmp_name'];
    $conteudoArquivo = file_get_contents($arquivoTemp);
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

    // Endereço padrão
    $caminhoImagem = $empresaId . '/';

    // Somente para inserção de conteúdo
    $artigoId = $params['artigoId'] ?? 0;
    $conteudoId = $params['conteudoId'] ?? 0;
    $nome = $params['nome'] ?? 0;
    $imagemAtual = $params['imagemAtual'] ?? '';

    if ($artigoId and $conteudoId) {
      $caminhoImagem .= $artigoId . '/' . $conteudoId;
    }
    elseif ($nome) {
      $caminhoImagem .= $nome;
    }

    // Local
    if (HOST_LOCAL) {

      // Apaga antes
      if ($imagemAtual and file_exists('img/local/' . $imagemAtual)) {
        unlink('img/local/' . $imagemAtual);
      }

      $diretorio = 'img/local/' . dirname($caminhoImagem);

      if (! is_dir($diretorio)) {
         mkdir($diretorio, 0777, true);
      }

      $caminhoFinal = 'img/local/' . $caminhoImagem . '.' . $extensao;
      file_put_contents($caminhoFinal, $conteudoArquivo);
      return true;
    }

    // Produção
    try {
      // Apaga antes
      if ($imagemAtual) {
        $objetoAtual = $this->bucket->object($imagemAtual);

        if ($objetoAtual->exists()) {
          $objetoAtual->delete();
        }
      }

      $this->bucket->upload($conteudoArquivo, [
        'name' => $caminhoImagem . '.' . $extensao,
        'metadata' => ['contentType' => $arquivo['type']],
      ]);

      return true;
    }
    catch (Google\Cloud\Core\Exception\ServiceException $e) {
      registrarLog('Erro no upload', $e->getMessage());
      registrarSentry($e);

      return false;
    }
  }

  public function apagarImagem(string $caminhoImagem): bool
  {
    if (empty($caminhoImagem)) {
      return false;
    }

    // Local
    if (HOST_LOCAL) {
      $caminhoLocal = 'img/local/' . $caminhoImagem;

      if (! file_exists($caminhoLocal)) {
        return true;
      }

      if (unlink($caminhoLocal)) {
        return true;
      }

      registrarLog('Erro ao apagar arquivo local', $caminhoLocal);
      return false;
    }

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

    // Local
    if (HOST_LOCAL) {
      $caminhoLocal = 'img/local/' . $caminhoImagem;

      if (! is_dir($caminhoLocal)) {
        return true;
      }

      return $this->apagarPastaRecursiva($caminhoLocal);
    }

    // Produção
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

  // Local
  private function apagarPastaRecursiva(string $caminho): bool
  {
    $arquivos = array_diff(scandir($caminho), ['.', '..']);

    foreach ($arquivos as $arquivo) {
      $arquivoCompleto = $caminho . DIRECTORY_SEPARATOR . $arquivo;

      if (is_dir($arquivoCompleto)) {
        $this->apagarPastaRecursiva($arquivoCompleto);
      }
      else {
        unlink($arquivoCompleto);
      }
    }

    return rmdir($caminho);
  }
}