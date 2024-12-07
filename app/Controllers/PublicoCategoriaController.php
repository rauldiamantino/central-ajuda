<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardCategoriaModel;

class PublicoCategoriaController extends PublicoController
{
  protected $artigoModel;
  protected $categoriaModel;

  public function __construct()
  {
    parent::__construct();

    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function categoriaVer(int $id)
  {
    $categorias = [];
    $artigos = [];

    $condicoes[] = [
      'campo' => 'Categoria.ativo',
      'operador' => '=',
      'valor' => ATIVO,
    ];

    if ($this->exibirInativos()) {
      $condicoes = [];
    }

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.icone',
      'Categoria.ativo',
    ];

    $existe = [
      'tabela' => 'Artigo',
      'params' => [
        ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => 'Categoria.id'],
        ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => (int) ATIVO],
      ],
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $cacheNome = 'publico-categorias';
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->categoriaModel->selecionar($colunas)
                                        ->condicao($condicoes)
                                        ->existe($existe)
                                        ->ordem($ordem)
                                        ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    if (isset($resultado[0]['Categoria']['id'])) {
      $categorias = $resultado;
    }

    if ($categorias) {
      $condArtigos = [
        0 => ['campo' => 'Artigo.categoria_id', 'operador' => '=', 'valor' => (int) $id],
        1 => ['campo' => 'Categoria.ativo', 'operador' => '=', 'valor' => ATIVO],
        2 => ['campo' => 'Artigo.ativo', 'operador' => '=', 'valor' => ATIVO],
      ];

      if ($this->exibirInativos()) {
        unset($condArtigos[1]);
        unset($condArtigos[2]);
      }


      $colArtigos = [
        'Artigo.id',
        'Artigo.ativo',
        'Artigo.titulo',
        'Artigo.usuario_id',
        'Artigo.empresa_id',
        'Artigo.categoria_id',
        'Artigo.criado',
        'Artigo.modificado',
        'Categoria.id',
        'Categoria.ativo',
        'Categoria.nome',
        'Categoria.descricao',
        'Categoria.icone',
        'Categoria.meta_titulo',
        'Categoria.meta_descricao',
      ];

      $uniArtigos = [
        'tabelaJoin' => 'Categoria',
        'campoA' => 'Categoria.id',
        'campoB' => 'Artigo.categoria_id',
      ];

      $ordArtigos = [
        'Artigo.ordem' => 'ASC',
      ];

      $cacheNome = 'publico-categoria-' . $id . '-artigos';
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->artigoModel->selecionar($colArtigos)
                                       ->condicao($condArtigos)
                                       ->juntar($uniArtigos, 'LEFT')

                                       ->ordem($ordArtigos)
                                       ->executarConsulta();

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

      if (isset($resultado[0]['Artigo']['id'])) {
        $artigos = $resultado;
      }
    }

    $sucesso = false;
    foreach ($categorias as $chave => $linha):

      if (isset($linha['Categoria']['id']) and $linha['Categoria']['id'] == $id) {
        $sucesso = true;
      }
    endforeach;

    if (empty($categorias) or $sucesso == false) {
      $this->redirecionarErro('/' . $this->subdominio, 'Desculpe, esta categoria não está disponível');
    }

    $metaTitulo = $resultado[0]['Categoria']['meta_titulo'];
    $metaDescricao = $resultado[0]['Categoria']['meta_descricao'];

    if (empty($metaTitulo) and $this->empresaNome and $resultado[0]['Categoria']['nome']) {
      $metaTitulo = $resultado[0]['Categoria']['nome'] . ' - ' . $this->empresaNome;
    }
    elseif (empty($metaTitulo)) {
      $metaTitulo = $resultado[0]['Categoria']['nome'];
    }

    if (empty($metaDescricao)) {
      $metaDescricao = $resultado[0]['Categoria']['descricao'];
    }

    $urlCanonica = 'https://' . $this->subdominio_2;

    if ($this->subdominio) {
      $urlCanonica = 'https://360help.com.br/' . $this->subdominio;
    }

    $urlCanonica = $urlCanonica . '/categoria/' . $id . '/' . $this->gerarSlug($resultado[0]['Categoria']['nome']);

    $this->visao->variavel('metaTitulo', $metaTitulo);
    $this->visao->variavel('metaDescricao', $metaDescricao);
    $this->visao->variavel('urlCanonica', $urlCanonica);
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('artigos', $artigos);
    $this->visao->variavel('menuLateral', true);
    $this->visao->renderizar('/categoria/index');
  }
}