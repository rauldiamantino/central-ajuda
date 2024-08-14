<?php
namespace app\Controllers;
use app\Models\ArtigoModel;
use app\Models\ConteudoModel;
use app\Models\CategoriaModel;
use app\Controllers\ViewRenderer;

class ArtigoController extends Controller
{
  protected $middleware;
  protected $artigoModel;
  protected $conteudoModel;
  protected $categoriaModel;
  protected $visao;

  public function __construct()
  {
    $this->visao = new ViewRenderer('/dashboard/artigo');
    $this->artigoModel = new ArtigoModel();
    $this->conteudoModel = new ConteudoModel();
    $this->categoriaModel = new CategoriaModel();

    parent::__construct($this->artigoModel);
  }

  public function artigosVer()
  {
    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);
    $categoriaId = $_GET['categoria_id'] ?? '';

    $condicoes = [
      'Artigo.empresa_id' => $this->empresaPadraoId,
    ];

    // Filtrar por categoria
    if (isset($_GET['categoria_id'])) {

      if (intval($categoriaId) > 0) {
        $condicoes['Artigo.categoria_id'] = (int) $categoriaId;
      }
      elseif ($categoriaId === '0') {
        $condicoes['Artigo.categoria_id IS'] = NULL;
      }
    }

    // Recupera quantidade de páginas
    $artigosTotal = $this->artigoModel->condicao($condicoes)
                                      ->contar('Artigo.id');
                         
    $artigosTotal = $artigosTotal['total'] ?? 0;
    $paginasTotal = ceil($artigosTotal / $limite);

    $pagina = abs($pagina);
    $pagina = max($pagina, 1);
    $pagina = min($pagina, $paginasTotal);

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
                                   ->pagina($limite, $pagina)
                                   ->ordem($ordem)
                                   ->buscar($colunas);

    // Calcular início e fim do intervalo
    $intervaloInicio = 0;
    $intervaloFim = 0;

    if ($artigosTotal) {
      $intervaloInicio = ($pagina - 1) * $limite + 1;
      $intervaloFim = min($pagina * $limite, $artigosTotal);
    }

    $this->visao->variavel('artigos', $resultado);
    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('titulo', 'Artigos');
    $this->visao->renderizar('/index');
  }

  public function artigoEditarVer(int $id)
  {
    $id = (int) $id;

    $condicao = [
      'Artigo.id' => $id,
      'Artigo.empresa_id' => $this->empresaPadraoId,
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

    $artigo = $this->artigoModel->condicao($condicao)
                                ->uniao2($uniaoCategoria, 'LEFT')
                                ->uniao2($uniaoUsuario)
                                ->buscar($colunas);
    
    if (isset($artigo['erro']) and $artigo['erro']) {
      $_SESSION['erro'] = $artigo['erro']['mensagem'] ?? '';

      header('Location: /dashboard/artigos');
      exit();
    }

    $condCategoria = [
      'Categoria.empresa_id' => $this->empresaPadraoId,
    ];

    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $categorias = $this->categoriaModel->condicao($condCategoria)
                                       ->buscar($colCategoria);

    if (! isset($categorias[0]['Categoria.nome'])) {
      $categorias = [];
    }

    $condConteudo = [
      'Conteudo.artigo_id' => $id,
      'Conteudo.empresa_id' => $this->empresaPadraoId,
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

    $condConteudoOrdem = [
      'Conteudo.artigo_id' => $id,
      'Conteudo.empresa_id' => $this->empresaPadraoId,
    ];
    
    $colConteudoOrdem = [
      'Conteudo.id',
      'Conteudo.ordem',
    ];

    $ordConteudoOrdem = [
      'Conteudo.ordem' => 'DESC',
    ];

    $limiteConteudoOrdem = 1;

    $resultadoOrdem = $this->conteudoModel->condicao($condConteudoOrdem)
                                          ->ordem($ordConteudoOrdem)
                                          ->limite($limiteConteudoOrdem)
                                          ->buscar($colConteudoOrdem);

    $ordem = [];
    $ordemAtual = intval($resultadoOrdem[0]['Conteudo.ordem'] ?? 0);

    if ($resultadoOrdem) {
      $ordem = [
        'prox' => $ordemAtual + 1,
      ];
    }

    $this->visao->variavel('artigo', reset($artigo));
    $this->visao->variavel('ordem', $ordem);
    $this->visao->variavel('conteudos', $conteudos);
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('titulo', 'Editar artigo');
    $this->visao->renderizar('/editar/index');
  }

  public function artigoAdicionarVer()
  {
    $condCategoria = [
      'Categoria.empresa_id' => $this->empresaPadraoId,
    ];

    $colCategoria = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $categorias = $this->categoriaModel->condicao($condCategoria)
                                       ->buscar($colCategoria);

    if (! isset($categorias[0]['Categoria.nome'])) {
      $categorias = [];
    }

    $condArtigoOrdem = [
      'Artigo.empresa_id' => $this->empresaPadraoId,
    ];
    
    $colArtigoOrdem = [
      'Artigo.id',
      'Artigo.ordem',
    ];

    $ordArtigoOrdem = [
      'Artigo.ordem' => 'DESC',
    ];

    $limiteArtigoOrdem = 1;

    $resultadoOrdem = $this->artigoModel->condicao($condArtigoOrdem)
                                        ->ordem($ordArtigoOrdem)
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
    $this->visao->variavel('usuarioId', $this->usuarioLogadoId);
    $this->visao->variavel('categorias', $categorias);
    $this->visao->variavel('titulo', 'Adicionar artigo');
    $this->visao->renderizar('/adicionar');
  }

  public function adicionar(array $params = []): array
  {
    $dados = $params;

    if (empty($params)) {
      $dados = $this->receberJson();
    }
    
    // Sempre informar Empresa ID
    $dados = array_merge($dados, ['empresa_id' => $this->empresaPadraoId]);

    // Adiciona artigo
    $resultado = $this->artigoModel->adicionar($dados);

    // Requisição interna
    if ($params and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif ($params and isset($resultado['id'])) {
      $condicao = [
        'Artigo.id' => $resultado['id'],
        'Artigo.empresa_id' => $this->empresaPadraoId,
      ];

      $colunas = [
        'Artigo.id',
        'Artigo.ativo',
        'Artigo.titulo',
        'Artigo.usuario_id',
        'Artigo.categoria_id',
        'Artigo.visualizacoes',
        'Artigo.criado',
        'Artigo.modificado',
      ];

      $artigo = $this->artigoModel->condicao($condicao)
                                  ->buscar($colunas);
      
      return reset($artigo);
    }

    // Formulário via POST
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
    
    // Formulário via Fetch
    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
  }

  public function buscar(int $id = 0)
  {
    $condicao = [
      'Artigo.empresa_id' => $this->empresaPadraoId,
    ];

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

    // Sempre informar Empresa ID
    $json = array_merge($json, ['empresa_id' => $this->empresaPadraoId]);

    // Atualiza artigo
    $resultado = $this->artigoModel->atualizar($json, $id);

    if ($_POST and isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/artigos');
      exit();
    }
    elseif ($_POST) { 
      $_SESSION['ok'] = 'Registro alterado com sucesso';

      header('Location: /dashboard/artigo/editar/' . $id);
      exit();
    }

    if (isset($resultado['erro'])) {
      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    $this->responderJson($resultado);
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

  public function apagar(int $id, bool $rollback = false)
  {
    $resultado = $this->artigoModel->apagar($id);

    if ($rollback and isset($resultado['erro'])) {
      return $resultado;
    }
    elseif (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro'];

      $codigo = $resultado['erro']['codigo'] ?? 500;
      $this->responderJson($resultado, $codigo);
    }

    if ($rollback) {
      return $resultado;
    }

    $_SESSION['ok'] = 'Artigo excluído com sucesso';

    $this->responderJson($resultado);
  }
}