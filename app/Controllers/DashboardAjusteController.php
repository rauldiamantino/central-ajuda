<?php
namespace app\Controllers;
use app\Core\Cache;
use app\Core\Helper;
use app\Models\DashboardAjusteModel;
use app\Controllers\Components\DatabaseFirebaseComponent;

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
    $resultado = $this->ajusteModel->buscarTodos();

    $this->visao->variavel('ajustes', $resultado);
    $this->visao->variavel('metaTitulo', 'Ajustes');
    $this->visao->variavel('paginaMenuLateral', 'ajustes');
    $this->visao->renderizar('/ajuste/index');
  }

  public function atualizar()
  {
    $json = $this->receberJson();

    // Atualiza foto de início
    if (isset($_FILES['arquivo-foto']) and $_FILES['arquivo-foto']['error'] === UPLOAD_ERR_OK) {
      $firebase = new DatabaseFirebaseComponent();
      $extensao = pathinfo($_FILES['arquivo-foto']['name'], PATHINFO_EXTENSION);

      $params = [
        'nome' => 'in',
        'imagemAtual' => $json['publico_inicio_foto'] ?? '',
      ];

      if ($firebase->adicionarImagem($this->empresaPadraoId, $_FILES['arquivo-foto'], $params) == false) {
        $this->redirecionarErro('/dashboard/ajustes', 'Erro ao fazer upload da foto de início');
      }

      $json['publico_inicio_foto'] = $this->empresaPadraoId . '/' . $params['nome'] . '.' . $extensao;
    }
    else {
      unset($json['publico_inicio_foto']);
    }

    $resultado = $this->ajusteModel->atualizarTodos($json);

    if (isset($resultado['erro'])) {
      $this->redirecionarErro('/dashboard/ajustes', $resultado['erro']);
    }

    if (isset($resultado['linhasAfetadas']) and $resultado['linhasAfetadas'] == 0) {
      $this->redirecionar('/dashboard/ajustes', 'Nenhuma alteração realizada');
    }

    $this->limparCacheTodos(['ajustes_'], $this->empresaPadraoId);

    $this->redirecionarSucesso('/dashboard/ajustes', 'Ajuste alterado com sucesso');
  }

  public function apagarFoto()
  {
    $foto = Helper::ajuste('publico_inicio_foto');

    if (empty($foto)) {
      $this->sessaoUsuario->definir('erro', 'Imagem não encontrada');
      $this->responderJson(['erro' => 'Imagem não encontrada'], 404);
    }

    $firebase = new DatabaseFirebaseComponent();

    // Apaga Firebase
    if ($firebase->apagarImagem($foto) == false) {
      $this->sessaoUsuario->definir('erro', 'Erro ao apagar imagem');
      $this->responderJson(['erro' => 'Erro ao apagar imagem'], 500);
    }

    // Apaga Banco de dados
    $this->ajusteModel->apagarAjuste(['nome' => 'publico_inicio_foto']);

    // Limpa cache de artigos públicos
    $this->limparCacheTodos(['ajustes_'], $this->empresaPadraoId);

    $this->sessaoUsuario->definir('ok', 'Foto removida com sucesso');
    $this->responderJson(['ok' => true]);
  }

  public function buscar(string $nome): array
  {
    if (is_array($nome)) {
      $nome = '';
    }

    $condicao = [
      [
        'campo' => 'Ajuste.nome',
        'operador' => '=',
        'valor' => $nome,
      ],
    ];

    $colunas = [
      'Ajuste.nome',
      'Ajuste.valor',
    ];

    $cacheNome = 'ajustes_' . $nome;
    $cacheTempo = 60 * 30;
    $resultado = Cache::buscar($cacheNome, $this->empresaPadraoId);

    if ($resultado == null) {
      $resultado = $this->ajusteModel->selecionar($colunas)
                                     ->condicao($condicao)
                                     ->limite(1)
                                     ->executarConsulta();

      Cache::definir($cacheNome, $resultado, $cacheTempo, $this->empresaPadraoId);
    }

    if (! is_array($resultado)) {
      $resultado = [];
    }

    return $resultado;
  }
}