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

  public function robotsEmpresa()
  {
    // if (HOST_LOCAL) {
    //   header('Location: /');
    //   exit;
    // }

    $empresa = $this->empresaController->buscarEmpresaSemId('id', $this->empresaId);
    $subdominio = $empresa[0]['Empresa']['subdominio'] ?? '';
    $empresaId = $empresa[0]['Empresa']['id'] ?? 0;

    if (empty($subdominio)) {
      return ['erro' => 'Empresa não encontrada'];
    }

    // Domínio personalizado
    $dominio = $empresa[0]['Empresa']['subdominio_2'] ?? '';

    if (empty($dominio)) {
      $dominio = 'https://' . $subdominio . '.360help.com.br';
    }

    header('Content-Type: text/plain');

    echo "User-agent: *\n";

    if ($empresaId == 1) {
      echo 'User-agent: *';
      echo 'Disallow: /';
    }
    elseif (isset($empresa[0]['Empresa']['subdominio_2']) and $empresa[0]['Empresa']['subdominio_2']) {
      echo "Disallow: /dashboard/\n";
      echo "Disallow: /buscar/\n";
      echo "Disallow: /d/\n";
      echo "Disallow: /cache/limpar\n";

      echo "Allow: /artigo/\n";
      echo "Allow: /categoria/\n";
      echo "Allow: /\n";
      echo "Sitemap: {$dominio}/sitemap.xml\n";
    }
    else {
      echo "Disallow: /dashboard/\n";
      echo "Disallow: /buscar/\n";
      echo "Disallow: /d/\n";

      echo "Allow: /artigo/\n";
      echo "Allow: /categoria/\n";
      echo "Allow: /\n";
      echo "Sitemap: {$dominio}/sitemap.xml\n";
    }

  }

  public function robotsGeral()
  {
    // if (HOST_LOCAL) {
    //   header('Location: /');
    //   exit;
    // }

    header('Content-Type: text/plain');

    echo "User-agent: *\n";
    echo "Disallow: /dashboard/\n";
    echo "Disallow: /buscar/\n";
    echo "Disallow: /d/\n";
    echo "Disallow: /login\n";
    echo "Disallow: /login/suporte\n";
    echo "Disallow: /cadastro\n";
    echo "Disallow: /cadastro/sucesso\n";
    echo "Disallow: /logout\n";
    echo "Disallow: /erro\n";

    echo "Allow: /\n";
    echo "Sitemap: https://360help.com.br/sitemap.xml\n";
  }

  public function sitemapGeral()
  {
    // if (HOST_LOCAL) {
    //   header('Location: /');
    //   exit;
    // }

    header('Content-Type: application/xml');

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    echo "  <url>\n";
    echo "    <loc>https://360help.com.br/</loc>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>1.0</priority>\n";
    echo "  </url>\n";

    echo "  <url>\n";
    echo "    <loc>https://360help.com.br/privacidade</loc>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>1.0</priority>\n";
    echo "  </url>\n";

    echo "  <url>\n";
    echo "    <loc>https://360help.com.br/termos</loc>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>1.0</priority>\n";
    echo "  </url>\n";
    echo "</urlset>";
  }

  public function sitemapEmpresa()
  {
    // if (HOST_LOCAL) {
    //   header('Location: /');
    //   exit;
    // }

    header('Content-Type: application/xml');

    $empresa = $this->empresaController->buscarEmpresaSemId('id', $this->empresaId);
    $subdominio = $empresa[0]['Empresa']['subdominio'] ?? '';
    $empresaId = $empresa[0]['Empresa']['id'] ?? 0;

    if ($empresaId == 1) {
      return ['erro' => 'Empresa não indexada'];
    }

    if (empty($subdominio)) {
      return ['erro' => 'Empresa não encontrada'];
    }

    // Domínio personalizado
    $dominio = $empresa[0]['Empresa']['subdominio_2'] ?? '';

    if (empty($dominio)) {
      $dominio = 'https://' . $subdominio . '.360help.com.br';
    }

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    // Base
    echo "  <url>\n";
    echo "    <loc>" . $dominio . "</loc>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>1.0</priority>\n";
    echo "  </url>\n";

    // Categorias
    foreach ($this->categoriaController->buscar() as $linha) {

      if (! isset($linha['Categoria']['id']) or empty($linha['Categoria']['id'])) {
        continue;
      }

      echo "  <url>\n";
      echo "    <loc>" . $dominio . "/categoria/" . $linha['Categoria']['id'] . '/' . $this->gerarSlug($linha['Categoria']['nome']) . "</loc>\n";
      echo "    <changefreq>weekly</changefreq>\n";
      echo "    <priority>0.8</priority>\n";
      echo "  </url>\n";
    }

    // Artigos
    foreach ($this->artigoController->buscar() as $linha):

      if (! isset($linha['Artigo']['codigo']) or empty($linha['Artigo']['codigo'])) {
        continue;
      }

      echo "  <url>\n";
      echo "    <loc>" . $dominio . "/artigo/" . $linha['Artigo']['codigo'] . '/' . $this->gerarSlug($linha['Artigo']['titulo']) . "</loc>\n";
      echo "    <changefreq>monthly</changefreq>\n";
      echo "    <priority>0.7</priority>\n";
      echo "  </url>\n";
    endforeach;

    echo "</urlset>";
  }
}