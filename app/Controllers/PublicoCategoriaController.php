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

    $condicoes = [
      'Categoria.ativo' => ATIVO,
    ];

    if ($this->exibirInativos()) {
      unset($condicoes['Categoria.ativo']);
    }

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
      'Categoria.descricao',
      'Categoria.ativo',
    ];

    $ordem = [
      'Categoria.ordem' => 'ASC',
    ];

    $cacheNome = 'publico-categoria_categorias-' . md5(serialize($condicoes));
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->categoriaModel->condicao($condicoes)
                                        ->ordem($ordem)
                                        ->buscar($colunas);

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    if (isset($resultado[0]['Categoria.id'])) {
      $categorias = $resultado;
    }

    if ($categorias) {
      $condArtigos = [
        'Artigo.categoria_id' => (int) $id,
        'Categoria.ativo' => ATIVO,
        'Artigo.ativo' => ATIVO,
      ];

      if ($this->exibirInativos()) {
        unset($condArtigos['Categoria.ativo']);
        unset($condArtigos['Artigo.ativo']);
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
        'Categoria.ativo',
        'Categoria.nome',
        'Categoria.descricao',
      ];

      $uniArtigos = [
        'Categoria',
      ];

      $ordArtigos = [
        'Artigo.ordem' => 'ASC',
      ];

      $cacheNome = 'publico-categoria_artigos-' . md5(serialize($condArtigos));
      $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

      if ($resultado == null) {
        $resultado = $this->artigoModel->condicao($condArtigos)
                                       ->uniao2($uniArtigos, 'LEFT')
                                       ->ordem($ordArtigos)
                                       ->buscar($colArtigos);

        Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
      }

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
      $this->redirecionarErro('/', 'Desculpe, esta categoria não está disponível');
    }

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('artigos', $artigos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->variavel('menuLateral', true);
    $this->visao->renderizar('/categoria/index');
  }
}