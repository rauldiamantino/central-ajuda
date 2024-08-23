<?php
namespace app\Controllers;
use app\Models\DashboardCategoriaModel;
use app\Models\DashboardEmpresaModel;
use app\Controllers\ViewRenderer;

class PublicoController extends Controller
{
  protected $publicoModel;
  protected $categoriaModel;
  protected $subdominio;
  protected $visao;

  public function __construct()
  {
    $this->obterSubdominio();
    $this->categoriaModel = new DashboardCategoriaModel();

    $this->visao = new ViewRenderer('/publico');
    $this->visao->variavel('subdominio', $this->subdominio);
    $this->visao->variavel('telefoneEmpresa', $this->obterTelefone());

  }

  public function publicoVer()
  {
    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $resultado = $this->categoriaModel->ordem(['Categoria.ordem' => 'ASC'])
                                      ->buscar($colunas);

    if (isset($resultado[0]['Categoria.id']) and $this->subdominio) {
      header('Location: /p/' . $this->subdominio . '/categoria/' . $resultado[0]['Categoria.id']);
      exit;
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('titulo', 'Público');
    $this->visao->renderizar('/index');
  }

  private function obterTelefone(): int
  {
    $telefone = intval($_SESSION['empresaTelefone'] ?? 0);

    if ($telefone == 0) {
      $this->empresaModel = new DashboardEmpresaModel();
      $resultado = $this->empresaModel->buscar(['Empresa.telefone']);
      $telefone = intval($resultado[0]['Empresa.telefone'] ?? 0);
      $_SESSION['empresaTelefone'] = $telefone;
    }

    return $telefone;
  }

  private function obterSubdominio(): void
  {
    $this->subdominio = $_SESSION['subdominio'] ?? null;
  }
}