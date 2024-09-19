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
    if ($this->buscarUsuarioLogado('nivel') == 2) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/artigos');
      exit;
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
      $_SESSION['erro'] = $empresa['erro']['mensagem'] ?? '';

     header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/artigos');
      exit();
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('titulo', 'Editar empresa');
    $this->visao->renderizar('/empresa/index');
  }

  public function atualizar(int $id)
  {
    if ($this->buscarUsuarioLogado('nivel') == 2) {
      $_SESSION['erro'] = 'Você não tem permissão para realizar esta ação.';
      header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/artigos');
      exit;
    }

    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/empresa/editar');
      exit();
    }

    $_SESSION['ok'] = 'Registro alterado com sucesso';
    header('Location: /' . $this->buscarUsuarioLogado('subdominio') . '/dashboard/empresa/editar');
    exit();
  }
}