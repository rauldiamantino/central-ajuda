<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Models\DashboardAjusteModel;

class DashboardAjusteController extends DashboardController
{
  protected $ajusteModel;

  public function __construct()
  {
    parent::__construct();

    $this->ajusteModel = new DashboardAjusteModel($this->usuarioLogado, $this->empresaPadraoId);
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
    $this->visao->variavel('paginaMenuLateral', 'ajustes');
    $this->visao->renderizar('/ajuste/index');
  }

  public function atualizar()
  {
    $json = $this->receberJson();

    $resultado = $this->ajusteModel->atualizarAjustes($json);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/' . $this->usuarioLogado['empresaId'] . '/ajustes', $resultado['erro']);
    }

    Cache::apagar('ajustes', $this->usuarioLogado['empresaId']);

    $this->redirecionarSucesso('/dashboard/' . $this->usuarioLogado['empresaId'] . '/ajustes', 'Ajuste alterado com sucesso');
  }
}