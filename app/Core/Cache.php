<?php
namespace app\Core;

class Cache
{
  public static function definir(string $nome, array $dados, int $tempo, int $empresaId = 0)
  {
    $diretorio = 'cache/';

    if ($empresaId) {
      $diretorio .= $empresaId . '/';
    }

    if (! is_dir($diretorio)) {
      mkdir($diretorio, 0777, true);
    }

    $arquivo = $diretorio . $nome . '.txt';

    $dadosComTempo = [
      'validade' => time() + $tempo,
      'dados' => $dados
    ];

    file_put_contents($arquivo, serialize($dadosComTempo));
  }

  public static function buscar(string $nome, int $empresaId = 0)
  {
    $diretorio = 'cache/';

    if ($empresaId) {
      $diretorio .= $empresaId . '/';
    }

    $arquivo = $diretorio . $nome . '.txt';

    if (! file_exists($arquivo)) {
      return null;
    }

    $dados = unserialize(file_get_contents($arquivo));

    if (time() > $dados['validade']) {
      unlink($arquivo);
      return null;
    }

    return $dados['dados'];
  }

  public static function apagar(string $nome, int $empresaId = 0)
  {
    $diretorio = 'cache/';

    if ($empresaId) {
      $diretorio .= $empresaId . '/';
    }

    $arquivo = $diretorio . $nome . '.txt';

    if (file_exists($arquivo)) {
      unlink($arquivo);
    }
  }

  // Usar com muita atenção
  public static function apagarTodos(string $nome, int $empresaId)
  {
    if (empty($empresaId)) {
      return;
    }

    $diretorio = 'cache/' . $empresaId . '/';
    $padrao = $diretorio . $nome . '*.txt';
    $arquivos = glob($padrao);

    foreach ($arquivos as $arquivo) {

      if (file_exists($arquivo)) {
        unlink($arquivo);
      }
    }
  }

}
