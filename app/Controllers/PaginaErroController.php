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
    http_response_code($codigo);
    $this->visao->variavel('metaTitulo', $titulo . ' - 360Help');
    $this->visao->variavel('codigoErro', $codigo);
    $this->visao->variavel('paginaMenuLateral', 'erro');
    $this->visao->renderizar('/index');

    exit; // Sempre encerra a comunicação
  }
}