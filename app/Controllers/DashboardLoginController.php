<?php

namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardLoginModel;
use app\Models\DashboardEmpresaModel;
use app\Models\DashboardAssinaturaModel;

class DashboardLoginController extends DashboardController
{
  protected $loginModel;
  protected $empresaModel;
  protected $assinaturaModel;
  protected $visao;

  public function __construct()
  {
    parent::__construct();

    $this->loginModel = new DashboardLoginModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->empresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);
    $this->assinaturaModel = new DashboardAssinaturaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function loginVer()
  {
    if ($this->usuarioLogado['id'] > 0) {
      header('Location: /dashboard');
      exit();
    }

    $this->visao->variavel('metaTitulo', 'Login - 360Help');
    $this->visao->variavel('pagLogin', true);
    $this->visao->variavel('paginaMenuLateral', 'login');
    $this->visao->renderizar('/login/index');
  }

  public function loginRedirVer()
  {
    $this->visao->variavel('metaTitulo', 'Login - 360Help');
    $this->visao->variavel('pagLogin', true);
    $this->visao->variavel('paginaMenuLateral', 'login');
    $this->visao->renderizar('/loginRedir/index');
  }

  public function loginRedir()
  {
    $json = $this->receberJson();
    $subdominio = $json['empresa'] ?? '';
    $subdominio = $this->filtrarInjection($subdominio);

    $resultado = $this->empresaModel->buscarEmpresaSemId('subdominio', $subdominio);

    $resultadoSubdominio = $resultado[0]['Empresa']['subdominio'] ?? '';
    $resultadoSubdominio2 = $resultado[0]['Empresa']['subdominio_2'] ?? '';

    if (empty($resultadoSubdominio) and empty($resultadoSubdominio2)) {
      $this->redirecionarErro('/login', 'Empresa não encontrada');
    }

    $redirecionar = 'https://' . $resultadoSubdominio . '.360help.com.br/dashboard/login';

    if (HOST_LOCAL) {
      $redirecionar = 'http://' . $resultadoSubdominio . '.localhost/dashboard/login';
    }

    if ($resultadoSubdominio2) {
      $redirecionar = $resultadoSubdominio2 . '/dashboard/login';
    }

    $this->redirecionar($redirecionar);
  }

  public function loginSuporteVer(int $id = 0)
  {
    if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) {
      header('Location: /dashboard');
      exit();
    }

    $colunas = [
      'Empresa.id',
      'Empresa.subdominio',
      'Empresa.subdominio_2',
      'Empresa.ativo',
      'Empresa.id',
      'Empresa.criado',
      'Assinatura.asaas_id',
      'Assinatura.status',
      'Assinatura.gratis_prazo',
    ];

    $uniaoAssinatura = [
      'tabelaJoin' => 'Assinatura',
      'campoA' => 'Assinatura.empresa_id',
      'campoB' => 'Empresa.id',
    ];

    $empresas = $this->empresaModel->selecionar($colunas)
                                   ->juntar($uniaoAssinatura, 'LEFT')
                                   ->executarConsulta();

    if (isset($empresas['erro'])) {
      $this->sessaoUsuario->apagar('usuario');
      $this->redirecionarErro('/dashboard/login', $empresas['erro']);
    }

    if (! is_array($empresas)) {
      $empresas = [];
    }

    if ($id) {
      // Empresa selecionada
      foreach($empresas as $linha):

        if (! isset($linha['Empresa']['id']) or (int) $linha['Empresa']['id'] == 0) {
          continue;
        }

        if (! isset($linha['Empresa']['subdominio']) or empty($linha['Empresa']['subdominio'])) {
          continue;
        }

        if (! isset($linha['Empresa']['id']) or empty($linha['Empresa']['id'])) {
          continue;
        }

        if (! isset($linha['Empresa']['criado']) or empty($linha['Empresa']['criado'])) {
          continue;
        }

        if (! isset($linha['Assinatura']['asaas_id'])) {
          continue;
        }

        if (! isset($linha['Assinatura']['status'])) {
          continue;
        }

        if ($id != $linha['Empresa']['id']) {
          continue;
        }

        // Sessão antiga
        Cache::apagar('sessao', $this->empresaPadraoId);

        // Aplica empresa na sessão
        $this->usuarioLogado['empresaId'] = $linha['Empresa']['id'];
        $this->usuarioLogado['empresaAtivo'] = $linha['Empresa']['ativo'];
        $this->usuarioLogado['empresaCriado'] = $linha['Empresa']['criado'];
        $this->usuarioLogado['gratisPrazo'] = $linha['Assinatura']['gratis_prazo'] ?? '';
        $this->usuarioLogado['corPrimaria'] = intval($linha['Empresa']['cor_primaria'] ?? 1);
        $this->usuarioLogado['urlSite'] = $linha['Empresa']['url_site'] ?? '';
        $this->usuarioLogado['assinaturaStatus'] = $linha['Assinatura']['status'];
        $this->usuarioLogado['assinaturaIdAsaas'] = $linha['Assinatura']['asaas_id'];
        $this->usuarioLogado['subdominio'] = $linha['Empresa']['subdominio'];
        $this->usuarioLogado['subdominio_2'] = $linha['Empresa']['subdominio_2'] ?? '';
        $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);

        $this->redirecionar('/dashboard');
      endforeach;

      $this->redirecionarErro('/dashboard/login/suporte', 'Empresa não encontrada');
    }

    $this->visao->variavel('metaTitulo', 'Login Suporte - 360Help');
    $this->visao->variavel('pagLoginSuporte', true);
    $this->visao->variavel('empresas', $empresas);
    $this->visao->variavel('paginaMenuLateral', 'login');
    $this->visao->renderizar('/login/suporte/index');
  }

  public function login(array $loginCadastro = [])
  {
    $json = $this->receberJson();
    $baseUrl = '';

    if ($loginCadastro) {
      $json = $loginCadastro;
      $baseUrl = 'https://' . $loginCadastro['subdominio'] . '.360help.com.br';

      if (HOST_LOCAL) {
        $baseUrl = 'http://' . $loginCadastro['subdominio'] . '.localhost';
      }
    }

    $resultado = $this->loginModel->login($json);

    // Acesso será liberado somente via suporte
    if (isset($resultado['bloqueio']) and $resultado['bloqueio']) {
      $this->sessaoUsuario->definir('acessoBloqueado-' . $resultado['bloqueio'], true);
    }

    if (isset($resultado['erro'])) {
      $this->sessaoUsuario->apagar('usuario');
      $this->redirecionarErro($baseUrl . '/dashboard/login', $resultado['erro']);
    }

    $this->usuarioLogado = [
      'id' => $resultado['ok']['id'],
      'nome' => $resultado['ok']['nome'],
      'email' => $resultado['ok']['email'],
      'empresaId' => $resultado['ok']['empresa_id'],
      'empresaAtivo' => $resultado['ok']['Empresa.ativo'],
      'empresaCriado' => $resultado['ok']['Empresa.criado'],
      'gratisPrazo' => $resultado['ok']['Assinatura.gratis_prazo'],
      'corPrimaria' => $resultado['ok']['Empresa.cor_primaria'],
      'urlSite' => $resultado['ok']['Empresa.url_site'],
      'assinaturaIdAsaas' => $resultado['ok']['Assinatura.asaas_id'],
      'assinaturaStatus' => $resultado['ok']['Assinatura.status'],
      'subdominio' => $resultado['ok']['Empresa.subdominio'],
      'subdominio_2' => $resultado['ok']['Empresa.subdominio_2'],
      'nivel' => $resultado['ok']['nivel'],
      'padrao' => $resultado['ok']['padrao'],
      'tentativasLogin' => $resultado['ok']['tentativas_login'],
      'foto' => $resultado['ok']['foto'],
    ];

    $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    $this->sessaoUsuario->regenerarId();

    if ($this->usuarioLogado['empresaId'] == 1 and $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {
      // $this->redirecionar($baseUrl . '/dashboard/login/suporte');
    }

    // Revisar e direcionar para tela intermediaria onde anota os dados de acesso
    if ($loginCadastro) {
      $this->sessaoUsuario->definir('ok', 'Cadastro realizado com sucesso!');
    }

    $this->redirecionar($baseUrl . '/dashboard');
  }

  public function logout()
  {
    $this->sessaoUsuario->apagar('usuario');
    Cache::apagar('sessao', $this->empresaPadraoId);

    header('Location: /');
    exit();
  }
}