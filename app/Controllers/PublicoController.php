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
  protected $empresaNome;
  protected $empresaCnpj;
  protected $telefone;
  protected $logo;
  protected $favicon;
  protected $corPrimaria;
  protected $urlSite;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->cacheTempo = 60 * 60;

    $this->obterDadosEmpresa();
    $this->dashboardCategoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);

    $this->visao = new ViewRenderer('/publico');
    $this->visao->variavel('logo', $this->logo);
    $this->visao->variavel('favicon', $this->favicon);
    $this->visao->variavel('corPrimaria', $this->corPrimaria);
    $this->visao->variavel('subdominio', $this->subdominio);
    $this->visao->variavel('empresaId', $this->empresaId);
    $this->visao->variavel('empresaNome', $this->empresaNome);
    $this->visao->variavel('empresaCnpj', $this->empresaCnpj);
    $this->visao->variavel('telefoneEmpresa', $this->telefone);
    $this->visao->variavel('urlSite', $this->urlSite);

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
      'Categoria.icone',
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

    $cacheNome = 'publico-categorias-inicio';
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->dashboardCategoriaModel->selecionar($colunas)
                                                 ->condicao($condicoes)
                                                 ->existe($existe)
                                                 ->ordem($ordem)
                                                 ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    if ((int) $this->buscarAjuste('publico_cate_abrir_primeira') == ATIVO and isset($resultado[0]['Categoria']['id']) and $this->subdominio) {
      $this->redirecionar('/' . $this->subdominio . '/categoria/' . $resultado[0]['Categoria']['id']);
    }

    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('titulo', 'Público');
    $this->visao->variavel('menuLateral', true);
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
      'Empresa.nome',
      'Empresa.cnpj',
      'Empresa.favicon',
      'Empresa.cor_primaria',
      'Empresa.telefone',
      'Empresa.url_site',
    ];

    $cacheNome = 'publico-dados-empresa';
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->dashboardEmpresaModel->selecionar($colunas)
                                               ->condicao($condicoes)
                                               ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $this->cacheTempo, $this->empresaPadraoId);
    }

    $this->logo = $resultado[0]['Empresa']['logo'] ?? '';
    $this->favicon = $resultado[0]['Empresa']['favicon'] ?? '';
    $this->corPrimaria = $resultado[0]['Empresa']['cor_primaria'] ?? '';
    $this->urlSite = $resultado[0]['Empresa']['url_site'] ?? '';
    $this->empresaNome = $resultado[0]['Empresa']['nome'] ?? '';
    $this->telefone = intval($resultado[0]['Empresa']['telefone'] ?? 0);
    $this->subdominio = $this->sessaoUsuario->buscar('subdominio');
    $this->empresaId = $this->sessaoUsuario->buscar('empresaPadraoId');

    $cnpj = $resultado[0]['Empresa']['cnpj'] ?? '';

    if ($cnpj && strlen($cnpj) == 14) {
      $this->empresaCnpj = vsprintf('%s.%s.%s/%s-%s', [
        substr($cnpj, 0, 2),
        substr($cnpj, 2, 3),
        substr($cnpj, 5, 3),
        substr($cnpj, 8, 4),
        substr($cnpj, 12, 2)
      ]);
    }
  }

  public function exibirInativos(): bool
  {
    return false;
  }
}