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
      'Artigo.criado',
      'Artigo.modificado',
      'Categoria.ativo',
      'Categoria.nome',
      'Usuario.nome',
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

    $cacheNome = 'publico-artigo_artigo-' . md5(serialize($condicoes));
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

      $cacheNome = 'publico-artigo_conteudos-' . md5(serialize($condConteudo));
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

      $cacheNome = 'publico-artigo_demais-artigos-' . md5(serialize($condDemaisArtigos));
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

    $this->visao->variavel('demaisArtigos', $demaisArtigos);
    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->variavel('menuLateral', true);
    $this->visao->renderizar('/artigo/index');
  }
}