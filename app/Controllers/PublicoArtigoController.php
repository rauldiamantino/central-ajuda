<?php
namespace app\Controllers;
use app\Models\DashboardConteudoModel;
use app\Models\DashboardArtigoModel;
use app\Core\Cache;

class PublicoArtigoController extends PublicoController
{
  protected $artigoModel;
  protected $conteudoModel;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->conteudoModel = new DashboardConteudoModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function artigoVer(int $id)
  {
    $artigo = [];
    $conteudos = [];
    $demaisArtigos = [];

    $condicoes = [
      'Artigo.id' => (int) $id,
      'Artigo.ativo' => ATIVO,
    ];

    if ($this->exibirInativos()) {
      unset($condicoes['Artigo.ativo']);
    }

    $colunas = [
      'Artigo.id',
      'Artigo.ativo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.empresa_id',
      'Artigo.categoria_id',
      'Artigo.criado',
      'Artigo.modificado',
      'Categoria.ativo',
      'Categoria.nome',
      'Usuario.nome',
    ];

    $uniao = [
      'Categoria',
      'Usuario',
    ];

    $cacheNome = 'publico-artigo_artigo-' . md5(serialize($condicoes));
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->artigoModel->condicao($condicoes)
                                     ->uniao2($uniao, 'LEFT')
                                     ->ordem(['Artigo.ordem' => 'ASC'])
                                     ->buscar($colunas);

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

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

      $cacheNome = 'publico-artigo_conteudos-' . md5(serialize($condConteudo));
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->conteudoModel->condicao($condConteudo)
                                         ->ordem($ordConteudo)
                                         ->buscar($colConteudo);

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Conteudo.id'])) {
        $conteudos = $resultado;
      }

      $condDemaisArtigos = [
        'Artigo.categoria_id' => intval($artigo[0]['Artigo.categoria_id'] ?? 0),
        'Artigo.ativo' => ATIVO,
      ];

      if ($this->exibirInativos()) {
        unset($condDemaisArtigos['Artigo.ativo']);
      }

      $colDemaisArtigos = [
        'Artigo.id',
        'Artigo.ativo',
        'Artigo.titulo',
        'Categoria.nome',
        'Categoria.ativo',
      ];

      $uniDemaisArtigos = [
        'Categoria',
      ];


      $cacheNome = 'publico-artigo_demais-artigos-' . md5(serialize($condDemaisArtigos));
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->artigoModel->condicao($condDemaisArtigos)
                                       ->uniao2($uniDemaisArtigos, 'LEFT')
                                       ->ordem(['Artigo.ordem' => 'ASC'])
                                       ->buscar($colDemaisArtigos);

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Artigo.id'])) {
        $demaisArtigos = $resultado;
      }
    }
    else {
      $this->redirecionarErro('/', 'Desculpe, este artigo não está disponível');
    }

    $this->visao->variavel('demaisArtigos', $demaisArtigos);
    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->variavel('menuLateral', true);
    $this->visao->renderizar('/artigo/index');
  }
}