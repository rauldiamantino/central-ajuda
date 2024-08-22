<?php
namespace app\Controllers;
use app\Models\ArtigoModel;
use app\Models\CategoriaModel;

class PublicoCategoriaController extends PublicoController
{
  protected $artigoModel;
  protected $categoriaModel;

  public function __construct()
  {
    parent::__construct();

    $this->categoriaModel = new CategoriaModel();
    $this->artigoModel = new ArtigoModel();
  }

  public function categoriaVer(int $id)
  {
    $categorias = [];
    $artigos = [];

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $resultado = $this->categoriaModel->ordem($ordem)
                                       ->buscar($colunas);
    
    if (isset($resultado[0]['Categoria.id'])) {
      $categorias = $resultado;
    }

    if ($categorias) {
      $condArtigos = [
        'Artigo.categoria_id' => (int) $id,
      ];

      $colArtigos = [
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

      $uniArtigos = [
        'Categoria',
      ];

      $ordArtigos = [
        'Artigo.ordem' => 'ASC',
      ];

      $resultado = $this->artigoModel->condicao($condArtigos)
                                     ->uniao2($uniArtigos, 'LEFT')
                                     ->ordem($ordArtigos)
                                     ->buscar($colArtigos);

      if (isset($resultado[0]['Artigo.id'])) {
        $artigos = $resultado;
      }
    }

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('artigos', $artigos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/categoria/index');
  }
}