<?php
namespace app\Controllers;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardCategoriaModel;

class PublicoCategoriaController extends PublicoController
{
  protected $artigoModel;
  protected $categoriaModel;

  public function __construct()
  {
    parent::__construct();

    $this->categoriaModel = new DashboardCategoriaModel();
    $this->artigoModel = new DashboardArtigoModel();
  }

  public function categoriaVer(int $id)
  {
    $categorias = [];
    $artigos = [];

    $condicoes = [
      'Categoria.ativo' => 1,
    ];

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $resultado = $this->categoriaModel->condicao($condicoes)
                                      ->ordem($ordem)
                                      ->buscar($colunas);

    if (isset($resultado[0]['Categoria.id'])) {
      $categorias = $resultado;
    }

    if ($categorias) {
      $condArtigos = [
        'Artigo.categoria_id' => (int) $id,
        'Categoria.ativo' => 1,
        'Artigo.ativo' => 1,
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

    $sucesso = false;
    foreach ($categorias as $chave => $linha):

      if (isset($linha['Categoria.id']) and $linha['Categoria.id'] == $id) {
        $sucesso = true;
      }
    endforeach;

    if (empty($categorias) or $sucesso == false) {
      $this->redirecionarErro('/' . $this->subdominio, 'Desculpe, esta categoria não está disponível');
    }

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('artigos', $artigos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/categoria/index');
  }
}