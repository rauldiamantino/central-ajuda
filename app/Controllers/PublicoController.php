<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Core\Helper;
use app\Controllers\ViewRenderer;
use app\Models\DashboardEmpresaModel;
use app\Models\DashboardCategoriaModel;

class PublicoController extends Controller
{
  protected $publicoModel;
  protected $dashboardCategoriaModel;
  protected $dashboardEmpresaModel;
  protected $cacheTempo;
  protected $subdominio;
  protected $subdominio_2;
  protected $empresaId;
  protected $empresaNome;
  protected $empresaCnpj;
  protected $telefone;
  protected $logo;
  protected $favicon;
  protected $corPrimaria;
  protected $urlSite;
  protected $visao;
  protected $metaTituloEmpresa;
  protected $metaDescricaoEmpresa;

  public function __construct()
  {
    parent::__construct();

    $this->cacheTempo = 60 * 60;

    $this->obterDadosEmpresa();
    $this->dashboardCategoriaModel = new DashboardCategoriaModel($this->usuarioLogado, $this->empresaPadraoId);

    if ($this->subdominio_2) {
      $this->subdominio = '';
    }

    $urlCanonica = 'https://' . $this->subdominio . '.360help.com.br';

    if ($this->subdominio_2) {
      $urlCanonica = $this->subdominio_2;
    }

    $this->visao = new ViewRenderer('/publico');
    $this->visao->variavel('logo', $this->logo);
    $this->visao->variavel('favicon', $this->favicon);
    $this->visao->variavel('corPrimaria', $this->corPrimaria);
    $this->visao->variavel('subdominio', $this->subdominio);
    $this->visao->variavel('subdominio_2', $this->subdominio_2);
    $this->visao->variavel('empresaId', $this->empresaId);
    $this->visao->variavel('empresaNome', $this->empresaNome);
    $this->visao->variavel('empresaCnpj', $this->empresaCnpj);
    $this->visao->variavel('telefoneEmpresa', $this->telefone);
    $this->visao->variavel('urlSite', $this->urlSite);
    $this->visao->variavel('metaTitulo', $this->metaTituloEmpresa);
    $this->visao->variavel('metaDescricao', $this->metaDescricaoEmpresa);
    $this->visao->variavel('urlCanonica', $urlCanonica);
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

    if ((int) Helper::ajuste('publico_cate_abrir_primeira') == ATIVO and isset($resultado[0]['Categoria']['id']) and $this->subdominio) {
      $this->redirecionar('/categoria/' . $resultado[0]['Categoria']['id']);
    }

    $metaTitulo = $this->metaTituloEmpresa;
    $metaDescricao = $this->metaDescricaoEmpresa;

    if (empty($metaTitulo)) {
      $metaTitulo = 'Início';
    }

    $urlCanonica = 'https://' . $this->subdominio . '.360help.com.br';

    if ($this->subdominio_2) {
      $urlCanonica = $this->subdominio_2;
    }

    $this->visao->variavel('urlCanonica', $urlCanonica);
    $this->visao->variavel('categorias', $resultado);
    $this->visao->variavel('metaTitulo', $metaTitulo);
    $this->visao->variavel('metaDescricao', $metaDescricao);
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
      'Empresa.meta_titulo',
      'Empresa.meta_descricao',
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
    $this->subdominio_2 = $this->sessaoUsuario->buscar('subdominio_2');
    $this->empresaId = $this->sessaoUsuario->buscar('empresaPadraoId');
    $this->metaTituloEmpresa = $resultado[0]['Empresa']['meta_titulo'] ?? '';
    $this->metaDescricaoEmpresa = $resultado[0]['Empresa']['meta_descricao'] ?? '';

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