<?php
namespace app\Controllers;
use app\Models\PublicoModel;
use app\Controllers\ViewRenderer;
use app\Models\CategoriaModel;
use app\Models\ArtigoModel;
use app\Models\ConteudoModel;

class PublicoController extends Controller
{
  protected $middleware;
  protected $publicoModel;
  protected $categoriaModel;
  protected $artigoModel;
  protected $conteudoModel;
  protected $visao;

  public function __construct()
  {
    $this->publicoModel = new PublicoModel();
    $this->categoriaModel = new CategoriaModel();
    $this->artigoModel = new ArtigoModel();
    $this->conteudoModel = new ConteudoModel();
    $this->visao = new ViewRenderer('/publico');

    parent::__construct($this->publicoModel);
  }

  public function publicoCategoriasVer()
  {
    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $resultado = $this->categoriaModel->ordem(['Categoria.ordem' => 'ASC'])
                                      ->buscar($colunas);
    
    if (isset($resultado[0]['Categoria.id'])) {
      header('Location: /publico/categoria/' . $resultado[0]['Categoria.id']);
      exit;
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('titulo', 'Público');
    $this->visao->renderizar('/index');
  }

  public function publicoCategoriaVer(int $id)
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
    $this->visao->renderizar('/index');
  }

  public function publicoArtigoVer(int $id)
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
    $this->visao->renderizar('/index');
  }
}