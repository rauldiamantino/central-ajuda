<?php
namespace app\Roteamento;
use app\Controllers\TesteController;
use app\Controllers\BuscaController;
use app\Controllers\AjusteController;
use app\Controllers\LoginController;
use app\Controllers\CadastroController;
use app\Controllers\UsuarioController;
use app\Controllers\EmpresaController;
use app\Controllers\EmpresaCadastroController;
use app\Controllers\DashboardController;
use app\Controllers\ArtigoController;
use app\Controllers\ConteudoController;
use app\Controllers\CategoriaController;
use app\Controllers\PublicoController;
use app\Controllers\PaginaErroController;
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
      'PUT:/ajustes' => [AjusteController::class, 'atualizar'],
      'GET:/teste' => [TesteController::class, 'testar'],
      'GET:/erro' => [PaginaErroController::class, 'erroVer'],
      'GET:/dashboard' => [DashboardController::class, 'dashboardVer'],
      'GET:/dashboard/ajustes' => [AjusteController::class, 'ajustesVer'],
      'GET:/dashboard/artigos' => [ArtigoController::class, 'artigosVer'],
      'GET:/dashboard/artigo/editar/{id}' => [ArtigoController::class, 'artigoEditarVer'],
      'GET:/dashboard/artigo/adicionar' => [ArtigoController::class, 'artigoAdicionarVer'],
      'GET:/dashboard/categorias' => [CategoriaController::class, 'categoriasVer'],
      'GET:/dashboard/categoria/editar/{id}' => [CategoriaController::class, 'categoriaEditarVer'],
      'GET:/dashboard/categoria/adicionar' => [CategoriaController::class, 'categoriaAdicionarVer'],
      'GET:/dashboard/usuarios' => [UsuarioController::class, 'UsuariosVer'],
      'GET:/dashboard/usuario/editar/{id}' => [UsuarioController::class, 'usuarioEditarVer'],
      'GET:/dashboard/usuario/adicionar' => [UsuarioController::class, 'usuarioAdicionarVer'],
      'GET:/dashboard/empresa/editar' => [EmpresaController::class, 'empresaEditarVer'],
      
      'GET:/publico' => [PublicoController::class, 'publicoCategoriasVer'],
      'GET:/publico/categoria/{id}' => [PublicoController::class, 'publicoCategoriaVer'],
      'GET:/publico/artigo/{id}' => [PublicoController::class, 'publicoArtigoVer'],
      'POST:/publico/buscar' => [BuscaController::class, 'buscarArtigos'],
      'GET:/publico/buscar' => [BuscaController::class, 'buscarArtigos'],

      'GET:/login' => [LoginController::class, 'loginVer'],
      'GET:/cadastro' => [CadastroController::class, 'cadastroVer'],
      'POST:/login' => [LoginController::class, 'login'],
      'GET:/logout' => [LoginController::class, 'logout'],

      'GET:/artigos' => [ArtigoController::class, 'buscar'],
      'GET:/artigo/{id}' => [ArtigoController::class, 'buscar'],
      'POST:/artigo' => [ArtigoController::class, 'adicionar'],
      'PUT:/artigo/{id}' => [ArtigoController::class, 'atualizar'],
      'PUT:/artigo/ordem' => [ArtigoController::class, 'atualizarOrdem'],
      'DELETE:/artigo/{id}' => [ArtigoController::class, 'apagar'],

      'GET:/conteudos' => [ConteudoController::class, 'buscar'],
      'GET:/conteudos/{id}' => [ConteudoController::class, 'buscar'],
      'POST:/conteudo' => [ConteudoController::class, 'adicionar'],
      'PUT:/conteudo/{id}' => [ConteudoController::class, 'atualizar'],
      'PUT:/conteudo/ordem' => [ConteudoController::class, 'atualizarOrdem'],
      'DELETE:/conteudo/{id}' => [ConteudoController::class, 'apagar'],

      'GET:/categorias' => [CategoriaController::class, 'buscar'],
      'GET:/categoria/{id}' => [CategoriaController::class, 'buscar'],
      'POST:/categoria' => [CategoriaController::class, 'adicionar'],
      'PUT:/categoria/{id}' => [CategoriaController::class, 'atualizar'],
      'PUT:/categoria/ordem' => [CategoriaController::class, 'atualizarOrdem'],
      'DELETE:/categoria/{id}' => [CategoriaController::class, 'apagar'],

      'GET:/usuarios' => [UsuarioController::class, 'buscar'],
      'GET:/usuario/{id}' => [UsuarioController::class, 'buscar'],
      'POST:/usuario' => [UsuarioController::class, 'adicionar'],
      'PUT:/usuario/{id}' => [UsuarioController::class, 'atualizar'],
      'DELETE:/usuario/{id}' => [UsuarioController::class, 'apagar'],

      'GET:/empresas/cadastro/{id}' => [EmpresaCadastroController::class, 'buscar'],
      'POST:/empresas/cadastro' => [EmpresaCadastroController::class, 'adicionar'],
      'POST:/cadastro' => [CadastroController::class, 'adicionar'],
      'PUT:/empresa/{id}' => [EmpresaController::class, 'atualizar'],
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

    $chaveRota = $metodo . ':' . $url;
    $id = (int) basename($url);
    $chaveRota = str_replace($id, '{id}', $chaveRota);

    // Subdomínio
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $partes = explode('.', $host);
    $subdominio = $partes[0] ?? '';
    $empresa_id = 0;

    if (count($partes) == 2 and strpos($chaveRota, '/publico')) {
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
    if (isset($_SESSION['usuario']['empresa_id']) and isset($_SESSION['empresa_id'])) {

      if (intval($_SESSION['usuario']['empresa_id']) > 0 and intval($_SESSION['empresa_id']) > 0) {
        $empresa_id = intval($_SESSION['empresa_id']);
      }
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