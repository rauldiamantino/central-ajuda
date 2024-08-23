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
use app\Models\Model;

class Roteador
{
  protected $rotas;
  protected $subdominios;
  protected $paginaErro;
  protected $empresaId;

  public function __construct()
  {
    $this->paginaErro = new PaginaErroController();

    $this->rotas = [
      'PUT:/ajustes' => [DashboardAjusteController::class, 'atualizar'],
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],
      'GET:/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/dashboard/ajustes' => [DashboardAjusteController::class, 'ajustesVer'],
      'GET:/dashboard/artigos' => [DashboardArtigoController::class, 'artigosVer'],
      'GET:/dashboard/artigo/editar/{id}' => [DashboardArtigoController::class, 'artigoEditarVer'],
      'GET:/dashboard/artigo/adicionar' => [DashboardArtigoController::class, 'artigoAdicionarVer'],
      'GET:/dashboard/categorias' => [DashboardCategoriaController::class, 'categoriasVer'],
      'GET:/dashboard/categoria/editar/{id}' => [DashboardCategoriaController::class, 'categoriaEditarVer'],
      'GET:/dashboard/categoria/adicionar' => [DashboardCategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/dashboard/usuarios' => [DashboardUsuarioController::class, 'UsuariosVer'],
      'GET:/dashboard/usuario/editar/{id}' => [DashboardUsuarioController::class, 'usuarioEditarVer'],
      'GET:/dashboard/usuario/adicionar' => [DashboardUsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/dashboard/empresa/editar' => [DashboardEmpresaController::class, 'empresaEditarVer'],
      
      'GET:/p/{subdominio}' => [PublicoController::class, 'publicoVer'],
      'GET:/p/{subdominio}/categoria/{id}' => [PublicoCategoriaController::class, 'categoriaVer'],
      'GET:/p/{subdominio}/artigo/{id}' => [PublicoArtigoController::class, 'artigoVer'],
      'POST:/p/{subdominio}/buscar' => [PublicoBuscaController::class, 'buscar'],
      'GET:/p/{subdominio}/buscar' => [PublicoBuscaController::class, 'buscar'],

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

    // Subdomínio
    $partesUrl = explode('/', $url);
    $subdominio = '';
    $subdominioLigado = false;

    if (count($partesUrl) >= 3 and $partesUrl[1] == 'p') {
      $subdominioLigado = true;
      $subdominio = $partesUrl[2];
    }

    $chaveRota = $metodo . ':' . $url;
    $id = (int) basename($url);
    $chaveRota = str_replace($id, '{id}', $chaveRota);

    if ($subdominioLigado) {
      $chaveRota = str_replace($subdominio, '{subdominio}', $chaveRota);
    }

    $empresa_id = 0;

    if ($subdominioLigado) {
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

      // Empresa encontrada no banco de dados
      if (isset($resultado[0]['id']) and $resultado[0]['id']) {
        $empresa_id = intval($resultado[0]['id']);
      }
    }
    elseif (strpos($chaveRota, '/dashboard') and isset($_SESSION['usuario']['empresa_id'])) {
      $empresa_id = intval($_SESSION['usuario']['empresa_id'] ?? 0);
    }
    elseif (strpos($chaveRota, '/dashboard') and ! isset($_SESSION['usuario']['empresa_id'])) {
      header('Location: /login');
      exit;
    }

    // Demais rotas apenas se for para a mesma empresa ID do usuário
    $sessaoUsuarioEmpresaId = intval($_SESSION['usuario']['empresa_id'] ?? 0);
    $sessaoEmpresaId = intval($_SESSION['empresa_id'] ?? 0);

    if ($subdominioLigado == false or $subdominio == 'ver' and $sessaoUsuarioEmpresaId > 0 and $sessaoEmpresaId > 0) {
      $empresa_id = $sessaoUsuarioEmpresaId;
    }

    if ($empresa_id == 0) {
      $rotasPermitidas = [
        'GET:/login',
        'POST:/login',
        'GET:/logout',
        'GET:/cadastro',
        'POST:/cadastro',
      ];

      if (! in_array($chaveRota, $rotasPermitidas)) {
        return $this->paginaErro->erroVer();
      }
    }

    $_SESSION['empresa_id'] = $empresa_id;
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