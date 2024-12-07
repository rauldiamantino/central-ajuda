<?php
// Suporte
$suporteTotal = [
  'padrao' => [ USUARIO_SUPORTE ],
  'nivel' => [ USUARIO_TOTAL ],
];

$suporteTodos = [
  'padrao' => [ USUARIO_SUPORTE ],
  'nivel' => [ USUARIO_TOTAL, USUARIO_RESTRITO ],
];

// Logados
$total = [
  'padrao' => [ USUARIO_SUPORTE, USUARIO_SISTEMA, USUARIO_COMUM, USUARIO_PADRAO ],
  'nivel' => [ USUARIO_TOTAL ],
];

$todos = [
  'padrao' => [ USUARIO_SUPORTE, USUARIO_SISTEMA, USUARIO_COMUM,USUARIO_PADRAO ],
  'nivel' => [ USUARIO_TOTAL, USUARIO_RESTRITO ],
];

// Deslogados
$publico = $todos;
$publico['padrao'][] = 0;
$publico['nivel'][] = 0;

return [
  'publico' => [
    'GET:/'                                             => ['controlador' => [InicioController::class,                  'inicioVer'],                     'permissao' => $publico],
    'GET:/erro'                                         => ['controlador' => [PaginaErroController::class,              'erroVer'],                       'permissao' => $publico],
    'GET:/login'                                        => ['controlador' => [DashboardLoginController::class,          'loginVer'],                      'permissao' => $publico],
    'GET:/login/suporte'                                => ['controlador' => [DashboardLoginController::class,          'loginSuporteVer'],               'permissao' => $suporteTotal],
    'GET:/login/suporte/{id}'                           => ['controlador' => [DashboardLoginController::class,          'loginSuporteVer'],               'permissao' => $suporteTotal],
    'POST:/cadastro'                                    => ['controlador' => [DashboardCadastroController::class,       'adicionar'],                     'permissao' => $publico],
    'GET:/cadastro'                                     => ['controlador' => [DashboardCadastroController::class,       'cadastroVer'],                   'permissao' => $publico],
    'GET:/cadastro/sucesso'                             => ['controlador' => [DashboardCadastroController::class,       'cadastroSucessoVer'],            'permissao' => $publico],
    'POST:/login'                                       => ['controlador' => [DashboardLoginController::class,          'login'],                         'permissao' => $publico],
    'GET:/logout'                                       => ['controlador' => [DashboardLoginController::class,          'logout'],                        'permissao' => $publico],
    'POST:/d/assinaturas/receber'                       => ['controlador' => [AssinaturaReceberComponent::class,        'receberWebhook'],                'permissao' => $todos],
    'GET:/robots.txt'                                   => ['controlador' => [SEOController::class,                     'robotsGeral'],                   'permissao' => $publico],
    'GET:/sitemap.xml'                                  => ['controlador' => [SEOController::class,                     'sitemapGeral'],                  'permissao' => $publico],
    'GET:/cache/limpar'                                 => ['controlador' => [Cache::class,                             'resetarCacheTodos'],             'permissao' => $suporteTotal],
    'GET:/cache/limpar/{id}'                            => ['controlador' => [Cache::class,                             'resetarCacheEmpresa'],           'permissao' => $suporteTotal],
  ],
  'central' => [
    'GET:/{empresa}'                                    => ['controlador' => [PublicoController::class,                 'publicoVer'],                    'permissao' => $publico],
    'GET:/{empresa}/categoria/{id}/{slug}'              => ['controlador' => [PublicoCategoriaController::class,        'categoriaVer'],                  'permissao' => $publico],
    'GET:/{empresa}/artigo/{id}/{slug}'                 => ['controlador' => [PublicoArtigoController::class,           'artigoVer'],                     'permissao' => $publico],
    'GET:/{empresa}/categoria/{id}'                     => ['controlador' => [PublicoCategoriaController::class,        'categoriaVer'],                  'permissao' => $publico],
    'GET:/{empresa}/artigo/{id}'                        => ['controlador' => [PublicoArtigoController::class,           'artigoVer'],                     'permissao' => $publico],
    'POST:/{empresa}/buscar'                            => ['controlador' => [PublicoBuscaController::class,            'buscar'],                        'permissao' => $publico],
    'GET:/{empresa}/buscar'                             => ['controlador' => [PublicoBuscaController::class,            'buscar'],                        'permissao' => $publico],
    'GET:/{empresa}/robots.txt'                         => ['controlador' => [SEOController::class,                     'robotsEmpresa'],                 'permissao' => $publico],
    'GET:/{empresa}/sitemap.xml'                        => ['controlador' => [SEOController::class,                     'sitemapEmpresa'],                'permissao' => $publico],
  ],
  'centralPersonalizado' => [
    'GET:/'                                             => ['controlador' => [PublicoController::class,                 'publicoVer'],                    'permissao' => $publico],
    'GET:/categoria/{id}/{slug}'                        => ['controlador' => [PublicoCategoriaController::class,        'categoriaVer'],                  'permissao' => $publico],
    'GET:/artigo/{id}/{slug}'                           => ['controlador' => [PublicoArtigoController::class,           'artigoVer'],                     'permissao' => $publico],
    'GET:/categoria/{id}'                               => ['controlador' => [PublicoCategoriaController::class,        'categoriaVer'],                  'permissao' => $publico],
    'GET:/artigo/{id}'                                  => ['controlador' => [PublicoArtigoController::class,           'artigoVer'],                     'permissao' => $publico],
    'POST:/buscar'                                      => ['controlador' => [PublicoBuscaController::class,            'buscar'],                        'permissao' => $publico],
    'GET:/buscar'                                       => ['controlador' => [PublicoBuscaController::class,            'buscar'],                        'permissao' => $publico],
    'GET:/robots.txt'                                   => ['controlador' => [SEOController::class,                     'robotsEmpresa'],                 'permissao' => $publico],
    'GET:/sitemap.xml'                                  => ['controlador' => [SEOController::class,                     'sitemapEmpresa'],                'permissao' => $publico],
  ],
  'dashboard' => [
    'GET:/{empresa}/cache/limpar'                       => ['controlador' => [Cache::class,                             'resetarCacheSemId'],             'permissao' => $suporteTotal],
    'GET:/{empresa}/dashboard/ajustes'                  => ['controlador' => [DashboardAjusteController::class,         'ajustesVer'],                    'permissao' => $todos],
    'GET:/{empresa}/dashboard/artigos'                  => ['controlador' => [DashboardArtigoController::class,         'artigosVer'],                    'permissao' => $todos],
    'GET:/{empresa}/dashboard/artigo/editar/{id}'       => ['controlador' => [DashboardArtigoController::class,         'artigoEditarVer'],               'permissao' => $todos],
    'GET:/{empresa}/dashboard/conteudo/editar/{id}'     => ['controlador' => [DashboardConteudoController::class,       'conteudoEditarVer'],             'permissao' => $todos],
    'GET:/{empresa}/dashboard/conteudo/adicionar'       => ['controlador' => [DashboardConteudoController::class,       'conteudoAdicionarVer'],          'permissao' => $todos],
    'GET:/{empresa}/dashboard/categorias'               => ['controlador' => [DashboardCategoriaController::class,      'categoriasVer'],                 'permissao' => $todos],
    'GET:/{empresa}/dashboard/categoria/editar/{id}'    => ['controlador' => [DashboardCategoriaController::class,      'categoriaEditarVer'],            'permissao' => $todos],
    'GET:/{empresa}/dashboard/categoria/adicionar'      => ['controlador' => [DashboardCategoriaController::class,      'categoriaAdicionarVer'],         'permissao' => $todos],
    'GET:/{empresa}/dashboard/usuarios'                 => ['controlador' => [DashboardUsuarioController::class,        'UsuariosVer'],                   'permissao' => $todos],
    'GET:/{empresa}/dashboard/usuario/editar/{id}'      => ['controlador' => [DashboardUsuarioController::class,        'usuarioEditarVer'],              'permissao' => $total],
    'GET:/{empresa}/dashboard/usuario/adicionar'        => ['controlador' => [DashboardUsuarioController::class,        'usuarioAdicionarVer'],           'permissao' => $total],
    'GET:/{empresa}/dashboard/validar_assinatura'       => ['controlador' => [DashboardAssinaturaController::class,     'reprocessarAssinaturaAsaas'],    'permissao' => $suporteTotal],
    'GET:/{empresa}/d/calcular_consumo'                 => ['controlador' => [DashboardAssinaturaController::class,     'calcularConsumo'],               'permissao' => $todos],
    'GET:/{empresa}/d/buscar_cobrancas'                 => ['controlador' => [DashboardAssinaturaController::class,     'buscarCobrancas'],               'permissao' => $todos],
    'PUT:/{empresa}/d/ajustes'                          => ['controlador' => [DashboardAjusteController::class,         'atualizar'],                     'permissao' => $todos],
    'GET:/{empresa}/d/firebase'                         => ['controlador' => [DatabaseFirebaseComponent::class,         'credenciais'],                   'permissao' => $todos],
    'POST:/{empresa}/d/apagar-local'                    => ['controlador' => [DatabaseFirebaseComponent::class,         'apagarLocal'],                   'permissao' => $todos],
    'POST:/{empresa}/d/apagar-artigos-local'            => ['controlador' => [DatabaseFirebaseComponent::class,         'apagarArtigosLocal'],            'permissao' => $todos],
    'POST:/{empresa}/d/upload-local'                    => ['controlador' => [DatabaseFirebaseComponent::class,         'uploadLocal'],                   'permissao' => $todos],
    'POST:/{empresa}/d/upload-multiplas-local'          => ['controlador' => [DatabaseFirebaseComponent::class,         'uploadMultiplasLocal'],          'permissao' => $todos],
    'GET:/{empresa}/d/artigos'                          => ['controlador' => [DashboardArtigoController::class,         'buscar'],                        'permissao' => $todos],
    'GET:/{empresa}/d/artigo/{id}'                      => ['controlador' => [DashboardArtigoController::class,         'buscar'],                        'permissao' => $todos],
    'POST:/{empresa}/d/artigo'                          => ['controlador' => [DashboardArtigoController::class,         'adicionar'],                     'permissao' => $todos],
    'PUT:/{empresa}/d/artigo/{id}'                      => ['controlador' => [DashboardArtigoController::class,         'atualizar'],                     'permissao' => $todos],
    'PUT:/{empresa}/d/artigo/ordem'                     => ['controlador' => [DashboardArtigoController::class,         'atualizarOrdem'],                'permissao' => $todos],
    'DELETE:/{empresa}/d/artigo/{id}'                   => ['controlador' => [DashboardArtigoController::class,         'apagar'],                        'permissao' => $todos],
    'GET:/{empresa}/d/conteudos'                        => ['controlador' => [DashboardConteudoController::class,       'buscar'],                        'permissao' => $todos],
    'GET:/{empresa}/d/conteudos/{id}'                   => ['controlador' => [DashboardConteudoController::class,       'buscar'],                        'permissao' => $todos],
    'POST:/{empresa}/d/conteudo'                        => ['controlador' => [DashboardConteudoController::class,       'adicionar'],                     'permissao' => $todos],
    'PUT:/{empresa}/d/conteudo/{id}'                    => ['controlador' => [DashboardConteudoController::class,       'atualizar'],                     'permissao' => $todos],
    'PUT:/{empresa}/d/conteudo/ordem'                   => ['controlador' => [DashboardConteudoController::class,       'atualizarOrdem'],                'permissao' => $todos],
    'DELETE:/{empresa}/d/conteudo/{id}'                 => ['controlador' => [DashboardConteudoController::class,       'apagar'],                        'permissao' => $todos],
    'GET:/{empresa}/d/categorias'                       => ['controlador' => [DashboardCategoriaController::class,      'buscar'],                        'permissao' => $todos],
    'GET:/d/categoria/{id}'                             => ['controlador' => [DashboardCategoriaController::class,      'buscar'],                        'permissao' => $todos],
    'POST:/{empresa}/d/categoria'                       => ['controlador' => [DashboardCategoriaController::class,      'adicionar'],                     'permissao' => $todos],
    'PUT:/{empresa}/d/categoria/{id}'                   => ['controlador' => [DashboardCategoriaController::class,      'atualizar'],                     'permissao' => $todos],
    'PUT:/{empresa}/d/categoria/ordem'                  => ['controlador' => [DashboardCategoriaController::class,      'atualizarOrdem'],                'permissao' => $todos],
    'DELETE:/{empresa}/d/categoria/{id}'                => ['controlador' => [DashboardCategoriaController::class,      'apagar'],                        'permissao' => $todos],
    'GET:/{empresa}/d/usuarios'                         => ['controlador' => [DashboardUsuarioController::class,        'buscar'],                        'permissao' => $total],
    'GET:/{empresa}/d/usuario/{id}'                     => ['controlador' => [DashboardUsuarioController::class,        'buscar'],                        'permissao' => $total],
    'POST:/{empresa}/d/usuario'                         => ['controlador' => [DashboardUsuarioController::class,        'adicionar'],                     'permissao' => $total],
    'PUT:/{empresa}/d/usuario/{id}'                     => ['controlador' => [DashboardUsuarioController::class,        'atualizar'],                     'permissao' => $total],
    'DELETE:/{empresa}/d/usuario/{id}'                  => ['controlador' => [DashboardUsuarioController::class,        'apagar'],                        'permissao' => $total],
    'DELETE:/{empresa}/d/usuario/foto/{id}'             => ['controlador' => [DashboardUsuarioController::class,        'apagarFoto'],                    'permissao' => $total],
    'GET:/{empresa}/d/usuario/desbloquear/{id}'         => ['controlador' => [DashboardUsuarioController::class,        'desbloquear'],                   'permissao' => $suporteTotal],
  ],
  'dashboardVencida' => [
    'GET:/{empresa}/dashboard'                          => ['controlador' => [DashboardController::class,               'dashboardVer'],                  'permissao' => $todos],
    'GET:/{empresa}/dashboard/assinatura/editar'        => ['controlador' => [DashboardAssinaturaController::class,     'assinaturaEditarVer'],           'permissao' => $total],
    'PUT:/{empresa}/d/assinatura/editar/{id}'           => ['controlador' => [DashboardAssinaturaController::class,     'atualizar'],                     'permissao' => $total],
    'GET:/{empresa}/d/assinaturas/gerar/{id}'           => ['controlador' => [DashboardAssinaturaController::class,     'criarAssinaturaAsaas'],          'permissao' => $total],
    'POST:/{empresa}/d/assinatura'                      => ['controlador' => [DashboardAssinaturaController::class,     'buscarAssinatura'],              'permissao' => $total],
    'GET:/{empresa}/dashboard/empresa/editar'           => ['controlador' => [DashboardEmpresaController::class,        'empresaEditarVer'],              'permissao' => $todos],
    'PUT:/{empresa}/d/empresa/editar/{id}'              => ['controlador' => [DashboardEmpresaController::class,        'atualizar'],                     'permissao' => $todos],
  ],
];