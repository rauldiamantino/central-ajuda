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
      0 => ['campo' => 'Artigo.id', 'operador' => '=', 'valor' => (int) $id],
      1 => ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => ATIVO],
    ];

    if ($this->exibirInativos()) {
      unset($condicoes[1]);
    }

    $colunas = [
      'Artigo.id',
      'Artigo.ativo',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.empresa_id',
      'Artigo.categoria_id',
      'Artigo.meta_titulo',
      'Artigo.meta_descricao',
      'Artigo.criado',
      'Artigo.modificado',
      'Categoria.ativo',
      'Categoria.nome',
      'Usuario.nome',
      'Usuario.foto',
    ];

    $juntarCategoria = [
      'tabelaJoin' => 'Categoria',
      'campoA' => 'Categoria.id',
      'campoB' => 'Artigo.categoria_id',
    ];

    $juntarUsuario = [
      'tabelaJoin' => 'Usuario',
      'campoA' => 'Usuario.id',
      'campoB' => 'Artigo.usuario_id',
    ];

    $ordem = [
      'Artigo.ordem' => 'ASC',
    ];

    $cacheNome = 'publico-artigo_' . $id;
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->artigoModel->selecionar($colunas)
                                     ->condicao($condicoes)
                                     ->juntar($juntarCategoria, 'LEFT')
                                     ->juntar($juntarUsuario, 'LEFT')
                                     ->ordem($ordem)
                                     ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    if (isset($resultado[0]['Artigo']['id'])) {
      $artigo = $resultado;
    }

    if ($artigo) {
      $condConteudo[] = [
        'campo' => 'Conteudo.artigo_id',
        'operador' => '=',
        'valor' => $id,
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

      $cacheNome = 'publico-artigo_' . $id . '-conteudos';
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->conteudoModel->selecionar($colConteudo)
                                         ->condicao($condConteudo)
                                         ->ordem($ordConteudo)
                                         ->executarConsulta();

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Conteudo']['id'])) {
        $conteudos = $resultado;
      }

      $condDemaisArtigos = [
        0 => ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => intval($artigo[0]['Artigo']['categoria_id'] ?? 0)],
        1 => ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => ATIVO],
      ];

      if ($this->exibirInativos()) {
        unset($condDemaisArtigos[1]);
      }

      $colDemaisArtigos = [
        'Artigo.id',
        'Artigo.ativo',
        'Artigo.titulo',
        'Categoria.nome',
        'Categoria.ativo',
      ];

      $uniDemaisArtigos = [
        'tabelaJoin' => 'Categoria',
        'campoA' => 'Categoria.id',
        'campoB' => 'Artigo.categoria_id',
      ];

      $ordem = [
        'Artigo.ordem' => 'ASC',
      ];

      $cacheNome = 'publico-artigos-categoria-' . intval($artigo[0]['Artigo']['categoria_id'] ?? 0);
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->artigoModel->selecionar($colDemaisArtigos)
                                       ->condicao($condDemaisArtigos)
                                       ->juntar($uniDemaisArtigos, 'LEFT')
                                       ->ordem($ordem)
                                       ->executarConsulta();

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Artigo']['id'])) {
        $demaisArtigos = $resultado;
      }
    }
    else {
      $this->redirecionarErro('/' . $this->subdominio, 'Desculpe, este artigo não está disponível');
    }

    $metaTitulo = $artigo[0]['Artigo']['meta_titulo'];
    $metaDescricao = $artigo[0]['Artigo']['meta_descricao'];

    if (empty($metaTitulo) and $this->empresaNome and $artigo[0]['Artigo']['titulo']) {
      $metaTitulo = $artigo[0]['Artigo']['titulo'] . ' - ' . $this->empresaNome;
    }
    elseif (empty($metaTitulo)) {
      $metaTitulo = $artigo[0]['Artigo']['titulo'];
    }

    $urlCanonica = 'https://' . $this->subdominio_2;

    if ($this->subdominio) {
      $urlCanonica = 'https://360help.com.br/' . $this->subdominio;
    }

    $urlCanonica = $urlCanonica . '/artigo/' . $id . '/' . $this->gerarSlug($artigo[0]['Artigo']['titulo']);

    $this->visao->variavel('demaisArtigos', $demaisArtigos);
    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('metaTitulo', $metaTitulo);
    $this->visao->variavel('metaDescricao', $metaDescricao);
    $this->visao->variavel('urlCanonica', $urlCanonica);
    $this->visao->variavel('menuLateral', true);
    $this->visao->renderizar('/artigo/index');
  }
}