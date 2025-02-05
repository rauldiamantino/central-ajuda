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
    'GET:/login'                                        => ['controlador' => [DashboardLoginController::class,          'loginRedirVer'],                 'permissao' => $publico],
    'POST:/login/redir'                                 => ['controlador' => [DashboardLoginController::class,          'loginRedir'],                    'permissao' => $publico],
    'GET:/privacidade'                                  => ['controlador' => [InicioController::class,                  'privacidadeVer'],                'permissao' => $publico],
    'GET:/termos'                                       => ['controlador' => [InicioController::class,                  'termosVer'],                     'permissao' => $publico],
    'GET:/erro'                                         => ['controlador' => [PaginaErroController::class,              'erroVer'],                       'permissao' => $publico],
    'POST:/cadastro'                                    => ['controlador' => [DashboardCadastroController::class,       'adicionar'],                     'permissao' => $publico],
    'GET:/cadastro'                                     => ['controlador' => [DashboardCadastroController::class,       'cadastroVer'],                   'permissao' => $publico],
    'POST:/d/assinaturas/receber'                       => ['controlador' => [AssinaturaReceberComponent::class,        'receberWebhook'],                'permissao' => $todos],
    'GET:/robots.txt'                                   => ['controlador' => [SEOController::class,                     'robotsGeral'],                   'permissao' => $publico],
    'GET:/sitemap.xml'                                  => ['controlador' => [SEOController::class,                     'sitemapGeral'],                  'permissao' => $publico],
  ],
  'central' => [
    'GET:/'                                             => ['controlador' => [PublicoController::class,                 'publicoVer'],                    'permissao' => $publico],
    'GET:/categoria/{id}/{slug}'                        => ['controlador' => [PublicoCategoriaController::class,        'categoriaVer'],                  'permissao' => $publico],
    'GET:/artigo/{id}/{slug}'                           => ['controlador' => [PublicoArtigoController::class,           'artigoVer'],                     'permissao' => $publico],
    'GET:/categoria/{id}'                               => ['controlador' => [PublicoCategoriaController::class,        'categoriaVer'],                  'permissao' => $publico],
    'GET:/artigo/{id}'                                  => ['controlador' => [PublicoArtigoController::class,           'artigoVer'],                     'permissao' => $publico],
    'POST:/buscar'                                      => ['controlador' => [PublicoBuscaController::class,            'buscar'],                        'permissao' => $publico],
    'GET:/buscar'                                       => ['controlador' => [PublicoBuscaController::class,            'buscar'],                        'permissao' => $publico],
    'GET:/robots.txt'                                   => ['controlador' => [SEOController::class,                     'robotsEmpresa'],                 'permissao' => $publico],
    'GET:/sitemap.xml'                                  => ['controlador' => [SEOController::class,                     'sitemapEmpresa'],                'permissao' => $publico],
    'POST:/feedback'                                    => ['controlador' => [DashboardFeedbackController::class,       'adicionarAtualizar'],            'permissao' => $publico],
  ],
  'dashboardLogin' => [
    'GET:/dashboard/login'                              => ['controlador' => [DashboardLoginController::class,          'loginVer'],                      'permissao' => $publico],
    'GET:/dashboard/login/suporte'                      => ['controlador' => [DashboardLoginController::class,          'loginSuporteVer'],               'permissao' => $suporteTotal],
    'GET:/dashboard/login/suporte/{id}'                 => ['controlador' => [DashboardLoginController::class,          'loginSuporteVer'],               'permissao' => $suporteTotal],
    'POST:/dashboard/login'                             => ['controlador' => [DashboardLoginController::class,          'login'],                         'permissao' => $publico],
    'GET:/dashboard/logout'                             => ['controlador' => [DashboardLoginController::class,          'logout'],                        'permissao' => $publico],
  ],
  'dashboard' => [
    'GET:/dashboard'                                    => ['controlador' => [DashboardInicioController::class,         'dashboardVer'],                  'permissao' => $todos],
    'GET:/dashboard/cache/limpar/{id}'                  => ['controlador' => [Cache::class,                             'resetarCacheEmpresa'],           'permissao' => $suporteTodos],
    'GET:/dashboard/cache/limpar/roteador'              => ['controlador' => [Cache::class,                             'resetarCacheSemId'],             'permissao' => $suporteTodos],
    'GET:/dashboard/cache/limpar/todos'                 => ['controlador' => [Cache::class,                             'resetarCacheTodos'],             'permissao' => $suporteTodos],
    'GET:/dashboard/ajustes'                            => ['controlador' => [DashboardAjusteController::class,         'ajustesVer'],                    'permissao' => $todos],
    'GET:/dashboard/artigos'                            => ['controlador' => [DashboardArtigoController::class,         'artigosVer'],                    'permissao' => $todos],
    'GET:/dashboard/artigo/editar/{id}'                 => ['controlador' => [DashboardArtigoController::class,         'artigoEditarVer'],               'permissao' => $todos],
    'GET:/dashboard/conteudo/editar/{id}'               => ['controlador' => [DashboardConteudoController::class,       'conteudoEditarVer'],             'permissao' => $todos],
    'GET:/dashboard/conteudo/adicionar'                 => ['controlador' => [DashboardConteudoController::class,       'conteudoAdicionarVer'],          'permissao' => $todos],
    'GET:/dashboard/categorias'                         => ['controlador' => [DashboardCategoriaController::class,      'categoriasVer'],                 'permissao' => $todos],
    'GET:/dashboard/categoria/editar/{id}'              => ['controlador' => [DashboardCategoriaController::class,      'categoriaEditarVer'],            'permissao' => $todos],
    'GET:/dashboard/categoria/adicionar'                => ['controlador' => [DashboardCategoriaController::class,      'categoriaAdicionarVer'],         'permissao' => $todos],
    'GET:/dashboard/usuarios'                           => ['controlador' => [DashboardUsuarioController::class,        'UsuariosVer'],                   'permissao' => $todos],
    'GET:/dashboard/usuario/editar/{id}'                => ['controlador' => [DashboardUsuarioController::class,        'usuarioEditarVer'],              'permissao' => $total],
    'GET:/dashboard/usuario/adicionar'                  => ['controlador' => [DashboardUsuarioController::class,        'usuarioAdicionarVer'],           'permissao' => $total],
    'GET:/dashboard/validar_assinatura'                 => ['controlador' => [DashboardAssinaturaController::class,     'reprocessarAssinaturaAsaas'],    'permissao' => $suporteTotal],
    'GET:/dashboard/relatorios'                         => ['controlador' => [DashboardRelatorioController::class,      'relatoriosVer'],                 'permissao' => $todos],
    'GET:/dashboard/relatorios/feedbacks'               => ['controlador' => [DashboardRelatorioController::class,      'feedbacks'],                     'permissao' => $todos],
    'GET:/dashboard/relatorios/visualizacoes'           => ['controlador' => [DashboardRelatorioController::class,      'visualizacoes'],                 'permissao' => $todos],
    'GET:/d/calcular_consumo'                           => ['controlador' => [DashboardAssinaturaController::class,     'calcularConsumo'],               'permissao' => $todos],
    'GET:/d/buscar_cobrancas'                           => ['controlador' => [DashboardAssinaturaController::class,     'buscarCobrancas'],               'permissao' => $todos],
    'PUT:/d/ajustes'                                    => ['controlador' => [DashboardAjusteController::class,         'atualizar'],                     'permissao' => $todos],
    'DELETE:/d/ajustes/foto_inicio'                     => ['controlador' => [DashboardAjusteController::class,         'apagarFoto'],                    'permissao' => $total],
    'GET:/d/firebase'                                   => ['controlador' => [DatabaseFirebaseComponent::class,         'credenciais'],                   'permissao' => $todos],
    'POST:/d/apagar-local'                              => ['controlador' => [DatabaseFirebaseComponent::class,         'apagarLocal'],                   'permissao' => $todos],
    'POST:/d/apagar-artigos-local'                      => ['controlador' => [DatabaseFirebaseComponent::class,         'apagarArtigosLocal'],            'permissao' => $todos],
    'POST:/d/upload-local'                              => ['controlador' => [DatabaseFirebaseComponent::class,         'uploadLocal'],                   'permissao' => $todos],
    'POST:/d/upload-multiplas-local'                    => ['controlador' => [DatabaseFirebaseComponent::class,         'uploadMultiplasLocal'],          'permissao' => $todos],
    'GET:/d/artigos'                                    => ['controlador' => [DashboardArtigoController::class,         'buscar'],                        'permissao' => $todos],
    'GET:/d/artigo/{id}'                                => ['controlador' => [DashboardArtigoController::class,         'buscar'],                        'permissao' => $todos],
    'POST:/d/artigo'                                    => ['controlador' => [DashboardArtigoController::class,         'adicionar'],                     'permissao' => $todos],
    'PUT:/d/artigo/{id}'                                => ['controlador' => [DashboardArtigoController::class,         'atualizar'],                     'permissao' => $todos],
    'PUT:/d/artigo/ordem'                               => ['controlador' => [DashboardArtigoController::class,         'atualizarOrdem'],                'permissao' => $todos],
    'DELETE:/d/artigo/{id}'                             => ['controlador' => [DashboardArtigoController::class,         'apagar'],                        'permissao' => $todos],
    'GET:/d/conteudos'                                  => ['controlador' => [DashboardConteudoController::class,       'buscar'],                        'permissao' => $todos],
    'GET:/d/conteudos/{id}'                             => ['controlador' => [DashboardConteudoController::class,       'buscar'],                        'permissao' => $todos],
    'POST:/d/conteudo'                                  => ['controlador' => [DashboardConteudoController::class,       'adicionar'],                     'permissao' => $todos],
    'PUT:/d/conteudo/{id}'                              => ['controlador' => [DashboardConteudoController::class,       'atualizar'],                     'permissao' => $todos],
    'PUT:/d/conteudo/ordem'                             => ['controlador' => [DashboardConteudoController::class,       'atualizarOrdem'],                'permissao' => $todos],
    'DELETE:/d/conteudo/{id}'                           => ['controlador' => [DashboardConteudoController::class,       'apagar'],                        'permissao' => $todos],
    'GET:/d/categorias'                                 => ['controlador' => [DashboardCategoriaController::class,      'buscar'],                        'permissao' => $todos],
    'GET:/d/categoria/{id}'                             => ['controlador' => [DashboardCategoriaController::class,      'buscar'],                        'permissao' => $todos],
    'POST:/d/categoria'                                 => ['controlador' => [DashboardCategoriaController::class,      'adicionar'],                     'permissao' => $todos],
    'PUT:/d/categoria/{id}'                             => ['controlador' => [DashboardCategoriaController::class,      'atualizar'],                     'permissao' => $todos],
    'PUT:/d/categoria/ordem'                            => ['controlador' => [DashboardCategoriaController::class,      'atualizarOrdem'],                'permissao' => $todos],
    'DELETE:/d/categoria/{id}'                          => ['controlador' => [DashboardCategoriaController::class,      'apagar'],                        'permissao' => $todos],
    'GET:/d/usuarios'                                   => ['controlador' => [DashboardUsuarioController::class,        'buscar'],                        'permissao' => $total],
    'GET:/d/usuario/{id}'                               => ['controlador' => [DashboardUsuarioController::class,        'buscar'],                        'permissao' => $total],
    'POST:/d/usuario'                                   => ['controlador' => [DashboardUsuarioController::class,        'adicionar'],                     'permissao' => $total],
    'PUT:/d/usuario/{id}'                               => ['controlador' => [DashboardUsuarioController::class,        'atualizar'],                     'permissao' => $total],
    'DELETE:/d/usuario/{id}'                            => ['controlador' => [DashboardUsuarioController::class,        'apagar'],                        'permissao' => $total],
    'DELETE:/d/usuario/foto/{id}'                       => ['controlador' => [DashboardUsuarioController::class,        'apagarFoto'],                    'permissao' => $total],
    'GET:/d/usuario/desbloquear/{id}'                   => ['controlador' => [DashboardUsuarioController::class,        'desbloquear'],                   'permissao' => $suporteTotal],
  ],
  'dashboardVencida' => [
    'GET:/dashboard'                                    => ['controlador' => [DashboardInicioController::class,         'dashboardVer'],                  'permissao' => $todos],
    'GET:/dashboard/assinatura/editar'                  => ['controlador' => [DashboardAssinaturaController::class,     'assinaturaEditarVer'],           'permissao' => $total],
    'PUT:/d/assinatura/editar/{id}'                     => ['controlador' => [DashboardAssinaturaController::class,     'atualizar'],                     'permissao' => $total],
    'GET:/d/assinaturas/gerar/{id}'                     => ['controlador' => [DashboardAssinaturaController::class,     'criarAssinaturaAsaas'],          'permissao' => $total],
    'POST:/d/assinatura'                                => ['controlador' => [DashboardAssinaturaController::class,     'buscarAssinatura'],              'permissao' => $total],
    'GET:/d/calcular_consumo'                           => ['controlador' => [DashboardAssinaturaController::class,     'calcularConsumo'],               'permissao' => $todos],
    'GET:/dashboard/empresa/editar'                     => ['controlador' => [DashboardEmpresaController::class,        'empresaEditarVer'],              'permissao' => $todos],
    'PUT:/d/empresa/editar/{id}'                        => ['controlador' => [DashboardEmpresaController::class,        'atualizar'],                     'permissao' => $todos],
  ],
];