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
$acessoTotal = [
  'padrao' => [ USUARIO_SUPORTE, USUARIO_SISTEMA, USUARIO_COMUM, USUARIO_PADRAO ],
  'nivel' => [ USUARIO_TOTAL ],
];

$acessoTodos = [
  'padrao' => [ USUARIO_SUPORTE, USUARIO_SISTEMA, USUARIO_COMUM,USUARIO_PADRAO ],
  'nivel' => [ USUARIO_TOTAL, USUARIO_RESTRITO ],
];

// Deslogados
$acessoPublico = $acessoTodos;
$acessoPublico['padrao'][] = 0;
$acessoPublico['nivel'][] = 0;

return [
  'publico' => [
    'GET:/'                                          => ['controlador' => [InicioController::class,               'inicioVer'],                   'permissao' => $acessoPublico],
    'GET:/erro'                                      => ['controlador' => [PaginaErroController::class,           'erroVer'],                     'permissao' => $acessoPublico],
    'GET:/login'                                     => ['controlador' => [DashboardLoginController::class,       'loginVer'],                    'permissao' => $acessoPublico],
    'GET:/login/suporte'                             => ['controlador' => [DashboardLoginController::class,       'loginSuporteVer'],             'permissao' => $suporteTotal],
    'GET:/login/suporte/{id}'                        => ['controlador' => [DashboardLoginController::class,       'loginSuporteVer'],             'permissao' => $suporteTotal],
    'POST:/cadastro'                                 => ['controlador' => [DashboardCadastroController::class,    'adicionar'],                   'permissao' => $acessoPublico],
    'GET:/cadastro'                                  => ['controlador' => [DashboardCadastroController::class,    'cadastroVer'],                 'permissao' => $acessoPublico],
    'GET:/cadastro/sucesso'                          => ['controlador' => [DashboardCadastroController::class,    'cadastroSucessoVer'],          'permissao' => $acessoPublico],
    'POST:/login'                                    => ['controlador' => [DashboardLoginController::class,       'login'],                       'permissao' => $acessoPublico],
    'GET:/logout'                                    => ['controlador' => [DashboardLoginController::class,       'logout'],                      'permissao' => $acessoPublico],
    'GET:/cache/limpar'                              => ['controlador' => [Cache::class,                          'resetarCache'],                'permissao' => $acessoTodos],
    'POST:/d/assinaturas/receber'                    => ['controlador' => [AssinaturaReceberComponent::class,     'receberWebhook'],              'permissao' => $acessoTodos],
  ],
  'central' => [
    'GET:/{empresa}'                                 => ['controlador' => [PublicoController::class,              'publicoVer'],                  'permissao' => $acessoPublico],
    'GET:/{empresa}/categoria/{id}'                  => ['controlador' => [PublicoCategoriaController::class,     'categoriaVer'],                'permissao' => $acessoPublico],
    'GET:/{empresa}/artigo/{id}'                     => ['controlador' => [PublicoArtigoController::class,        'artigoVer'],                   'permissao' => $acessoPublico],
    'POST:/{empresa}/buscar'                         => ['controlador' => [PublicoBuscaController::class,         'buscar'],                      'permissao' => $acessoPublico],
    'GET:/{empresa}/buscar'                          => ['controlador' => [PublicoBuscaController::class,         'buscar'],                      'permissao' => $acessoPublico],
  ],
  'dashboard' => [
    'GET:/{empresa}/dashboard/ajustes'               => ['controlador' => [DashboardAjusteController::class,      'ajustesVer'],                  'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/artigos'               => ['controlador' => [DashboardArtigoController::class,      'artigosVer'],                  'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/artigo/editar/{id}'    => ['controlador' => [DashboardArtigoController::class,      'artigoEditarVer'],             'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/conteudo/editar/{id}'  => ['controlador' => [DashboardConteudoController::class,    'conteudoEditarVer'],           'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/conteudo/adicionar'    => ['controlador' => [DashboardConteudoController::class,    'conteudoAdicionarVer'],        'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/categorias'            => ['controlador' => [DashboardCategoriaController::class,   'categoriasVer'],               'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/categoria/editar/{id}' => ['controlador' => [DashboardCategoriaController::class,   'categoriaEditarVer'],          'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/categoria/adicionar'   => ['controlador' => [DashboardCategoriaController::class,   'categoriaAdicionarVer'],       'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/usuarios'              => ['controlador' => [DashboardUsuarioController::class,     'UsuariosVer'],                 'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/usuario/editar/{id}'   => ['controlador' => [DashboardUsuarioController::class,     'usuarioEditarVer'],            'permissao' => $acessoTotal],
    'GET:/{empresa}/dashboard/usuario/adicionar'     => ['controlador' => [DashboardUsuarioController::class,     'usuarioAdicionarVer'],         'permissao' => $acessoTotal],
    'GET:/{empresa}/dashboard/validar_assinatura'    => ['controlador' => [DashboardAssinaturaController::class,  'reprocessarAssinaturaAsaas'],  'permissao' => $suporteTotal],
    'GET:/{empresa}/d/calcular_consumo'              => ['controlador' => [DashboardAssinaturaController::class,  'calcularConsumo'],             'permissao' => $acessoTodos],
    'GET:/{empresa}/d/buscar_cobrancas'              => ['controlador' => [DashboardAssinaturaController::class,  'buscarCobrancas'],             'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/ajustes'                       => ['controlador' => [DashboardAjusteController::class,      'atualizar'],                   'permissao' => $acessoTodos],
    'GET:/{empresa}/d/firebase'                      => ['controlador' => [DatabaseFirebaseComponent::class,      'credenciais'],                 'permissao' => $acessoTodos],
    'POST:/{empresa}/d/apagar-local'                 => ['controlador' => [DatabaseFirebaseComponent::class,      'apagarLocal'],                 'permissao' => $acessoTodos],
    'POST:/{empresa}/d/apagar-artigos-local'         => ['controlador' => [DatabaseFirebaseComponent::class,      'apagarArtigosLocal'],          'permissao' => $acessoTodos],
    'POST:/{empresa}/d/upload-local'                 => ['controlador' => [DatabaseFirebaseComponent::class,      'uploadLocal'],                 'permissao' => $acessoTodos],
    'POST:/{empresa}/d/upload-multiplas-local'       => ['controlador' => [DatabaseFirebaseComponent::class,      'uploadMultiplasLocal'],        'permissao' => $acessoTodos],
    'GET:/{empresa}/d/artigos'                       => ['controlador' => [DashboardArtigoController::class,      'buscar'],                      'permissao' => $acessoTodos],
    'GET:/{empresa}/d/artigo/{id}'                   => ['controlador' => [DashboardArtigoController::class,      'buscar'],                      'permissao' => $acessoTodos],
    'POST:/{empresa}/d/artigo'                       => ['controlador' => [DashboardArtigoController::class,      'adicionar'],                   'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/artigo/{id}'                   => ['controlador' => [DashboardArtigoController::class,      'atualizar'],                   'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/artigo/ordem'                  => ['controlador' => [DashboardArtigoController::class,      'atualizarOrdem'],              'permissao' => $acessoTodos],
    'DELETE:/{empresa}/d/artigo/{id}'                => ['controlador' => [DashboardArtigoController::class,      'apagar'],                      'permissao' => $acessoTodos],
    'GET:/{empresa}/d/conteudos'                     => ['controlador' => [DashboardConteudoController::class,    'buscar'],                      'permissao' => $acessoTodos],
    'GET:/{empresa}/d/conteudos/{id}'                => ['controlador' => [DashboardConteudoController::class,    'buscar'],                      'permissao' => $acessoTodos],
    'POST:/{empresa}/d/conteudo'                     => ['controlador' => [DashboardConteudoController::class,    'adicionar'],                   'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/conteudo/{id}'                 => ['controlador' => [DashboardConteudoController::class,    'atualizar'],                   'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/conteudo/ordem'                => ['controlador' => [DashboardConteudoController::class,    'atualizarOrdem'],              'permissao' => $acessoTodos],
    'DELETE:/{empresa}/d/conteudo/{id}'              => ['controlador' => [DashboardConteudoController::class,    'apagar'],                      'permissao' => $acessoTodos],
    'GET:/{empresa}/d/categorias'                    => ['controlador' => [DashboardCategoriaController::class,   'buscar'],                      'permissao' => $acessoTodos],
    'GET:/d/categoria/{id}'                          => ['controlador' => [DashboardCategoriaController::class,   'buscar'],                      'permissao' => $acessoTodos],
    'POST:/{empresa}/d/categoria'                    => ['controlador' => [DashboardCategoriaController::class,   'adicionar'],                   'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/categoria/{id}'                => ['controlador' => [DashboardCategoriaController::class,   'atualizar'],                   'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/categoria/ordem'               => ['controlador' => [DashboardCategoriaController::class,   'atualizarOrdem'],              'permissao' => $acessoTodos],
    'DELETE:/{empresa}/d/categoria/{id}'             => ['controlador' => [DashboardCategoriaController::class,   'apagar'],                      'permissao' => $acessoTodos],
    'GET:/{empresa}/d/usuarios'                      => ['controlador' => [DashboardUsuarioController::class,     'buscar'],                      'permissao' => $acessoTotal],
    'GET:/{empresa}/d/usuario/{id}'                  => ['controlador' => [DashboardUsuarioController::class,     'buscar'],                      'permissao' => $acessoTotal],
    'POST:/{empresa}/d/usuario'                      => ['controlador' => [DashboardUsuarioController::class,     'adicionar'],                   'permissao' => $acessoTotal],
    'PUT:/{empresa}/d/usuario/{id}'                  => ['controlador' => [DashboardUsuarioController::class,     'atualizar'],                   'permissao' => $acessoTotal],
    'DELETE:/{empresa}/d/usuario/{id}'               => ['controlador' => [DashboardUsuarioController::class,     'apagar'],                      'permissao' => $acessoTotal],
    'GET:/{empresa}/d/usuario/desbloquear/{id}'      => ['controlador' => [DashboardUsuarioController::class,     'desbloquear'],                 'permissao' => $suporteTotal],
  ],
  'dashboardVencida' => [
    'GET:/{empresa}/dashboard'                       => ['controlador' => [DashboardController::class,            'dashboardVer'],                'permissao' => $acessoTodos],
    'GET:/{empresa}/dashboard/assinatura/editar'     => ['controlador' => [DashboardAssinaturaController::class,  'assinaturaEditarVer'],         'permissao' => $acessoTotal],
    'PUT:/{empresa}/d/assinatura/editar/{id}'        => ['controlador' => [DashboardAssinaturaController::class,  'atualizar'],                   'permissao' => $acessoTotal],
    'GET:/{empresa}/d/assinaturas/gerar/{id}'        => ['controlador' => [DashboardAssinaturaController::class,  'criarAssinaturaAsaas'],        'permissao' => $acessoTotal],
    'POST:/{empresa}/d/assinatura'                   => ['controlador' => [DashboardAssinaturaController::class,  'buscarAssinatura'],            'permissao' => $acessoTotal],
    'GET:/{empresa}/dashboard/empresa/editar'        => ['controlador' => [DashboardEmpresaController::class,     'empresaEditarVer'],            'permissao' => $acessoTodos],
    'PUT:/{empresa}/d/empresa/editar/{id}'           => ['controlador' => [DashboardEmpresaController::class,     'atualizar'],                   'permissao' => $acessoTodos],
  ],
];