<?php
namespace app\Controllers;
use app\Models\CategoriaModel;

class PublicoArtigoController extends PublicoController
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

  public function artigoVer(int $id)
  {
    // Artigo selecionado
    $condicoes = [
      'Artigo.id' => (int) $id,
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
      'Usuario.nome',
    ];

    $uniao = [
      'Categoria',
      'Usuario',
    ];
    
    $artigo = $this->artigoModel->condicao($condicoes)
                                ->uniao2($uniao, 'LEFT')
                                ->ordem(['Artigo.ordem' => 'ASC'])
                                ->buscar($colunas);

    if (! isset($artigo[0]['Artigo.id'])) {
      $artigo = [];
    }

    // Conteúdos do artigo selecionado
    $condConteudo = [
      'Conteudo.artigo_id' => $id,
    ];
    
    $colConteudo = [
      'Conteudo.id',
      'Conteudo.ativo',
      'Conteudo.tipo',
      'Conteudo.titulo',
      'Conteudo.titulo_ocultar',
      'Conteudo.conteudo',
      'Conteudo.url',
      'Conteudo.ordem',
      'Conteudo.criado',
      'Conteudo.modificado',
    ];

    $ordConteudo = [
      'Conteudo.ordem' => 'ASC',
    ];

    $conteudos = $this->conteudoModel->condicao($condConteudo)
                                     ->ordem($ordConteudo)
                                     ->buscar($colConteudo);

    if (! isset($conteudos[0]['Conteudo.id'])) {
      $conteudos = [];
    }

    // Demais artigos da categoria
    $condicoes = [
      'Artigo.categoria_id' => intval($artigo[0]['Artigo.categoria_id'] ?? 0),
    ];

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
      'Categoria.nome',
    ];

    $uniao = [
      'Categoria',
    ];

    $demaisArtigos = $this->artigoModel->condicao($condicoes)
                                       ->uniao2($uniao, 'LEFT')
                                       ->ordem(['Artigo.ordem' => 'ASC'])
                                       ->buscar($colunas);

    if (! isset($demaisArtigos[0]['Artigo.id'])) {
      $demaisArtigos = [];
    }

    $this->visao->variavel('demaisArtigos', $demaisArtigos);
    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/artigo/index');
  }
}