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

     header('Location: /dashboard/artigos');
      exit();
    }

    $this->visao->variavel('empresa', reset($empresa));
    $this->visao->variavel('titulo', 'Editar empresa');
    $this->visao->renderizar('/empresa/index');
  }

  public function atualizar(int $id)
  {
    $json = $this->receberJson();
    $resultado = $this->empresaModel->atualizar($json, $id);

    if (isset($resultado['erro'])) { 
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/empresa/editar');
      exit();
    }
    
    $_SESSION['ok'] = 'Registro alterado com sucesso';
    header('Location: /dashboard/empresa/editar');
    exit();
  }
}