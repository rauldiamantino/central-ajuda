<?php
namespace app\Controllers;
use app\Models\ArtigoModel;
use app\Models\EmpresaModel;

class PublicoBuscaController extends PublicoController
{
  protected $visao;
  protected $artigoModel;
  protected $empresaModel;
  protected $subdominio;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new ArtigoModel();
    $this->empresaModel = new EmpresaModel();
  }

  public function buscar()
  {
    $limite = 10;
    $paginasTotal = 0;
    $intervaloInicio = 0;
    $intervaloFim = 0;
    $resultadoBuscar = [];
    $condicao = [];

    $pagina = intval($_GET['pagina'] ?? 0);
    $textoBusca = htmlspecialchars($_GET['texto_busca'] ?? '');

    if ($textoBusca) {
      $condicao['Artigo.titulo LIKE'] = '%' . $textoBusca . '%';
    }

    $resultado = $this->artigoModel->condicao($condicao)
                                   ->contar('Artigo.id');
    
    $artigosTotal = intval($resultado['total'] ?? 0);

    if ($artigosTotal > 0) {
      $paginasTotal = ceil($artigosTotal / $limite);

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
        'Categoria.nome' => 'DESC',
        'Artigo.criado' => 'DESC',
        'Artigo.ordem' => 'ASC',
      ];

      $uniao2 = [
        'Categoria',
      ];

      $limite = 10;

      $resultado = $this->artigoModel->condicao($condicao)
                                     ->uniao2($uniao2, 'LEFT')
                                     ->ordem($ordem)
                                     ->pagina($limite, $pagina)
                                     ->buscar($colunas);

      if (isset($resultado[0]['Artigo.id'])) {
        $resultadoBuscar = $resultado;
      }

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
    $this->visao->renderizar('/busca/index');
  }
}