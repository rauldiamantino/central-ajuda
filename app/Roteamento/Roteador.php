<?php
namespace app\Roteamento;
use app\Controllers\DashboardAjusteController;
use app\Controllers\DashboardArtigoController;
use app\Controllers\DashboardCadastroController;
use app\Controllers\DashboardCategoriaController;
use app\Controllers\DashboardConteudoController;
use app\Controllers\DashboardController;
use app\Controllers\DashboardEmpresaController;
use app\Controllers\DashboardLoginController;
use app\Controllers\DashboardUsuarioController;
use app\Controllers\PaginaErroController;
use app\Controllers\PublicoArtigoController;
use app\Controllers\PublicoBuscaController;
use app\Controllers\PublicoCategoriaController;
use app\Controllers\PublicoController;
use app\Controllers\TesteController;
use app\Controllers\FirebaseController;
use app\Models\Model;

class Roteador
{
  protected $rotas;
  protected $paginaErro;

  public function __construct()
  {
    $this->paginaErro = new PaginaErroController();

    $this->rotas = [
      // Público
      'GET:/{subdominio}' => [PublicoController::class, 'publicoVer'],
      'GET:/{subdominio}/categoria/{id}' => [PublicoCategoriaController::class, 'categoriaVer'],
      'GET:/{subdominio}/artigo/{id}' => [PublicoArtigoController::class, 'artigoVer'],
      'POST:/{subdominio}/buscar' => [PublicoBuscaController::class, 'buscar'],
      'GET:/{subdominio}/buscar' => [PublicoBuscaController::class, 'buscar'],

      // Dashboard
      'GET:/{subdominio}/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/{subdominio}/dashboard/ajustes' => [DashboardAjusteController::class, 'ajustesVer'],
      'GET:/{subdominio}/dashboard/artigos' => [DashboardArtigoController::class, 'artigosVer'],
      'GET:/{subdominio}/dashboard/artigo/editar/{id}' => [DashboardArtigoController::class, 'artigoEditarVer'],
      'GET:/{subdominio}/dashboard/artigo/adicionar' => [DashboardArtigoController::class, 'artigoAdicionarVer'],
      'GET:/{subdominio}/dashboard/categorias' => [DashboardCategoriaController::class, 'categoriasVer'],
      'GET:/{subdominio}/dashboard/categoria/editar/{id}' => [DashboardCategoriaController::class, 'categoriaEditarVer'],
      'GET:/{subdominio}/dashboard/categoria/adicionar' => [DashboardCategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/{subdominio}/dashboard/usuarios' => [DashboardUsuarioController::class, 'UsuariosVer'],
      'GET:/{subdominio}/dashboard/usuario/editar/{id}' => [DashboardUsuarioController::class, 'usuarioEditarVer'],
      'GET:/{subdominio}/dashboard/usuario/adicionar' => [DashboardUsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/{subdominio}/dashboard/empresa/editar' => [DashboardEmpresaController::class, 'empresaEditarVer'],

      // CRUD
      'PUT:/ajustes' => [DashboardAjusteController::class, 'atualizar'],
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],

      'GET:/login' => [DashboardLoginController::class, 'loginVer'],
      'GET:/cadastro' => [DashboardCadastroController::class, 'cadastroVer'],
      'POST:/login' => [DashboardLoginController::class, 'login'],
      'GET:/logout' => [DashboardLoginController::class, 'logout'],

      'GET:/artigos' => [DashboardArtigoController::class, 'buscar'],
      'GET:/artigo/{id}' => [DashboardArtigoController::class, 'buscar'],
      'POST:/artigo' => [DashboardArtigoController::class, 'adicionar'],
      'PUT:/artigo/{id}' => [DashboardArtigoController::class, 'atualizar'],
      'PUT:/artigo/ordem' => [DashboardArtigoController::class, 'atualizarOrdem'],
      'DELETE:/artigo/{id}' => [DashboardArtigoController::class, 'apagar'],

      'GET:/conteudos' => [DashboardConteudoController::class, 'buscar'],
      'GET:/conteudos/{id}' => [DashboardConteudoController::class, 'buscar'],
      'POST:/conteudo' => [DashboardConteudoController::class, 'adicionar'],
      'PUT:/conteudo/{id}' => [DashboardConteudoController::class, 'atualizar'],
      'PUT:/conteudo/ordem' => [DashboardConteudoController::class, 'atualizarOrdem'],
      'DELETE:/conteudo/{id}' => [DashboardConteudoController::class, 'apagar'],

      'GET:/categorias' => [DashboardCategoriaController::class, 'buscar'],
      'GET:/categoria/{id}' => [DashboardCategoriaController::class, 'buscar'],
      'POST:/categoria' => [DashboardCategoriaController::class, 'adicionar'],
      'PUT:/categoria/{id}' => [DashboardCategoriaController::class, 'atualizar'],
      'PUT:/categoria/ordem' => [DashboardCategoriaController::class, 'atualizarOrdem'],
      'DELETE:/categoria/{id}' => [DashboardCategoriaController::class, 'apagar'],

      'GET:/usuarios' => [DashboardUsuarioController::class, 'buscar'],
      'GET:/usuario/{id}' => [DashboardUsuarioController::class, 'buscar'],
      'POST:/usuario' => [DashboardUsuarioController::class, 'adicionar'],
      'PUT:/usuario/{id}' => [DashboardUsuarioController::class, 'atualizar'],
      'DELETE:/usuario/{id}' => [DashboardUsuarioController::class, 'apagar'],

      'POST:/cadastro' => [DashboardCadastroController::class, 'adicionar'],
      'PUT:/empresa/{id}' => [DashboardEmpresaController::class, 'atualizar'],

      'GET:/firebase' => [FirebaseController::class, 'credenciais'],
    ];
  }

  public function rotear()
  {
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $metodo = $_SERVER['REQUEST_METHOD'];
    $metodoOculto = $_POST['_method'] ?? null;

    if ($metodoOculto and in_array(strtoupper($metodoOculto), ['PUT', 'DELETE'])) {
      $metodo = strtoupper($metodoOculto);
    }

    $subdominios = [
      'teste',
      'teste2',
      'luminaon',
    ];

    // Subdomínio
    $partesUrl = explode('/', $url);
    $subdominio = $partesUrl[1] ?? '';

    if (! in_array($subdominio, $subdominios)) {
      $subdominio = '';
    }

    $chaveRota = $metodo . ':' . $url;
    $id = (int) basename($url);
    $chaveRota = str_replace($id, '{id}', $chaveRota);
    $chaveRota = str_replace($subdominio, '{subdominio}', $chaveRota);

    // Público - Recupera EmpresaID
    $sql = 'SELECT
              `Empresa`.`id`
            FROM
              `empresas` AS `Empresa`
            WHERE
              `Empresa`.`subdominio` = ?
              AND `Empresa`.`ativo` = 1
            ORDER BY
              `Empresa`.`id` ASC
            LIMIT
              1';

    $params = [
      0 => $subdominio,
    ];

    $model = new Model('Empresa');
    $resultado = $model->executarQuery($sql, $params);
    $empresaId = intval($resultado[0]['id'] ?? 0);

    // Dashboard - Somente para usuário logado
    if (strpos($chaveRota, '/{subdominio}/dashboard')) {
      $empresaId = intval($_SESSION['usuario']['empresa_id'] ?? 0);

      if ($empresaId == 0) {
        header('Location: /login');
        exit;
      }
    }

    if ($empresaId == 0) {
      $rotasPermitidasCrud = [
        'PUT:/ajustes',
        'GET:/teste',
        'GET:/erro',
        //
        'GET:/login',
        'GET:/cadastro',
        'POST:/login',
        'GET:/logout',
        //
        'GET:/artigos',
        'GET:/artigo/{id}',
        'POST:/artigo',
        'PUT:/artigo/{id}',
        'PUT:/artigo/ordem',
        'DELETE:/artigo/{id}',
        //
        'GET:/conteudos',
        'GET:/conteudos/{id}',
        'POST:/conteudo',
        'PUT:/conteudo/{id}',
        'PUT:/conteudo/ordem',
        'DELETE:/conteudo/{id}',
        //
        'GET:/categorias',
        'GET:/categoria/{id}',
        'POST:/categoria',
        'PUT:/categoria/{id}',
        'PUT:/categoria/ordem',
        'DELETE:/categoria/{id}',
        //
        'GET:/usuarios',
        'GET:/usuario/{id}',
        'POST:/usuario',
        'PUT:/usuario/{id}',
        'DELETE:/usuario/{id}',
        //
        'POST:/cadastro',
        'PUT:/empresa/{id}',
        //
        'GET:/firebase',
      ];

      if (! in_array($chaveRota, $rotasPermitidasCrud)) {
        return $this->paginaErro->erroVer();
      }

      // Usuário logado
      $empresaId = intval($_SESSION['usuario']['empresa_id'] ?? 0);
    }

    // Grava EmpresaID na sessão
    $_SESSION['empresa_id'] = $empresaId;
    $_SESSION['subdominio'] = $subdominio;

    if (isset($this->rotas[ $chaveRota ])) {
      $rota = $this->rotas[ $chaveRota ];
      $controlador = new $rota[0]();
      $metodo = $rota[1];

      if ($id) {
        $controlador->$metodo($id);
      }
      else {
        $controlador->$metodo();
      }
    }
    else {
      $this->paginaErro->erroVer();
    }
  }
}