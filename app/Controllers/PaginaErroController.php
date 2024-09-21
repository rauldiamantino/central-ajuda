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

  public function erroVer($titulo = 'Not Found', $codigo = 404)
  {
    $this->visao->variavel('titulo', $titulo);
    $this->visao->variavel('codigoErro', $codigo);
    $this->visao->renderizar('/index');
  }
}