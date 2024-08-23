<?php
namespace app\Controllers;
use app\Models\AjusteModel;

class DashboardAjusteController extends DashboardController
{
  protected $ajusteModel;
  
  public function __construct()
  {
    parent::__construct();

    $this->ajusteModel = new AjusteModel();
  }

  public function ajustesVer()
  {
    $colunas = [
      'Ajuste.id',
      'Ajuste.nome',
      'Ajuste.ativo',
      'Ajuste.empresa_id',
      'Ajuste.criado',
      'Ajuste.modificado',
    ];

    $resultado = $this->ajusteModel->condicao()
                                   ->buscar($colunas);

    if (! isset($resultado[0]['Ajuste.id'])) {
      $resultado = [];
    }

    $this->visao->variavel('ajustes', $resultado);
    $this->visao->variavel('titulo', 'Ajustes');
    $this->visao->renderizar('/ajuste/index');
  }

  public function atualizar()
  {
    $json = $this->receberJson();

    $resultado = $this->ajusteModel->atualizarAjustes($json);

    if (isset($resultado['erro'])) {
      $_SESSION['erro'] = $resultado['erro']['mensagem'] ?? '';

      header('Location: /dashboard/ajustes');
      exit();
    }

    $_SESSION['ok'] = 'Ajuste alterado com sucesso';
    header('Location: /dashboard/ajustes');
    exit();
  }
}