<?php
namespace app\Roteamento;
use DateTime;
use app\Core\Cache;
use app\Core\SessaoUsuario;
use app\Controllers\PaginaErroController;
use app\Controllers\DashboardEmpresaController;

class Roteador
{
  private $rotas;
  private $empresa;
  private $empresaId;
  private $chaveRota;
  private $paginaErro;
  private $gratisPrazo;
  private $empresaAtivo;
  private $subdominio_2;
  private $testeExpirado;
  private $sessaoUsuario;
  private $assinaturaStatus;
  private $empresaController;
  private $usuarioLogado;
  private $parametroId;
  private $slug;

  public function __construct()
  {
    $this->rotas = require_once 'rotas.php';

    $this->paginaErro = new PaginaErroController();
    $this->empresaController = new DashboardEmpresaController();
  }

  public function rotear()
  {
    $this->empresa = '';
    $this->empresaId = 0;
    $this->chaveRota = '';
    $this->empresaAtivo = 0;
    $this->gratisPrazo = '';
    $this->subdominio_2 = '';
    $this->assinaturaStatus = 0;
    $this->testeExpirado = false;
    $this->usuarioLogado = [];

    $this->recuperarDominioPersonalizado();
    $this->recuperarChaveRota();
    $this->validarAcessoDominioPadrao();
    $this->recuperarEmpresa();
    $this->validarAcessoCentral();
    $this->validarGratisExpirado();
    $this->recuperarSessaoLogado();
    $this->limiteRequisicoes();
    $this->permitirDebugSuporte();
    $this->permitirAcessoSuporte();
    $this->validarAcessoPorNivel();
    $this->validarAcessoDashboard();
    $this->validarCentralTesteExpirado();
    $this->validarAcessoNegado();
    $this->gravarEmpresaIdSessao();
    $this->acessarRota();
  }

  private function recuperarSessaoLogado(): void
  {
    $sessao = Cache::buscar('sessao', $this->empresaId);

    // Sessão cache
    $logadoSessaoId = $sessao['id'] ?? null;
    $logadoSessaoIp = $sessao['ip'] ?? null;
    $logadoNavegador = $sessao['navegador'] ?? null;

    // Nova sessão
    $novoNavegador = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $novoIp = $_SERVER['REMOTE_ADDR'] ?? null;
    $sessaoId = null;

    if (($this->subdominio_2 and $logadoSessaoId) and ($logadoNavegador and $logadoNavegador == $novoNavegador) and ($logadoSessaoIp and $logadoSessaoIp == $novoIp)) {
      $sessaoId = $logadoSessaoId;
    }

    $this->sessaoUsuario = new SessaoUsuario($sessaoId);
    $this->usuarioLogado = $this->sessaoUsuario->buscar('usuario');
    $this->sessaoUsuario->apagar('debug');
  }

  private function permitirAcessoSuporte(): void
  {
    // Acesso de usuário comum
    if ($this->empresaId) {
      return;
    }

    if (! isset($this->usuarioLogado['padrao'])) {
      return;
    }

    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      return;
    }

    // Permite acesso a qualquer empresa
    $this->empresaId = $this->usuarioLogado['empresaId'];

    if (empty($this->empresa) and ! isset($this->rotas['publico'][ $this->chaveRota ])) {
      registrarSentry('Rota não encontrada (sem domínio)', $_SESSION);
      $this->paginaErro->erroVer();
    }
  }

  private function permitirDebugSuporte(): void
  {
    if (! isset($this->usuarioLogado['padrao'])) {
      return;
    }

    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      return;
    }

    if (! isset($_GET['debug'])) {
      return;
    }

    if ($_GET['debug'] == 'true') {
      $this->sessaoUsuario->definir('debugAtivo', true);
    }
    else {
      $this->sessaoUsuario->apagar('debugAtivo');
    }
  }

  private function gravarEmpresaIdSessao(): void
  {
    $this->sessaoUsuario->definir('subdominio', $this->empresa);
    $this->sessaoUsuario->definir('empresaPadraoId', $this->empresaId);
    $this->sessaoUsuario->definir('subdominio_2', $this->subdominio_2);
  }

  private function validarAcessoNegado(): void
  {
    // Sem Empresa ID igual acesso negado, exceto rota pública
    if ($this->empresaId == 0 and ! isset($this->rotas['publico'][ $this->chaveRota ])) {
      registrarSentry('Rota pública não encontrada', $_SESSION);
      $this->paginaErro->erroVer();
    }
  }

  private function acessarRota(): void
  {
    $novoIp = $_SERVER['REMOTE_ADDR'] ?? '';
    $novoNavegador = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (empty($this->subdominio_2) and $novoIp and $novoNavegador) {
      $sessao = Cache::buscar('sessao', $this->empresaId);

      // Troca token somente em novo login
      $sessaoAtualId = $sessao['id'] ?? null;
      $sessaoAtualIp = $sessao['ip'] ?? null;
      $sessaoAtualNavegador = $sessao['navegador'] ?? null;

      if ($sessaoAtualId != session_id() or $sessaoAtualIp != $novoIp or $sessaoAtualNavegador != $novoNavegador) {
        $sessao = [
          'id' => session_id(),
          'navegador' => $novoNavegador,
          'ip' => $novoIp,
        ];

        Cache::definir('sessao', $sessao, 14400, $this->empresaId);
      }
    }

    $sucesso = false;
    foreach ($this->rotas as $chave => $linha):

      // Domínio personalizado acessa apenas central personalizado
      if ($this->subdominio_2 and ($chave != 'centralPersonalizado' or empty($this->empresaId))) {
        continue;
      }

      if (empty($this->subdominio_2) and $chave == 'centralPersonalizado') {
        continue;
      }

      foreach ($linha as $subChave => $subLinha):

        if ($subChave !== $this->chaveRota) {
          continue;
        }

        if (! isset($subLinha['controlador'][0]) or empty($subLinha['controlador'][0])) {
          continue;
        }

        if (! isset($subLinha['controlador'][1]) or empty($subLinha['controlador'][1])) {
          continue;
        }

        $controlNome = 'app\\Controllers\\' . $subLinha['controlador'][0];

        if ($subLinha['controlador'][0] == 'Cache') {
          $controlNome = 'app\\Core\\' . $subLinha['controlador'][0];
        }

        try {
          $controlador = new $controlNome();
          $metodo = $subLinha['controlador'][1];
          $sucesso = true;

          if ($this->parametroId) {
            $controlador->$metodo($this->parametroId);
          }
          else {
            $controlador->$metodo();
          }
        }
        catch (\Exception $e) {
          registrarSentry('Erro ao acessar rota', $_SESSION);
          $this->paginaErro->erroVer();
        }
      endforeach;
    endforeach;

    if ($sucesso == false) {
      registrarSentry('Rota não encontrada', $_SESSION);
      $this->paginaErro->erroVer();
    }
  }

  private function validarCentralTesteExpirado(): void
  {
    if (! isset($this->rotas['central'][ $this->chaveRota ])) {
      return;
    }

    if (empty($this->testeExpirado)) {
      return;
    }

    if (isset($this->usuarioLogado['padrao']) and $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      return;
    }

    // Acesso negado
    $this->empresaId = 0;
  }

  private function validarAcessoDashboard()
  {
    if (strpos($this->chaveRota, '/{empresa}/dashboard') === false and strpos($this->chaveRota, '/{empresa}/d/') === false) {
      return;
    }

    $sucesso = true;

    // Usuário deslogado
    if (! isset($this->usuarioLogado['nivel'])) {
      $sucesso = false;
    }
    elseif (! isset($this->usuarioLogado['id']) or (int) $this->usuarioLogado['id'] == 0) {
      $sucesso = false;
    }
    elseif (! isset($this->usuarioLogado['empresaId']) or empty($this->usuarioLogado['empresaId'])) {
      $sucesso = false;
    }
    elseif ($this->empresaAtivo == INATIVO and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      $this->sessaoUsuario->definir('erro', 'Acesso não autorizado, por favor, entre em contato conosco através do e-mail <span class="font-bold">suporte@360help.com.br</span>');
      $sucesso = false;
    }

    // Usuário deslogado
    if ($sucesso == false) {
      $this->sessaoUsuario->apagar('usuario');
      header('Location: /login');
      exit;
    }

    // Tentativas de senha
    if ($this->sessaoUsuario->buscar('acessoBloqueado-' . $this->usuarioLogado['id'])) {
      $this->sessaoUsuario->definir('erro', 'Acesso bloqueado.');
      $sucesso = false;
    }

    // Limita o acesso à empresa correta
    if ($this->usuarioLogado['empresaId'] !== $this->empresaId) {
      registrarSentry('Tentou acessar outra empresa', $_SESSION);
      $this->paginaErro->erroVer();
    }

    $this->testeExpirado = $this->sessaoUsuario->buscar('teste-expirado-' . $this->empresaId);

    if ($this->testeExpirado and ! isset($this->rotas['dashboardVencida'][ $this->chaveRota ]) and (int) $this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      header('Location: /' . $this->empresa . '/dashboard/assinatura/editar');
      exit;
    }
  }

  private function validarAcessoPorNivel(): void
  {
    $nivelAcesso = $this->usuarioLogado['nivel'] ?? 0;
    $nivelAcesso = (int) $nivelAcesso;

    $padraoAcesso = $this->usuarioLogado['padrao'] ?? 0;
    $padraoAcesso = (int) $padraoAcesso;

    $usuarioLogadoId = $this->usuarioLogado['id'] ?? 0;
    $usuarioLogadoId = (int) $usuarioLogadoId;

    $rotasPermitidasUsuario = [
      'GET:/{empresa}/dashboard/usuario/editar/{id}',
      'PUT:/{empresa}/d/usuario/{id}',
      'DELETE:/{empresa}/d/usuario/foto/{id}'
    ];

    $acessoOk = false;
    foreach ($this->rotas as $chave => $linha):
      foreach ($linha as $subChave => $subLinha):

        if ($subChave != $this->chaveRota) {
          continue;
        }

        // Permite restrito editar o próprio usuário
        if ($usuarioLogadoId and $usuarioLogadoId == $this->parametroId and in_array($this->chaveRota, $rotasPermitidasUsuario)) {
          $acessoOk = true;
          break;
        }

        if (! in_array($nivelAcesso, $subLinha['permissao']['nivel'])) {
          continue;
        }

        if (! in_array($padraoAcesso, $subLinha['permissao']['padrao'])) {
          continue;
        }

        $acessoOk = true;
      endforeach;
    endforeach;

    if ($acessoOk) {
      return;
    }

    if ($usuarioLogadoId) {
      $this->sessaoUsuario->definir('erro', 'Você não tem permissão para realizar esta ação.');
    }

    header('Location: /login');
    exit;
  }

  private function validarGratisExpirado(): void
  {
    if (empty($this->gratisPrazo) or $this->assinaturaStatus == ATIVO) {
      return;
    }

    $dataHoje = new DateTime('now');
    $dataGratis = new DateTime($this->gratisPrazo);

    if ($this->assinaturaStatus == INATIVO and $dataHoje > $dataGratis) {
      $this->testeExpirado = true;
    }
  }

  private function validarAcessoCentral(): void
  {
    if (empty($this->empresa)) {
      return;
    }

    if (empty($this->subdominio_2)) {
      return;
    }

    if (isset($this->rotas['centralPersonalizado'][ $this->chaveRota ])) {
      return;
    }

    registrarSentry('Rota não encontrada (domínio personalizado)', $_SESSION);
    $this->paginaErro->erroVer();
  }

  private function recuperarDominioPersonalizado(): void
  {
    // Domínio personalizado
    $this->subdominio_2 = $_SERVER['SERVER_NAME'];

    $dominiosPadrao = [
      'www.360help.com.br',
      '360help.com.br',
    ];

    if (HOST_LOCAL) {
      $this->chaveRota = str_replace(RAIZ, '/', $this->chaveRota);

      $dominiosPadrao = [
        'localhost',
      ];
    }

    // Acesso via domínio padrão
    if (in_array($this->subdominio_2, $dominiosPadrao)) {
      $this->subdominio_2 = '';
    }
  }

  private function validarAcessoDominioPadrao(): void
  {
    if ($this->subdominio_2) {
      return;
    }

    $rotaExiste = false;
    foreach ($this->rotas as $linha):

      if (isset($linha[ $this->chaveRota ])) {
        $rotaExiste = true;
      }
    endforeach;

    if ($rotaExiste) {
      return;
    }

    registrarSentry('Rota não encontrada', $_SESSION);

    $this->paginaErro->erroVer();
  }

  private function recuperarEmpresa(): void
  {
    // Busca empresa para central e dashboard
    if (empty($this->subdominio_2) and isset($this->rotas['publico'][ $this->chaveRota ])) {
      return;
    }

    $coluna = 'subdominio';
    $valor = $this->empresa;
    $cacheNome = 'roteador-' . $valor;

    if ($this->subdominio_2) {
      $coluna = 'subdominio_2';
      $valor = $_SERVER['REQUEST_SCHEME'] . '://' . $this->subdominio_2;
      $cacheNome = 'roteador-' . $this->subdominio_2;
    }

    // Provisório (Google Search)
    if (! HOST_LOCAL and empty($this->subdominio_2) and $valor == 'padrao') {
      header('Location: /technology');
      exit;
    }

    // Cache 1 hora
    $cacheTempo = 60 * 60;
    $buscarEmpresa = Cache::buscarSemId($cacheNome);

    if ($buscarEmpresa == null) {
      $buscarEmpresa = $this->empresaController->buscarEmpresaSemId($coluna, $valor);

      if ($buscarEmpresa) {
        Cache::definirSemId($cacheNome, $buscarEmpresa, $cacheTempo);
      }
    }

    $this->empresa = $buscarEmpresa[0]['Empresa']['subdominio'] ?? '';
    $this->empresaId = intval($buscarEmpresa[0]['Empresa']['id'] ?? 0);
    $this->empresaAtivo = intval($buscarEmpresa[0]['Empresa']['ativo'] ?? 0);
    $this->assinaturaStatus = intval($buscarEmpresa[0]['Assinatura']['status'] ?? 0);
    $this->gratisPrazo = $buscarEmpresa[0]['Assinatura']['gratis_prazo'] ?? '';

    // Acesso negado
    if ($this->empresaAtivo == INATIVO) {
      $this->empresaId = 0;
    }
  }

  private function recuperarChaveRota()
  {
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($url === '/favicon.ico') {
      http_response_code(204);
      exit;
    }

    $metodo = $_SERVER['REQUEST_METHOD'];
    $metodoOculto = $_POST['_method'] ?? null;

    if ($metodoOculto and in_array(strtoupper($metodoOculto), ['PUT', 'DELETE'])) {
      $metodo = strtoupper($metodoOculto);
    }

    $this->chaveRota = $metodo . ':' . $url;
    $partesRota = explode('/', trim($url, '/'));

    $this->empresa = '';
    $this->slug = '';
    $this->parametroId = 0;

    // Apenas letras ou números entre as barras
    $this->chaveRota = preg_replace_callback(
      '/\/([^\/]*)\//',
      function ($matches) {
          return '/' . preg_replace('/[^a-zA-Z0-9\-]/', '', $matches[1]) . '/';
      },
      $this->chaveRota
    );

    if (count($partesRota) > 1) {
      $this->empresa = $partesRota[0];
      $ultimaParte = end($partesRota);

      if (is_numeric($ultimaParte)) {
        $this->parametroId = (int) $ultimaParte;
      }
      else {
        $penultimaParte = prev($partesRota);

        if (is_numeric($penultimaParte)) {
          $this->parametroId = (int) $penultimaParte;
          $this->slug = $ultimaParte;
        }
      }
    }
    elseif (count($partesRota) === 1) {
      $this->empresa = reset($partesRota);
    }

    // Exemplo: {1} > {id}
    $this->chaveRota = preg_replace('/\b' . preg_quote($this->parametroId, '/') . '\b/', '{id}', $this->chaveRota, 1);

    // Exemplo: {como-configurar} > {slug}
    if ($this->slug) {
      $this->chaveRota = str_replace($this->slug, '{slug}', $this->chaveRota);
    }

    // Exemplo: {padrao} > {empresa}
    if (empty($this->subdominio_2) and ! isset($this->rotas['publico'][$this->chaveRota])) {
      $this->chaveRota = preg_replace('/\b' . preg_quote($this->empresa, '/') . '\b/', '{empresa}', $this->chaveRota, 1);
    }
  }

  private function limiteRequisicoes()
  {
    $limite = 10;
    $segundos = 1;
    $segundosBloqueio = $segundos * 900;

    $tempoAgora = time();
    $requisicoes = $this->sessaoUsuario->buscar('requisicoes');
    $bloqueio = (string) $this->sessaoUsuario->buscar('bloqueioData');

    if (empty($requisicoes)) {
      $requisicoes = [];
    }

    $bloqueioTimestamp = $bloqueio ? strtotime($bloqueio) : 0;

    // Usuário já está bloqueado
    if ($bloqueioTimestamp > $tempoAgora) {
      $erro = 'Limite de requisições excedido, tente novamente mais tarde.';

      $this->sessaoUsuario->definir('erro', $erro);
      $this->paginaErro->erroVer('Too Many Requests', 429);

      $acesso = [
        'url' => $_SERVER['REQUEST_URI'],
        'referer' => $_SERVER['HTTP_REFERER'] ?? '',
        'protocolo' => isset($_SERVER['HTTPS']) ? 'HTTPS' : 'HTTP',
      ];

      registrarSentry($erro, array_merge($acesso, $_SESSION));
      exit;
    }

    $novaLista = [];
    foreach ($requisicoes as $data):

      // Remove requisições antigas
      if (strtotime($data) > ($tempoAgora - $segundos)) {
        $novaLista[] = $data;
      }
    endforeach;

    $this->sessaoUsuario->definir('requisicoes', $novaLista);
    $this->sessaoUsuario->apagar('bloqueioData');

    // Bloqueia usuário por limite
    if (count($novaLista) >= $limite) {
      $novoBloqueio = $tempoAgora + $segundosBloqueio;
      $desbloqueio = (new DateTime())->setTimestamp($novoBloqueio)->format('Y-m-d H:i:s');
      $erro = 'Limite de requisições excedido, tente novamente mais tarde.';

      $acesso = [
        'url' => $_SERVER['REQUEST_URI'],
        'referer' => $_SERVER['HTTP_REFERER'] ?? '',
        'protocolo' => isset($_SERVER['HTTPS']) ? 'HTTPS' : 'HTTP',
      ];

      $this->sessaoUsuario->definir('bloqueioData', $desbloqueio);
      $this->sessaoUsuario->definir('erro', $erro);
      $this->paginaErro->erroVer('Too Many Requests', 429);

      registrarSentry($erro, array_merge($acesso, $_SESSION));
      exit;
    }

    // Armazena a data atual das requisições
    $novaLista[] = (new DateTime())->format('Y-m-d H:i:s');
    $this->sessaoUsuario->definir('requisicoes', $novaLista);
  }
}