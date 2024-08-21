<?php
namespace app\Controllers;
use app\Models\PublicoModel;
use app\Controllers\ViewRenderer;
use app\Models\CategoriaModel;
use app\Models\ArtigoModel;
use app\Models\ConteudoModel;
use app\Models\EmpresaModel;

class PublicoController extends Controller
{
  protected $publicoModel;
  protected $categoriaModel;
  protected $artigoModel;
  protected $conteudoModel;
  protected $empresaModel;
  protected $subdominio;
  protected $visao;

  public function __construct()
  {
    $this->publicoModel = new PublicoModel();
    $this->categoriaModel = new CategoriaModel();
    $this->artigoModel = new ArtigoModel();
    $this->conteudoModel = new ConteudoModel();
    $this->empresaModel = new EmpresaModel();
    $this->visao = new ViewRenderer('/publico');

    // Botão do WhatsApp
    $telefoneEmpresa = intval($_SESSION['empresaTelefone'] ?? 0);

    if ($telefoneEmpresa == 0) {
      $resultado = $this->empresaModel->buscar(['Empresa.telefone']);
      $telefoneEmpresa = intval($resultado[0]['Empresa.telefone'] ?? 0);

      $_SESSION['empresaTelefone'] = $telefoneEmpresa;
    }
    
    $this->subdominio = $_SESSION['subdominio'] ?? null;
    $this->visao->variavel('subdominio', $this->subdominio);
    $this->visao->variavel('telefoneEmpresa', $telefoneEmpresa);
  }

  public function publicoVer()
  {
    $colunas = [
      'Categoria.id',
      'Categoria.nome',
    ];

    $resultado = $this->categoriaModel->ordem(['Categoria.ordem' => 'ASC'])
                                      ->buscar($colunas);

    if (isset($resultado[0]['Categoria.id']) and isset($this->subdominio)) {
      header('Location: /p/' . $this->subdominio . '/categoria/' . $resultado[0]['Categoria.id']);
      exit;
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('titulo', 'Público');
    $this->visao->renderizar('/index');
  }
}