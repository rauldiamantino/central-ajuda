<?php
namespace app\Controllers;
use app\Models\DashboardInicioModel;

class DashboardInicioController extends DashboardController
{
  private $dashboardInicioModel;

  public function __construct()
  {
    parent::__construct();

    $this->dashboardInicioModel = new DashboardInicioModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function dashboardVer()
  {
    $artigosPopulares = $this->dashboardInicioModel->buscarFeedbacks(true);
    $artigosMenosPopulares = $this->dashboardInicioModel->buscarFeedbacks();

    $this->visao->variavel('artigosPopulares', $artigosPopulares);
    $this->visao->variavel('artigosMenosPopulares', $artigosMenosPopulares);
    $this->visao->variavel('totalUsuarios', $this->contarUsuarios());
    $this->visao->variavel('totalArtigos', $this->contarArtigos());
    $this->visao->variavel('totalCategorias', $this->contarCategorias());
    $this->visao->variavel('metaTitulo', 'Início - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'dashboard');
    $this->visao->renderizar('/dashboard/index');
  }

  private function contarUsuarios()
  {
    $condicoes = [
      [
        'campo' => 'Usuario.padrao',
        'operador' => '!=',
        'valor' => USUARIO_SUPORTE,
      ],
    ];

    if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      $condicoes = [];
    }

    $resultado = $this->usuarioModel->contar('Usuario.id')
                                    ->condicao($condicoes)
                                    ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarArtigos()
  {
    $resultado = $this->artigoModel->contar('Artigo.id')
                                   ->executarConsulta();

    return $resultado['total'] ?? 0;
  }

  private function contarCategorias()
  {
    $resultado = $this->categoriaModel->contar('Categoria.id')
                                      ->executarConsulta();

    return $resultado['total'] ?? 0;
  }
}