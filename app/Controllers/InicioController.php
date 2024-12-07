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
    $this->visao->variavel('metaDescricao', 'Uma base de conhecimento prática e eficiente. Organize, compartilhe e ofereça soluções rápidas, aliviando a sobrecarga do seu time de suporte.');
    $this->visao->renderizar('/inicio/index');
  }

  public function privacidadeVer()
  {
    $this->visao->variavel('metaTitulo', '360Help - Base de conhecimento profissional');
    $this->visao->variavel('metaDescricao', 'Uma base de conhecimento prática e eficiente. Organize, compartilhe e ofereça soluções rápidas, aliviando a sobrecarga do seu time de suporte.');
    $this->visao->renderizar('/privacidade/index');
  }

  public function termosVer()
  {
    $this->visao->variavel('metaTitulo', '360Help - Base de conhecimento profissional');
    $this->visao->variavel('metaDescricao', 'Uma base de conhecimento prática e eficiente. Organize, compartilhe e ofereça soluções rápidas, aliviando a sobrecarga do seu time de suporte.');
    $this->visao->renderizar('/termos/index');
  }
}