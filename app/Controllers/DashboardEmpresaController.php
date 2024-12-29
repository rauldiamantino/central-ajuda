<?php
namespace app\Controllers;

use DateTime;
use app\Core\Cache;
use app\Models\DashboardEmpresaModel;
use app\Controllers\DashboardController;
use app\Controllers\Components\DatabaseFirebaseComponent;

class DashboardEmpresaController extends DashboardController
{
  protected $firebase;
  protected $empresaModel;

  public function __construct()
  {
    parent::__construct();

    $this->firebase = new DatabaseFirebaseComponent();
    $this->empresaModel = new DashboardEmpresaModel($this->usuarioLogado, $this->empresaPadraoId);
  }

  public function empresaEditarVer()
  {
    $condicao[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $this->empresaPadraoId,
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.nome',
      'Empresa.subdominio',
      'Empresa.subdominio_2',
      'Empresa.cnpj',
      'Empresa.telefone',
      'Empresa.logo',
      'Empresa.favicon',
      'Empresa.cor_primaria',
      'Empresa.url_site',
      'Empresa.meta_titulo',
      'Empresa.meta_descricao',
      'Empresa.criado',
      'Empresa.modificado',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->executarConsulta();

    if (isset($empresa['erro']) and $empresa['erro']) {
      $this->redirecionarErro('/dashboard', $empresa['erro']);
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('metaTitulo', 'Editar empresa - 360Help');
    $this->visao->variavel('paginaMenuLateral', 'empresa');
    $this->visao->renderizar('/empresa/index');
  }

  public function buscarEmpresaSemId(string $coluna, $valor = ''): array
  {
    if (empty($valor)) {
      return [];
    }

    return $this->empresaModel->buscarEmpresaSemId($coluna, $valor);
  }

  public function buscarEmpresas(): array
  {
    $condicao[] = [
      'campo' => 'Empresa.ativo',
      'operador' => '=',
      'valor' => ATIVO,
    ];

    $condicao[] = [
      'campo' => 'Assinatura.status',
      'operador' => '=',
      'valor' => ATIVO,
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.subdominio',
      'Empresa.subdominio_2',
    ];

    $condJoin = [
      'tabelaJoin' => 'Assinatura',
      'campoA' => 'Assinatura.empresa_id',
      'campoB' => 'Empresa.id',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->juntar($condJoin, 'INNER')
                                  ->executarConsulta();

    return $empresa;
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();
    $formLogo = false;
    $formFavicon = false;

    if (isset($_FILES)) {
      $firebase = new DatabaseFirebaseComponent();

      foreach ($_FILES as $chave => $linha):
        $extensao = pathinfo($linha['name'], PATHINFO_EXTENSION);

        if ($chave == 'arquivo-logo' and $linha['error'] === UPLOAD_ERR_OK) {
          $params = [
            'nome' => 'logo',
            'imagemAtual' => $json['logo'] ?? '',
          ];

          if ($firebase->adicionarImagem($this->empresaPadraoId, $linha, $params) == false) {
            $this->redirecionarErro('/dashboard/empresa/editar', 'Erro ao fazer upload do logo');
          }

          $formLogo = true;
          $json['logo'] = $this->empresaPadraoId . '/' . $params['nome'] . '.' . $extensao;
        }
        elseif ($chave == 'arquivo-favicon' and $linha['error'] === UPLOAD_ERR_OK) {
          $params = [
            'nome' => 'favicon',
            'imagemAtual' => $json['favicon'] ?? '',
          ];

          if ($firebase->adicionarImagem($this->empresaPadraoId, $linha, $params) == false) {
            $this->redirecionarErro('/dashboard/empresa/editar', 'Erro ao fazer upload do favicon');
          }

          $formFavicon = true;
          $json['favicon'] = $this->empresaPadraoId . '/' . $params['nome'] . '.' . $extensao;
        }
      endforeach;
    }

    // Grava apenas se alterar no Firebase
    if ($formLogo == false and isset($json['logo'])) {
      unset($json['logo']);
    }

    if ($formFavicon == false and isset($json['favicon'])) {
      unset($json['favicon']);
    }

    // Remove sessão com subdomínio antigo
    $subdominio_2 = str_replace('https://', '', $this->usuarioLogado['subdominio_2']);
    $subdominio_2 = str_replace('http://', '', $subdominio_2);
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/empresa/editar', $resultado['erro']);
    }

    if ((! isset($resultado['linhasAfetadas']) or $resultado['linhasAfetadas'] == 0) and $formLogo == false and $formFavicon == false) {
      $this->redirecionar('/dashboard/empresa/editar', 'Nenhuma alteração realizada');
    }

    $condicao[] = [
      'campo' => 'Empresa.id',
      'operador' => '=',
      'valor' => (int) $id,
    ];

    $colunas = [
      'Empresa.ativo',
      'Empresa.subdominio',
      'Empresa.subdominio_2',
      'Empresa.cor_primaria',
      'Empresa.url_site',
    ];

    $empresa = $this->empresaModel->selecionar($colunas)
                                  ->condicao($condicao)
                                  ->executarConsulta();

    if (isset($empresa[0]['Empresa']['ativo'])) {
      $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa']['ativo'];
      $this->usuarioLogado['corPrimaria'] = $empresa[0]['Empresa']['cor_primaria'];
      $this->usuarioLogado['urlSite'] = $empresa[0]['Empresa']['url_site'];
      $this->usuarioLogado['subdominio_2'] = $empresa[0]['Empresa']['subdominio_2'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    Cache::apagar('publico-dados-empresa', $this->usuarioLogado['empresaId']);
    Cache::apagarSemId('roteador-' . mb_strtolower($this->usuarioLogado['subdominio']));
    Cache::apagarSemId('roteador-' . mb_strtolower($subdominio_2));

    $this->redirecionarSucesso('/dashboard/empresa/editar', 'Registro alterado com sucesso');
  }
}