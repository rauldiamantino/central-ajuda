<?php
namespace app\Controllers;
use app\Models\DashboardCategoriaModel;
use app\Models\DashboardEmpresaModel;
use app\Controllers\ViewRenderer;

class PublicoController extends Controller
{
  protected $publicoModel;
  protected $dashboardDategoriaModel;
  protected $dashboardEmpresaModel;
  protected $subdominio;
  protected $telefone;
  protected $logo;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->obterSubdominio();
    $this->obterDadosEmpresa();
    $this->dashboardDategoriaModel = new DashboardCategoriaModel();

    $this->visao = new ViewRenderer('/publico');
    $this->visao->variavel('logo', $this->logo);
    $this->visao->variavel('subdominio', $this->subdominio);
    $this->visao->variavel('telefoneEmpresa', $this->telefone);

  }

  public function publicoVer()
  {
    $condicoes = [
      'Categoria.ativo' => ATIVO,
    ];

    if ($this->exibirInativos()) {
      unset($condicoes['Categoria.ativo']);
    }

    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $resultado = $this->dashboardDategoriaModel->condicao($condicoes)
                                               ->ordem(['Categoria.ordem' => 'ASC'])
                                               ->buscar($colunas);

    if (isset($resultado[0]['Categoria.id']) and $this->subdominio) {
      $this->redirecionar('/' . $this->subdominio . '/categoria/' . $resultado[0]['Categoria.id']);
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('titulo', 'Público');
    $this->visao->renderizar('/index');
  }

  private function obterDadosEmpresa(): void
  {
    $this->dashboardEmpresaModel = new DashboardEmpresaModel();
    $resultado = $this->dashboardEmpresaModel->buscar(['Empresa.telefone']);
    $telefone = intval($resultado[0]['Empresa.telefone'] ?? 0);
    $this->sessaoUsuario->definir('empresaTelefone', $telefone);

    if ((int) $this->buscarAjuste('botao_whatsapp') == 1) {
      $this->telefone = $telefone;
    }

    $this->dashboardEmpresaModel = new DashboardEmpresaModel();
    $resultado = $this->dashboardEmpresaModel->buscar(['Empresa.logo']);
    $logo = $resultado[0]['Empresa.logo'] ?? '';
    $this->sessaoUsuario->definir('empresaLogo', $logo);

    $this->logo = $logo;
  }

  private function obterSubdominio(): void
  {
    $this->subdominio = $this->sessaoUsuario->buscar('subdominio');
  }

  public function exibirInativos(): bool
  {
    if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      return true;
    }

    if ($this->subdominio and $this->subdominio == $this->usuarioLogado['subdominio']) {
      return true;
    }

    return false;
  }
}