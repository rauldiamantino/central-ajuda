<?php
namespace app\Controllers;

use app\Controllers\DashboardEmpresaController;

class SEOController extends Controller
{
  private $artigoController;
  private $empresaController;
  private $categoriaController;
  private $empresaId;

  public function __construct()
  {
    $this->artigoController = new DashboardArtigoController();
    $this->empresaController = new DashboardEmpresaController();
    $this->categoriaController = new DashboardCategoriaController();
    $this->empresaId = $this->empresaController->empresaPadraoId;
  }

  public function robots()
  {
    if (HOST_LOCAL) {
      header('Location: /');
      exit;
    }

    header('Content-Type: text/plain');

    echo "User-agent: *\n";
    echo "Disallow: /*/dashboard/\n";
    echo "Disallow: /*/buscar/\n";
    echo "Disallow: /*/d/\n";
    echo "Disallow: /login\n";
    echo "Disallow: /login/suporte\n";
    echo "Disallow: /cadastro\n";
    echo "Disallow: /cadastro/sucesso\n";
    echo "Disallow: /logout\n";
    echo "Disallow: /cache/limpar\n";
    echo "Disallow: /erro\n";

    echo "Allow: /\n";
    echo "Sitemap: https://360help.com.br/sitemap.xml\n";
  }

  public function sitemapGeral()
  {
    if (HOST_LOCAL) {
      header('Location: /');
      exit;
    }

    header('Content-Type: application/xml');

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    echo "  <sitemap>\n";
    echo "    <loc>https://360help.com.br/sitemap-360help.xml</loc>\n";
    echo "  </sitemap>\n";

    $empresas = $this->empresaController->buscarEmpresas();

    // Dinâmico com todas as empresas
    if (isset($empresas[0]['Empresa']['id']) and $empresas[0]['Empresa']['id']) {
      foreach ($empresas as $linha):

        if (! isset($linha['Empresa']['subdominio']) or empty($linha['Empresa']['subdominio'])) {
          continue;
        }

        echo "  <sitemap>\n";
        echo "    <loc>https://360help.com.br/" . $linha['Empresa']['subdominio'] . '/sitemap.xml' . "</loc>\n";
        echo "  </sitemap>\n";
      endforeach;
    }

    echo "</sitemapindex>";
  }

  public function sitemapEmpresa()
  {
    if (HOST_LOCAL) {
      header('Location: /');
      exit;
    }

    header('Content-Type: application/xml');

    $empresa = $this->empresaController->buscarEmpresaSemId('id', $this->empresaId);
    $subdominio = $empresa[0]['Empresa']['subdominio'] ?? '';

    if (empty($subdominio)) {
      return ['erro' => 'Empresa não encontrada'];
    }

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    // Base
    echo "  <url>\n";
    echo "    <loc>https://360help.com.br/" . $subdominio . "</loc>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>1.0</priority>\n";
    echo "  </url>\n";

    // Categorias
    foreach ($this->categoriaController->buscar() as $linha) {

      if (! isset($linha['Categoria']['id']) or empty($linha['Categoria']['id'])) {
        continue;
      }

      echo "  <url>\n";
      echo "    <loc>https://360help.com.br/" . $subdominio . "/categoria/" . $linha['Categoria']['id'] . "</loc>\n";
      echo "    <changefreq>weekly</changefreq>\n";
      echo "    <priority>0.8</priority>\n";
      echo "  </url>\n";
    }

    // Artigos
    foreach ($this->artigoController->buscar() as $linha):

      if (! isset($linha['Artigo']['id']) or empty($linha['Artigo']['id'])) {
        continue;
      }

      echo "  <url>\n";
      echo "    <loc>https://360help.com.br/" . $subdominio . "/artigo/" . $linha['Artigo']['id'] . "</loc>\n";
      echo "    <changefreq>monthly</changefreq>\n";
      echo "    <priority>0.7</priority>\n";
      echo "  </url>\n";
    endforeach;

    echo "</urlset>";
  }
}