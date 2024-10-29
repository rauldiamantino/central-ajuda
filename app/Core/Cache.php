<?php

namespace app\Core;

class Cache
{
  private static $memcached;

  public static function iniciarMemcached()
  {
    if (self::$memcached === null) {
      self::$memcached = new \Memcached();

      $host = getenv('MEMCACHED_HOST');

      if (HOST_LOCAL) {
        $host = getenv('MEMCACHED_HOST_DEV');
      }
      $port = getenv('MEMCACHED_PORT');

      // Adiciona o servidor Memcached
      self::$memcached->addServer($host, $port);
    }
  }

  public static function definir(string $nome, array $dados, int $tempo, int $empresaId = 0)
  {
    if (GRAVAR_CACHE == INATIVO) {
      return;
    }

    if (HOST_LOCAL) {
      $diretorio = 'cache/';

      if ($empresaId) {
        $diretorio .= $empresaId . '/';
      }

      if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
      }

      $arquivo = $diretorio . $nome . '.txt';

      $dadosComTempo = [
        'validade' => time() + $tempo,
        'dados' => $dados
      ];

      file_put_contents($arquivo, serialize($dadosComTempo));

      return;
    }

    self::iniciarMemcached();

    if (strpos($nome, '_') !== false) {
      $temp = explode('_', $nome);
      $prefixo = $temp[0];

      $resultado = self::$memcached->get($prefixo . '-' . $empresaId);

      if (! is_array($resultado)) {
        $resultado = [];
      }

      $resultado[] = $nome;

      self::$memcached->set($prefixo . '-' . $empresaId, array_unique($resultado), $tempo);
    }

    self::$memcached->set($empresaId . '-' . $nome, $dados, $tempo);
  }

  public static function buscar(string $nome, int $empresaId = 0)
  {
    if (GRAVAR_CACHE == INATIVO) {
      return null;
    }

    if (HOST_LOCAL) {
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

    self::iniciarMemcached();
    $resultado = self::$memcached->get($empresaId . '-' . $nome);

    if (empty($resultado)) {
      $resultado = null;
    }

    return $resultado;
  }

  public static function apagar(string $nome, int $empresaId = 0)
  {
    if (GRAVAR_CACHE == INATIVO) {
      return null;
    }

    if (HOST_LOCAL) {
      $diretorio = 'cache/';

      if ($empresaId) {
        $diretorio .= $empresaId . '/';
      }

      $arquivo = $diretorio . $nome . '.txt';

      if (file_exists($arquivo)) {
        unlink($arquivo);
      }

      return;
    }

    self::iniciarMemcached();
    self::$memcached->delete($empresaId . '-' . $nome);
  }

  public static function apagarTodos(string $nome, int $empresaId)
  {
    if (GRAVAR_CACHE == INATIVO) {
      return null;
    }

    if (empty($empresaId)) {
      return;
    }

    if (HOST_LOCAL) {
      $diretorio = 'cache/' . $empresaId . '/';
      $padrao = $diretorio . $nome . '*.txt';
      $arquivos = glob($padrao);

      foreach ($arquivos as $arquivo):

        if (file_exists($arquivo)) {
          unlink($arquivo);
        }
      endforeach;

      return;
    }

    if (strpos($nome, '_') === false) {
      return;
    }

    $temp = explode('_', $nome);
    $prefixo = $temp[0];

    self::iniciarMemcached();
    $chave = $prefixo . '-' . $empresaId;
    $chaves = self::$memcached->get($prefixo . '-' . $empresaId);

    if ($chaves and is_array($chaves)) {
      foreach ($chaves as $cacheNome):
        self::$memcached->delete($empresaId . '-' . $cacheNome);
      endforeach;

      self::$memcached->delete($chave);
    }
  }

  public static function resetarCache()
  {
    self::iniciarMemcached();
    return self::$memcached->flush();
  }
}
