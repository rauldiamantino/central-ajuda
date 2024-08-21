<?php
namespace app\Controllers;
use app\Controllers\ViewRenderer;
use app\Models\ArtigoModel;
use app\Models\EmpresaModel;

class BuscaController extends Controller
{
  protected $visao;
  protected $artigoModel;
  protected $empresaModel;
  
  public function __construct()
  {
    $this->visao = new ViewRenderer('/publico');;
    $this->artigoModel = new ArtigoModel();
    $this->empresaModel = new EmpresaModel();

    // Botão do WhatsApp
    $telefoneEmpresa = intval($_SESSION['empresaTelefone'] ?? 0);

    if ($telefoneEmpresa == 0) {
      $resultado = $this->empresaModel->buscar(['Empresa.telefone']);
      $telefoneEmpresa = intval($resultado[0]['Empresa.telefone'] ?? 0);

      $_SESSION['empresaTelefone'] = $telefoneEmpresa;
    }

    $this->visao->variavel('telefoneEmpresa', $telefoneEmpresa);
  }

  public function buscarArtigos()
  {
    $limite = 10;
    $pagina = intval($_GET['pagina'] ?? 0);
    $textoBusca = htmlspecialchars($_GET['texto_busca'] ?? '');

    $condicao = [];
    
    if ($textoBusca) {
      $condicao = [
        'Artigo.titulo LIKE' => '%' . $textoBusca . '%',
      ];
    }

    // Recupera quantidade de páginas
    $artigosTotal = $this->artigoModel->condicao($condicao)
                                      ->contar('Artigo.id');
    
    $artigosTotal = intval($artigosTotal['total'] ?? 0);
    $paginasTotal = 0;

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);
    }

    $pagina = abs($pagina);
    $pagina = max($pagina, 1);
    $pagina = min($pagina, $paginasTotal);

    $colunas = [
      'Artigo.id',
      'Artigo.titulo',
      'Artigo.ativo',
      'Artigo.categoria_id',
      'Categoria.nome'
    ];

    $ordem = [
      'Artigo.modificado' => 'DESC',
      'Artigo.criado' => 'DESC',
      'Artigo.ordem' => 'ASC',
    ];

    $uniao2 = [
      'Categoria',
    ];

    $limite = 10;

    $resultadoBuscar = $this->artigoModel->condicao($condicao)
                                         ->uniao2($uniao2, 'LEFT')
                                         ->ordem($ordem)
                                         ->pagina($limite, $pagina)
                                         ->buscar($colunas);

    if (! isset($resultadoBuscar[0]['Artigo.id'])) {
      $resultadoBuscar = [];
    }

    // Calcular início e fim do intervalo
    $intervaloInicio = 0;
    $intervaloFim = 0;

    if ($artigosTotal) {
      $intervaloInicio = ($pagina - 1) * $limite + 1;
      $intervaloFim = min($pagina * $limite, $artigosTotal);
    }

    $this->visao->variavel('pagina', $pagina);
    $this->visao->variavel('artigosTotal', $artigosTotal);
    $this->visao->variavel('limite', $limite);
    $this->visao->variavel('paginasTotal', $paginasTotal);
    $this->visao->variavel('intervaloInicio', $intervaloInicio);
    $this->visao->variavel('intervaloFim', $intervaloFim);
    $this->visao->variavel('textoBusca', $textoBusca);
    $this->visao->variavel('resultadoBuscar', $resultadoBuscar);
    $this->visao->variavel('titulo', 'Buscar');
    $this->visao->renderizar('/index');
  }

  public function atualizar()
  {
    $json = $this->receberJson();

    $resultado = $this->buscaModel->atualizarAjustes($json);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/ajustes');
      exit();
    }

    $_SESSION['ok'] = 'Ajuste alterado com sucesso';
    header('Location: /dashboard/ajustes');
    exit();
  }
}