<?php
namespace app\Controllers;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardConteudoModel;
use app\Models\DashboardCategoriaModel;

class DashboardArtigoController extends DashboardController
{
  protected $artigoModel;
  protected $conteudoModel;
  protected $categoriaModel;
  
  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel();
    $this->conteudoModel = new DashboardConteudoModel();
    $this->categoriaModel = new DashboardCategoriaModel();
  }

  public function artigosVer()
  {
    $limite = 10;
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $paginaAtual = intval($_GET['pagina'] ?? 0);
    $categoriaId = $_GET['categoria_id'] ?? '';
    $resultado = [];
    $condicoes = [];

    // Filtrar por categoria
    if (isset($_GET['categoria_id'])) {

      if (intval($categoriaId) > 0) {
        $condicoes['Artigo.categoria_id'] = (int) $categoriaId;
      }
      elseif ($categoriaId === '0') {
        $condicoes['Artigo.categoria_id IS'] = NULL;
      }
    }

    $artigosTotal = $this->artigoModel->condicao($condicoes)
                                      ->contar('Artigo.id');
    
    $artigosTotal = intval($artigosTotal['total'] ?? 0);

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);
      $paginaAtual = abs($paginaAtual);
      $paginaAtual = max($paginaAtual, 1);
      $paginaAtual = min($paginaAtual, $paginasTotal);

      $colunas = [
        'Artigo.id',
        'Artigo.titulo',
        'Artigo.usuario_id',
        'Artigo.categoria_id',
        'Artigo.visualizacoes',
        'Categoria.nome',
        'Usuario.nome',
        'Artigo.ordem',
        'Artigo.criado',
        'Artigo.ativo',
      ];

      $uniaoCategoria = [
        'Categoria',
      ];

      $uniaoUsuario = [
        'Usuario',
      ];

      $ordem = [
        'Categoria.nome' => 'ASC',
        'Artigo.ordem' => 'ASC',
      ];

      $resultado = $this->artigoModel->condicao($condicoes)
                                     ->uniao2($uniaoCategoria, 'LEFT')
                                     ->uniao2($uniaoUsuario)
                                     ->pagina($limite, $paginaAtual)
                                     ->ordem($ordem)
                                     ->buscar($colunas);

      $intervaloInicio = ($paginaAtual - 1) * $limite + 1;
      $intervaloFim = min($paginaAtual * $limite, $artigosTotal);
    }

    // Exibe menu ao filtrar sem resultados
    if (! isset($resultado[0]) and isset($_GET['categoria_id'])) {
      $resultado['filtro'] = true;
    }

    $this->visao->variavel('artigos', $resultado);
    $this->visao->variavel('pagina', $paginaAtual);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/artigo/index');
  }

  public function artigoEditarVer(int $id)
  {

    $artigo = [];
    $categorias = [];
    $conteudos = [];
    $ordem = [];

    $condicao = [
      'Artigo.id' => (int) $id,
    ];

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
      'Artigo.usuario_id',
      'Artigo.categoria_id',
      'Artigo.visualizacoes',
      'Categoria.nome',
      'Usuario.nome',
      'Artigo.criado',
      'Artigo.modificado',
      'Artigo.ativo',
    ];

    $uniaoCategoria = [
      'Categoria',
    ];

    $uniaoUsuario = [
      'Usuario',
    ];

    $resultado = $this->artigoModel->condicao($condicao)
                                   ->uniao2($uniaoCategoria, 'LEFT')
                                   ->uniao2($uniaoUsuario)
                                   ->buscar($colunas);
    
    if (isset($resultado['erro']) and $resultado['erro']) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

     header('Location: /dashboard/artigos');
      exit();
    }
    else {
      $artigo = $resultado;
    }

    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $resultado = $this->categoriaModel->buscar($colCategoria);

    if (isset($resultado[0]['Categoria.nome'])) {
      $categorias = $resultado;
    }

    $condicao = [
      'Conteudo.artigo_id' => $id,
    ];
    
    $colunas = [
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

    $ordem = [
      'Conteudo.ordem' => 'ASC',
    ];

    $resultado = $this->conteudoModel->condicao($condicao)
                                     ->ordem($ordem)
                                     ->buscar($colunas);

    if (isset($resultado[0]['Conteudo.id'])) {
      $conteudos = $resultado;

      $condicao = [
        'Conteudo.artigo_id' => $id,
      ];
      
      $colunas = [
        'Conteudo.id',
        'Conteudo.ordem',
      ];

      $ordem = [
        'Conteudo.ordem' => 'DESC',
      ];

      $limite = 1;

      $resultadoOrdem = $this->conteudoModel->condicao($condicao)
                                            ->ordem($ordem)
                                            ->limite($limite)
                                            ->buscar($colunas);

      $ordemAtual = intval($resultadoOrdem[0]['Conteudo.ordem'] ?? 0);

      if ($resultadoOrdem) {
        $ordem = [
          'prox' => $ordemAtual + 1,
        ];
      }
    }

    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('titulo', 'Editar artigo');
    $this->visao->renderizar('/artigo/editar/index');
  }

  public function artigoAdicionarVer()
  {
    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $categorias = $this->categoriaModel->buscar($colCategoria);

    if (! isset($categorias[0]['Categoria.nome'])) {
      $categorias = [];
    }
    
    $colArtigoOrdem = [
      'Artigo.id',
      'Artigo.ordem',
    ];

    $ordArtigoOrdem = [
      'Artigo.ordem' => 'DESC',
    ];

    $limiteArtigoOrdem = 1;

    $resultadoOrdem = $this->artigoModel->ordem($ordArtigoOrdem)
                                        ->limite($limiteArtigoOrdem)
                                        ->buscar($colArtigoOrdem);

    $ordem = [];
    $ordemAtual = intval($resultadoOrdem[0]['Artigo.ordem'] ?? 0);

    if ($resultadoOrdem) {
      $ordem = [
        'prox' => $ordemAtual + 1,
      ];
    }

    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('usuarioId', intval($_SESSION['usuario']['id'] ?? 0));
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('titulo', 'Adicionar artigo');
    $this->visao->renderizar('/artigo/adicionar');
  }

  public function adicionar(array $params = []): array
  {
    $dados = $this->receberJson();
    $resultado = $this->artigoModel->adicionar($dados);

    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/artigos');
      exit();
    }
    elseif ($_POST and isset($resultado['id'])) { 
      $_SESSION['ok'] = 'Artigo criado com sucesso';

      header('Location: /dashboard/artigo/editar/' . $resultado['id']);
      exit();
    }
  }

  public function buscar(int $id = 0)
  {
    $condicao = [];

    if ($id) {
      $condicao['Artigo.id'] = $id;
    }

    // Filtrar por categoria
    $categoriaId = $_GET['categoria_id'] ?? '';
    
    if (isset($_GET['categoria_id'])) {

      if (intval($categoriaId) > 0) {
        $condicao['Artigo.categoria_id'] = (int) $categoriaId;
      }
      elseif ($categoriaId === '0') {
        $condicao['Artigo.categoria_id IS'] = NULL;
      }
    }

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
    ];

    $ordem = [
      'Artigo.ordem' => 'ASC'
    ];

    $limite = 500;

    $resultado = $this->artigoModel->condicao($condicao)
                                   ->ordem($ordem)
                                   ->limite($limite)
                                   ->buscar($colunas);

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();
    $resultado = $this->artigoModel->atualizar($json, $id);

    if (isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/artigos');
      exit();
    }

    $_SESSION['ok'] = 'Registro alterado com sucesso';
    header('Location: /dashboard/artigo/editar/' . $id);
    exit();
  }

  public function atualizarOrdem()
  {
    $json = $this->receberJson();
    $resultado = $this->artigoModel->atualizarOrdem($json);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'] ?? '';

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function apagar(int $id)
  {
    $resultado = $this->artigoModel->apagar($id);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'];

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $_SESSION['ok'] = 'Artigo excluído com sucesso';
    $this->responderJson($resultado);
  }
}