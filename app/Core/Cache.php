<?php
namespace app\Core;

use app\Core\SessaoUsuario;

class Cache
{
  private static $memcached;
  private static $sessaoUsuario;

  private static function iniciarMemcached()
  {
    self::$sessaoUsuario = new SessaoUsuario();

    if (self::$memcached === null) {
      self::$memcached = new \Memcached();

      if (! self::$memcached->addServer(MEMCACHED_HOST, MEMCACHED_PORT)) {
        self::registrarErro('Erro ao conectar ao servidor Memcached', self::$memcached->getResultMessage());
        throw new \RuntimeException('Conexão com Memcached falhou');
      }
    }
  }

  private static function registrarErro(string $mensagem, string $detalhes)
  {
    registrarLog($mensagem, $detalhes);
    registrarSentry($mensagem . ' - ' . $detalhes);
  }

  private static function verificarAtivo(array $parametros): bool
  {
    if (GRAVAR_CACHE == INATIVO) {
      return false;
    }

    foreach ($parametros as $linha):

      if (empty($linha)) {
        return false;
      }
    endforeach;

    return true;
  }

  // Usar com atenção
  public static function definirSemId(string $nome, array $dados, int $tempo)
  {
    if (! self::verificarAtivo([$nome, $dados, $tempo])) {
      return;
    }

    if (! is_array($dados)) {
      return false;
    }

    self::iniciarMemcached();

    if (! self::$memcached->set($nome, $dados, $tempo)) {
      self::registrarErro('Erro ao definir cache', self::$memcached->getResultMessage());
    }
  }

  // Usar com atenção
  public static function buscarSemId(string $nome)
  {
    if (! self::verificarAtivo([$nome])) {
      return;
    }

    self::iniciarMemcached();
    $resultado = self::$memcached->get($nome);

    if ($resultado === false and self::$memcached->getResultCode() !== \Memcached::RES_NOTFOUND) {
      self::registrarErro('Erro ao buscar cache', self::$memcached->getResultMessage());
    }

    return $resultado ? $resultado : null;
  }

  // Usar com atenção
  public static function apagarSemId(string $nome)
  {
    if (! self::verificarAtivo([$nome])) {
      return;
    }

    self::iniciarMemcached();

    if (! self::$memcached->delete($nome)) {

      if (self::$memcached->getResultCode() !== \Memcached::RES_NOTFOUND) {
        self::registrarErro("Erro ao apagar cache", self::$memcached->getResultMessage());
      }
    }
  }

  public static function definir(string $nome, array $dados, int $tempo, int $empresaId)
  {
    if (! self::verificarAtivo([$nome, $dados, $tempo, $empresaId])) {
      return;
    }

    if (! is_array($dados)) {
      return false;
    }

    self::iniciarMemcached();

    $chave = $empresaId . '-' . $nome;

    if (! self::$memcached->set($chave, $dados, $tempo)) {
      self::registrarErro('Erro ao definir cache', self::$memcached->getResultMessage());
    }

    // Lista de chaves para cada empresa
    $chaves = self::$memcached->get($empresaId . '-keys');

    if (! is_array($chaves)) {
      $chaves = [];
    }

    $chaves[] = $chave;

    if (! self::$memcached->set($empresaId . '-keys', $chaves)) {
      self::registrarErro('Erro ao atualizar lista de chaves', self::$memcached->getResultMessage());
    }
  }

  public static function buscar(string $nome, int $empresaId)
  {
    if (! self::verificarAtivo([$nome, $empresaId])) {
      return;
    }

    self::iniciarMemcached();

    $chave = $empresaId . '-' . $nome;
    $resultado = self::$memcached->get($chave);

    if ($resultado === false and self::$memcached->getResultCode() !== \Memcached::RES_NOTFOUND) {
      self::registrarErro('Erro ao buscar cache', self::$memcached->getResultMessage());
    }

    return $resultado ? $resultado : null;
  }

  public static function apagar(string $nome, int $empresaId)
  {
    if (! self::verificarAtivo([$nome, $empresaId])) {
      return;
    }

    self::iniciarMemcached();

    $chave = $empresaId . '-' . $nome;

    if (! self::$memcached->delete($chave)) {

      if (self::$memcached->getResultCode() !== \Memcached::RES_NOTFOUND) {
        self::registrarErro("Erro ao apagar cache", self::$memcached->getResultMessage());
      }
    }

    // Atualizar lista de chaves da Empresa
    $chaves = self::$memcached->get($empresaId . '-keys');

    if ($chaves and is_array($chaves)) {
      $chavesAtualizadas = [];
      foreach ($chaves as $item):

        if ($item !== $chave) {
          $chavesAtualizadas[] = $item;
        }
      endforeach;

      if (! self::$memcached->set($empresaId . '-keys', $chavesAtualizadas)) {
        self::registrarErro('Erro ao atualizar lista de chaves após exclusão', self::$memcached->getResultMessage());
      }
    }
  }

  private static function apagarChavesPrefixo($prefixo)
  {
    if (empty($prefixo)) {
      return;
    }

    $chaves = self::$memcached->get($prefixo . '-keys');

    if ($chaves and is_array($chaves)) {
      foreach ($chaves as $chave):

        if (strpos($chave, $prefixo . '-') === 0) {

          if (! self::$memcached->delete($chave)) {

            if (self::$memcached->getResultCode() !== \Memcached::RES_NOTFOUND) {
              self::registrarErro("Erro ao apagar cache", self::$memcached->getResultMessage());
            }
          }
        }
      endforeach;
    }
  }

  public static function apagarTodos(string $nome, int $empresaId)
  {
    if (! self::verificarAtivo([$nome, $empresaId])) {
      return;
    }

    self::iniciarMemcached();
    self::apagarChavesPrefixo((string) $empresaId . '-' . $nome);
  }

  public static function resetarCacheSemId()
  {
    $empresa = self::$sessaoUsuario->buscar('subdominio');

    if (empty($empresa)) {
      header('Location: /erro');
      exit();
    }

    self::iniciarMemcached();
    self::apagarChavesPrefixo('roteador-' . $empresa);

    self::$sessaoUsuario->definir('ok', 'Reset cache sem ID');

    header('Location: ' . REFERER);
    exit();
  }

  public static function resetarCacheEmpresa(int $empresaId)
  {
    if (! self::verificarAtivo([$empresaId])) {
      return;
    }

    self::iniciarMemcached();
    self::apagarChavesPrefixo((string) $empresaId);

    self::$sessaoUsuario->definir('ok', 'Reset cache empresa');

    header('Location: /logout');
    exit();
  }

  public static function resetarCacheTodos()
  {
    self::iniciarMemcached();

    if (! self::$memcached->flush()) {
      self::registrarErro('Erro ao resetar todo o cache', self::$memcached->getResultMessage());
    }

    self::$sessaoUsuario->definir('ok', 'Reset cache todos');

    header('Location: /logout');
    exit();
  }
}