<?php

namespace app\Controllers;

class ViewRenderer
{
  private $caminho;

  public function __construct($caminho)
  {
    $this->caminho = $caminho;
  }

  public function renderizar($visao, $dados = [])
  {
    extract($dados);

    // Conteúdo
    $arquivo = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . str_replace('/', DIRECTORY_SEPARATOR, $this->caminho) . str_replace('/', DIRECTORY_SEPARATOR, $visao) . '.php';

    ob_start();
    if (file_exists($arquivo)) {
      require $arquivo;
    }

    $content = ob_get_clean();
    $temp = explode('/', $this->caminho);
    $diretorioBase = $temp[1] ?? '';

    // Layout base
    $layout = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $diretorioBase . DIRECTORY_SEPARATOR . 'layout.php';

    if (file_exists($layout)) {
      require $layout;
    }
  }
}
