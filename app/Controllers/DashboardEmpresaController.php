<?php
namespace app\Controllers;
use app\Models\DashboardEmpresaModel;

class DashboardEmpresaController extends DashboardController
{
  protected $empresaModel;

  public function __construct()
  {
    parent::__construct();

    $this->empresaModel = new DashboardEmpresaModel();
  }

  public function empresaEditarVer()
  {
    if ($this->usuarioLogado['nivel'] == 2) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.nome',
      'Empresa.subdominio',
      'Empresa.cnpj',
      'Empresa.telefone',
      'Empresa.logo',
      'Empresa.criado',
      'Empresa.modificado',
    ];

    $empresa = $this->empresaModel->buscar($colunas);

    if (isset($empresa['erro']) and $empresa['erro']) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', $empresa['erro']);
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('titulo', 'Editar empresa');
    $this->visao->renderizar('/empresa/index');
  }

  public function atualizar(int $id)
  {
    if ($this->usuarioLogado['nivel'] == 2) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos', 'Você não tem permissão para realizar esta ação.');
    }

    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', $resultado['erro']);
    }


    $colunas = [
      'Empresa.ativo',
    ];

    $empresa = $this->empresaModel->buscar($colunas);

    if (isset($empresa[0]['Empresa.ativo'])) {
      $this->usuarioLogado['empresaAtivo'] = $empresa[0]['Empresa.ativo'];
      $this->sessaoUsuario->definir('usuario', $this->usuarioLogado);
    }

    $this->redirecionarSucesso('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar', 'Registro alterado com sucesso');
  }

  public function buscarEmpresa(string $subdominio = ''): array
  {
    return $this->empresaModel->buscarEmpresa($subdominio);
  }
}