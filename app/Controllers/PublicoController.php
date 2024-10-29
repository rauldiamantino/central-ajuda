<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardCategoriaModel;
use app\Models\DashboardEmpresaModel;
use app\Controllers\ViewRenderer;

class PublicoController extends Controller
{
  protected $publicoModel;
  protected $dashboardCategoriaModel;
  protected $dashboardEmpresaModel;
  protected $cacheTempo;
  protected $subdominio;
  protected $empresaId;
  protected $telefone;
  protected $logo;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->cacheTempo = 60 * 60;

    $this->obterDadosEmpresa();
    $this->dashboardCategoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);

    $this->visao = new ViewRenderer('/publico');
    $this->visao->variavel('logo', $this->logo);
    $this->visao->variavel('subdominio', $this->subdominio);
    $this->visao->variavel('empresaId', $this->empresaId);
    $this->visao->variavel('telefoneEmpresa', $this->telefone);

  }

  public function publicoVer()
  {
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

    $limite = 12;

    $cacheNome = 'publico-categoria_resultado-' . md5(serialize($condicoes));
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->dashboardCategoriaModel->selecionar($colunas)
                                                 ->condicao($condicoes)
                                                 ->existe($existe)
                                                 ->ordem($ordem)
                                                 ->limite($limite)
                                                 ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    if ((int) $this->buscarAjuste('publico_cate_abrir_primeira') == ATIVO and isset($resultado[0]['Categoria']['id']) and $this->subdominio) {
      $this->redirecionar('/' . $this->subdominio . '/categoria/' . $resultado[0]['Categoria']['id']);
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('titulo', 'Público');
    $this->visao->variavel('menuLateral', false);
    $this->visao->variavel('inicio', true);
    $this->visao->renderizar('/inicio/index');
  }

  private function obterDadosEmpresa(): void
  {
    $this->dashboardEmpresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);

    $condicoes[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $this->empresaPadraoId,
    ];

    $colunas = [
      'Empresa.logo',
      'Empresa.telefone',
    ];

    $cacheNome = 'publico_dados-empresa';
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->dashboardEmpresaModel->selecionar($colunas)
                                               ->condicao($condicoes)
                                               ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    $this->logo = $resultado[0]['Empresa']['logo'] ?? '';
    $this->telefone = intval($resultado[0]['Empresa']['telefone'] ?? 0);
    $this->subdominio = $this->sessaoUsuario->buscar('subdominio');
    $this->empresaId = $this->sessaoUsuario->buscar('empresaPadraoId');
  }

  public function exibirInativos(): bool
  {
    if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      return true;
    }

    if ($this->empresaId and $this->empresaId == $this->usuarioLogado['empresaId']) {
      return true;
    }

    return false;
  }
}