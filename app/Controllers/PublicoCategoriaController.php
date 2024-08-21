<?php
namespace app\Controllers;
use app\Models\ArtigoModel;
use app\Models\CategoriaModel;

class PublicoCategoriaController extends PublicoController
{
  protected $publicoModel;
  protected $categoriaModel;
  protected $conteudoModel;
  protected $empresaModel;

  public function __construct()
  {
    parent::__construct();
    $this->categoriaModel = new CategoriaModel();
  }

  public function categoriaVer(int $id)
  {
    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $categorias = $this->categoriaModel->ordem(['Categoria.ordem' => 'ASC'])
                                       ->buscar($colunas);
    
    if (! isset($categorias[0]['Categoria.id'])) {
      $categorias = [];
    }

    $condicoes = [
      'Artigo.categoria_id' => (int) $id,
    ];

    $colunas = [
      'Artigo.id',
      'Artigo.ativo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.empresa_id',
      'Artigo.categoria_id',
      'Artigo.criado',
      'Artigo.modificado',
      'Categoria.nome',
    ];

    $uniao = [
      'Categoria',
    ];

    $artigos = $this->artigoModel->condicao($condicoes)
                                 ->uniao2($uniao, 'LEFT')
                                 ->ordem(['Artigo.ordem' => 'ASC'])
                                 ->buscar($colunas);

    if (! isset($artigos[0]['Artigo.id'])) {
      $artigos = [];
    }

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('artigos', $artigos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/categoria/index');
  }
}