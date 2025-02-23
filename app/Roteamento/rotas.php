<?php
// Suporte
$suporteTotal = [
  'padrao' => [ USUARIO_SUPORTE ],
  'nivel' => [ USUARIO_TOTAL ],
];

$suporteTodos = [
  'padrao' => [ USUARIO_SUPORTE ],
  'nivel' => [ USUARIO_TOTAL, USUARIO_LEITURA ],
];

// Logados
$logadoTotal = [
  'padrao' => [ USUARIO_SUPORTE, USUARIO_SISTEMA, USUARIO_COMUM, USUARIO_PADRAO ],
  'nivel' => [ USUARIO_TOTAL ],
];

$logadoTodos = [
  'padrao' => [ USUARIO_SUPORTE, USUARIO_SISTEMA, USUARIO_COMUM,USUARIO_PADRAO ],
  'nivel' => [ USUARIO_TOTAL, USUARIO_LEITURA ],
];

// Deslogados
$publico = $logadoTodos;
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
    'POST:/d/assinaturas/receber'                       => ['controlador' => [AssinaturaReceberComponent::class,        'receberWebhook'],                'permissao' => $logadoTodos],
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
    'GET:/dashboard'                                    => ['controlador' => [DashboardInicioController::class,         'dashboardVer'],                  'permissao' => $logadoTodos],
    'GET:/dashboard/cache/limpar/{id}'                  => ['controlador' => [Cache::class,                             'resetarCacheEmpresa'],           'permissao' => $suporteTodos],
    'GET:/dashboard/cache/limpar/roteador'              => ['controlador' => [Cache::class,                             'resetarCacheSemId'],             'permissao' => $suporteTodos],
    'GET:/dashboard/cache/limpar/todos'                 => ['controlador' => [Cache::class,                             'resetarCacheTodos'],             'permissao' => $suporteTodos],
    'GET:/dashboard/ajustes'                            => ['controlador' => [DashboardAjusteController::class,         'ajustesVer'],                    'permissao' => $logadoTotal],
    'GET:/dashboard/artigos'                            => ['controlador' => [DashboardArtigoController::class,         'artigosVer'],                    'permissao' => $logadoTodos],
    'GET:/dashboard/artigo/editar/{id}'                 => ['controlador' => [DashboardArtigoController::class,         'artigoEditarVer'],               'permissao' => $logadoTodos],
    'GET:/dashboard/conteudo/editar/{id}'               => ['controlador' => [DashboardConteudoController::class,       'conteudoEditarVer'],             'permissao' => $logadoTodos],
    'GET:/dashboard/conteudo/adicionar'                 => ['controlador' => [DashboardConteudoController::class,       'conteudoAdicionarVer'],          'permissao' => $logadoTodos],
    'GET:/dashboard/categorias'                         => ['controlador' => [DashboardCategoriaController::class,      'categoriasVer'],                 'permissao' => $logadoTodos],
    'GET:/dashboard/categoria/editar/{id}'              => ['controlador' => [DashboardCategoriaController::class,      'categoriaEditarVer'],            'permissao' => $logadoTodos],
    'GET:/dashboard/categoria/adicionar'                => ['controlador' => [DashboardCategoriaController::class,      'categoriaAdicionarVer'],         'permissao' => $logadoTodos],
    'GET:/dashboard/usuarios'                           => ['controlador' => [DashboardUsuarioController::class,        'UsuariosVer'],                   'permissao' => $logadoTodos],
    'GET:/dashboard/usuario/editar/{id}'                => ['controlador' => [DashboardUsuarioController::class,        'usuarioEditarVer'],              'permissao' => $logadoTotal],
    'GET:/dashboard/usuario/adicionar'                  => ['controlador' => [DashboardUsuarioController::class,        'usuarioAdicionarVer'],           'permissao' => $logadoTotal],
    'GET:/dashboard/validar_assinatura'                 => ['controlador' => [DashboardAssinaturaController::class,     'reprocessarAssinaturaAsaas'],    'permissao' => $suporteTotal],
    'GET:/dashboard/relatorios'                         => ['controlador' => [DashboardRelatorioController::class,      'relatoriosVer'],                 'permissao' => $logadoTotal],
    'GET:/dashboard/relatorios/feedbacks'               => ['controlador' => [DashboardRelatorioController::class,      'feedbacks'],                     'permissao' => $logadoTotal],
    'GET:/dashboard/relatorios/visualizacoes'           => ['controlador' => [DashboardRelatorioController::class,      'visualizacoes'],                 'permissao' => $logadoTotal],
    'GET:/d/calcular_consumo'                           => ['controlador' => [DashboardAssinaturaController::class,     'calcularConsumo'],               'permissao' => $logadoTodos],
    'GET:/d/buscar_cobrancas'                           => ['controlador' => [DashboardAssinaturaController::class,     'buscarCobrancas'],               'permissao' => $logadoTodos],
    'PUT:/d/ajustes'                                    => ['controlador' => [DashboardAjusteController::class,         'atualizar'],                     'permissao' => $logadoTodos],
    'DELETE:/d/ajustes/foto_inicio'                     => ['controlador' => [DashboardAjusteController::class,         'apagarFoto'],                    'permissao' => $logadoTotal],
    'GET:/d/firebase'                                   => ['controlador' => [DatabaseFirebaseComponent::class,         'credenciais'],                   'permissao' => $logadoTodos],
    'POST:/d/apagar-local'                              => ['controlador' => [DatabaseFirebaseComponent::class,         'apagarLocal'],                   'permissao' => $logadoTodos],
    'POST:/d/apagar-artigos-local'                      => ['controlador' => [DatabaseFirebaseComponent::class,         'apagarArtigosLocal'],            'permissao' => $logadoTodos],
    'POST:/d/upload-local'                              => ['controlador' => [DatabaseFirebaseComponent::class,         'uploadLocal'],                   'permissao' => $logadoTodos],
    'POST:/d/upload-multiplas-local'                    => ['controlador' => [DatabaseFirebaseComponent::class,         'uploadMultiplasLocal'],          'permissao' => $logadoTodos],
    'GET:/d/artigos'                                    => ['controlador' => [DashboardArtigoController::class,         'buscar'],                        'permissao' => $logadoTodos],
    'GET:/d/artigo/{id}'                                => ['controlador' => [DashboardArtigoController::class,         'buscar'],                        'permissao' => $logadoTodos],
    'POST:/d/artigo'                                    => ['controlador' => [DashboardArtigoController::class,         'adicionar'],                     'permissao' => $logadoTodos],
    'PUT:/d/artigo/{id}'                                => ['controlador' => [DashboardArtigoController::class,         'atualizar'],                     'permissao' => $logadoTodos],
    'PUT:/d/artigo/ordem'                               => ['controlador' => [DashboardArtigoController::class,         'atualizarOrdem'],                'permissao' => $logadoTodos],
    'PUT:/d/artigo/editar'                              => ['controlador' => [DashboardArtigoController::class,         'atualizarEditar'],               'permissao' => $logadoTodos],
    'DELETE:/d/artigo/{id}'                             => ['controlador' => [DashboardArtigoController::class,         'apagar'],                        'permissao' => $logadoTodos],
    'GET:/d/conteudos'                                  => ['controlador' => [DashboardConteudoController::class,       'buscar'],                        'permissao' => $logadoTodos],
    'GET:/d/conteudos/{id}'                             => ['controlador' => [DashboardConteudoController::class,       'buscar'],                        'permissao' => $logadoTodos],
    'POST:/d/conteudo'                                  => ['controlador' => [DashboardConteudoController::class,       'adicionar'],                     'permissao' => $logadoTodos],
    'PUT:/d/conteudo/{id}'                              => ['controlador' => [DashboardConteudoController::class,       'atualizar'],                     'permissao' => $logadoTodos],
    'PUT:/d/conteudo/ordem'                             => ['controlador' => [DashboardConteudoController::class,       'atualizarOrdem'],                'permissao' => $logadoTodos],
    'DELETE:/d/conteudo/{id}'                           => ['controlador' => [DashboardConteudoController::class,       'apagar'],                        'permissao' => $logadoTodos],
    'GET:/d/categorias'                                 => ['controlador' => [DashboardCategoriaController::class,      'buscar'],                        'permissao' => $logadoTodos],
    'GET:/d/categoria/{id}'                             => ['controlador' => [DashboardCategoriaController::class,      'buscar'],                        'permissao' => $logadoTodos],
    'POST:/d/categoria'                                 => ['controlador' => [DashboardCategoriaController::class,      'adicionar'],                     'permissao' => $logadoTodos],
    'PUT:/d/categoria/{id}'                             => ['controlador' => [DashboardCategoriaController::class,      'atualizar'],                     'permissao' => $logadoTodos],
    'PUT:/d/categoria/ordem'                            => ['controlador' => [DashboardCategoriaController::class,      'atualizarOrdem'],                'permissao' => $logadoTodos],
    'DELETE:/d/categoria/{id}'                          => ['controlador' => [DashboardCategoriaController::class,      'apagar'],                        'permissao' => $logadoTodos],
    'GET:/d/usuarios'                                   => ['controlador' => [DashboardUsuarioController::class,        'buscar'],                        'permissao' => $logadoTotal],
    'GET:/d/usuario/{id}'                               => ['controlador' => [DashboardUsuarioController::class,        'buscar'],                        'permissao' => $logadoTotal],
    'POST:/d/usuario'                                   => ['controlador' => [DashboardUsuarioController::class,        'adicionar'],                     'permissao' => $logadoTotal],
    'PUT:/d/usuario/{id}'                               => ['controlador' => [DashboardUsuarioController::class,        'atualizar'],                     'permissao' => $logadoTotal],
    'DELETE:/d/usuario/{id}'                            => ['controlador' => [DashboardUsuarioController::class,        'apagar'],                        'permissao' => $logadoTotal],
    'DELETE:/d/usuario/foto/{id}'                       => ['controlador' => [DashboardUsuarioController::class,        'apagarFoto'],                    'permissao' => $logadoTotal],
    'GET:/d/usuario/desbloquear/{id}'                   => ['controlador' => [DashboardUsuarioController::class,        'desbloquear'],                   'permissao' => $suporteTotal],
  ],
  'dashboardVencida' => [
    'GET:/dashboard'                                    => ['controlador' => [DashboardInicioController::class,         'dashboardVer'],                  'permissao' => $logadoTodos],
    'GET:/dashboard/assinatura/editar'                  => ['controlador' => [DashboardAssinaturaController::class,     'assinaturaEditarVer'],           'permissao' => $logadoTotal],
    'PUT:/d/assinatura/editar/{id}'                     => ['controlador' => [DashboardAssinaturaController::class,     'atualizar'],                     'permissao' => $logadoTotal],
    'GET:/d/assinaturas/gerar/{id}'                     => ['controlador' => [DashboardAssinaturaController::class,     'criarAssinaturaAsaas'],          'permissao' => $logadoTotal],
    'POST:/d/assinatura'                                => ['controlador' => [DashboardAssinaturaController::class,     'buscarAssinatura'],              'permissao' => $logadoTotal],
    'GET:/d/calcular_consumo'                           => ['controlador' => [DashboardAssinaturaController::class,     'calcularConsumo'],               'permissao' => $logadoTodos],
    'GET:/dashboard/empresa/editar'                     => ['controlador' => [DashboardEmpresaController::class,        'empresaEditarVer'],              'permissao' => $logadoTotal],
    'PUT:/d/empresa/editar/{id}'                        => ['controlador' => [DashboardEmpresaController::class,        'atualizar'],                     'permissao' => $logadoTodos],
  ],
];