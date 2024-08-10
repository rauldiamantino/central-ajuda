<?php
namespace app\Controllers;
use app\Models\PublicoModel;
use app\Controllers\ViewRenderer;

class PublicoController extends Controller
{
  protected $middleware;
  protected $publicoModel;
  protected $visao;

  public function __construct()
  {
    $this->publicoModel = new PublicoModel();
    $this->visao = new ViewRenderer('/publico');

    parent::__construct($this->publicoModel);
  }

  public function publicoVer()
  {
    $this->visao->variavel('titulo', 'Público');
    $this->visao->renderizar('/index');
  }
}