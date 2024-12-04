<?php
namespace app\Controllers;
use app\Controllers\ViewRenderer;
use app\Core\Cache;

class InicioController extends Controller
{
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->visao = new ViewRenderer('/inicio');

  }

  public function inicioVer()
  {
    $this->visao->variavel('metaTitulo', '360Help - Base de conhecimento profissional');
    $this->visao->variavel('metaDescricao', '360Help - Base de conhecimento profissional');
    $this->visao->renderizar('/index');
  }
}