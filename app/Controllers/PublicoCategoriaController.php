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

    $cacheNome = 'publico-categoria_categorias-' . md5(serialize($condicoes));
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
        'Categoria.ativo',
        'Categoria.nome',
        'Categoria.descricao',
      ];

      $uniArtigos = [
        'tabelaJoin' => 'Categoria',
        'campoA' => 'Categoria.id',
        'campoB' => 'Artigo.categoria_id',
      ];

      $ordArtigos = [
        'Artigo.ordem' => 'ASC',
      ];

      $cacheNome = 'publico-categoria_artigos-' . md5(serialize($condArtigos));
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

    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('artigos', $artigos);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->variavel('menuLateral', true);
    $this->visao->renderizar('/categoria/index');
  }
}