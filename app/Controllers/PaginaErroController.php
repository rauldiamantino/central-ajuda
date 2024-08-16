<?php
namespace app\Controllers;
use app\Controllers\ViewRenderer;

class PaginaErroController extends Controller
{
  protected $paginaErroModel;
  protected $visao;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/pagina-erro');
  }

  public function erroVer()
  {
    $this->visao->variavel('titulo', 'Não encontrada');
    $this->visao->renderizar('/index');
  }
}