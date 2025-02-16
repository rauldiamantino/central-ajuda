<?php

namespace app\Controllers;

class ViewRenderer extends Controller
{
  private $caminho;
  private $variaveis = [];

  public function __construct($caminho = '')
  {
    parent::__construct();

    $this->caminho = $caminho;
  }

  public function renderizar($visao)
  {
    extract($this->variaveis);

    // Visão
    $visao = '../' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . str_replace('/', DIRECTORY_SEPARATOR, $this->caminho) . str_replace('/', DIRECTORY_SEPARATOR, $visao) . '.php';

    $temp = explode('/', $this->caminho);
    $diretorioBase = $temp[1] ?? '';

    // Layout base
    $index = '../' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $diretorioBase . DIRECTORY_SEPARATOR . 'index.php';

    if (file_exists($index)) {
      require $index;
    }
  }

  public function variavel($nome, $valor)
  {
    $this->variaveis[ $nome ] = $valor;
  }
}
