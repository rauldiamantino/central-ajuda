<?php
namespace app\Controllers;
use app\Models\DashboardConteudoModel;
use app\Models\DashboardArtigoModel;

class PublicoArtigoController extends PublicoController
{
  protected $artigoModel;
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel();
    $this->conteudoModel = new DashboardConteudoModel();
  }

  public function artigoVer(int $id)
  {
    $artigo = [];
    $conteudos = [];
    $demaisArtigos = [];

    $condicoes = [
      'Artigo.id' => (int) $id,
      'Artigo.ativo' => 1,
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

    $resultado = $this->artigoModel->condicao($condicoes)
                                   ->uniao2($uniao, 'LEFT')
                                   ->ordem(['Artigo.ordem' => 'ASC'])
                                   ->buscar($colunas);

    if (isset($resultado[0]['Artigo.id'])) {
      $artigo = $resultado;
    }

    if ($artigo) {
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

      $resultado = $this->conteudoModel->condicao($condConteudo)
                                       ->ordem($ordConteudo)
                                       ->buscar($colConteudo);

      if (isset($resultado[0]['Conteudo.id'])) {
        $conteudos = $resultado;
      }

      $condDemaisArtigos = [
        'Artigo.categoria_id' => intval($artigo[0]['Artigo.categoria_id'] ?? 0),
        'Artigo.ativo' => 1,
      ];

      $colDemaisArtigos = [
        'Artigo.id',
        'Artigo.titulo',
        'Categoria.nome',
      ];

      $uniDemaisArtigos = [
        'Categoria',
      ];

      $resultado = $this->artigoModel->condicao($condDemaisArtigos)
                                     ->uniao2($uniDemaisArtigos, 'LEFT')
                                     ->ordem(['Artigo.ordem' => 'ASC'])
                                     ->buscar($colDemaisArtigos);

      if (isset($resultado[0]['Artigo.id'])) {
        $demaisArtigos = $resultado;
      }
    }
    else {
      $_SESSION['erro'] = 'Desculpe, este artigo não está disponível';
      header('Location: /' . $this->subdominio);
      exit;
    }

    $this->visao->variavel('demaisArtigos', $demaisArtigos);
    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/artigo/index');
  }
}