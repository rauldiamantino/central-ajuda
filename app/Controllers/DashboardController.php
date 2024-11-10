<?php
namespace app\Controllers;
use app\Models\DashboardModel;
use app\Controllers\Controller;
use app\Controllers\ViewRenderer;
use app\Models\DashboardArtigoModel;
use app\Models\DashboardUsuarioModel;
use app\Models\DashboardCategoriaModel;

class DashboardController extends Controller
{
  protected $artigoModel;
  protected $usuarioModel;
  protected $categoriaModel;
  protected $dashboardModel;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->artigoModel = new DashboardArtigoModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->usuarioModel = new DashboardUsuarioModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->categoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->dashboardModel = new DashboardModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->visao = new ViewRenderer('/dashboard');
  }

  public function dashboardVer()
  {
    // Usuários
    $condicoes = [];

    // Oculta usuários de suporte
    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $condicoes[] = [
        'campo' => 'Usuario.padrao',
        'operador' => '!=',
        'valor' => USUARIO_SUPORTE,
      ];
    }

    $usuariosTotal = $this->usuarioModel->contar('Usuario.id')
                                        ->condicao($condicoes)
                                        ->executarConsulta();

    // Artigos
    $artigosTotal = $this->artigoModel->contar('Artigo.id')
                                      ->executarConsulta();

    // Categorias
    $categoriasTotal = $this->categoriaModel->contar('Categoria.id')
                                            ->executarConsulta();

    $this->visao->variavel('totalUsuarios', $usuariosTotal['total'] ?? 0);
    $this->visao->variavel('totalArtigos', $artigosTotal['total'] ?? 0);
    $this->visao->variavel('totalCategorias', $categoriasTotal['total'] ?? 0);
    $this->visao->variavel('titulo', 'Início');
    $this->visao->variavel('paginaMenuLateral', 'dashboard');
    $this->visao->renderizar('/dashboard/index');
  }
}