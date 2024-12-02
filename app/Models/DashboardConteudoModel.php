<?php
namespace app\Models;
use app\Models\Model;

class DashboardConteudoModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Conteudo');
  }

  // --- CRUD ---
  public function adicionar(array $params = []): array
  {
    if ($this->sessaoUsuario->buscar('bloqueio-espaco-' . $this->empresaPadraoId)) {
      $msgErro = [
        'erro' => [
          'codigo' => 409,
          'mensagem' => 'Não foi possível adicionar, pois o limite de armazenamento foi atingido',
        ],
      ];

      return $msgErro;
    }

    $campos = $this->validarCampos($params);

    if (isset($campos['erro'])) {
      return $campos;
    }

    return parent::adicionar($campos);
  }

  public function atualizar(array $params, int $id): array
  {
    if (! is_int($id) or empty($id)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'ID não informado',
        ],
      ];

      return $msgErro;
    }

    $atualizar = true;
    $campos = $this->validarCampos($params, $atualizar);

    if (isset($campos['erro'])) {
      return $campos;
    }

    return parent::atualizar($campos, $id);
  }

  public function atualizarOrdem(array $params): array
  {
    $ids = [];
    $ordens = [];
    $cases = [];
    foreach ($params as $linha) {
      $id = intval($linha['id'] ?? 0);
      $ordem = intval($linha['ordem'] ?? 0);

      if (in_array($id, $ids)) {
        return ['erro' => 'IDs duplicados encontrados.'];
      }

      if (in_array($ordem, $ordens)) {
        return ['erro' => 'Ordens duplicadas encontradas.'];
      }

      $ids[] = $id;
      $ordens[] = $ordem;
      $cases[] = "WHEN $id THEN $ordem";
    }

    if (empty($ids) or empty($ordens) or empty($cases)) {
      return ['erro' => 'Não foi possível processar a requisição'];
    }

    $sql = 'UPDATE conteudos SET ordem = CASE id ' . implode(' ', $cases) . ' END WHERE id IN (' . implode(', ', $ids) . ')';

    return parent::executarQuery($sql);
  }

  public function apagar(int $id): array
  {
    if (! is_int($id) or empty($id)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'ID não informado',
        ],
      ];

      return $msgErro;
    }

    return parent::apagar($id);
  }

  // --- Métodos auxiliares
  private function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'ativo' => intval($params['ativo'] ?? 0),
      'artigo_id' => intval($params['artigo_id'] ?? 0),
      'empresa_id' => $this->empresaPadraoId,
      'tipo' => intval($params['tipo'] ?? 0),
      'titulo' => $params['titulo'] ?? '',
      'titulo_ocultar' => intval($params['titulo_ocultar'] ?? 0),
      'conteudo' => $params['conteudo'] ?? '',
      'url' => $params['url'] ?? '',
      'ordem' => intval($params['ordem'] ?? 0),
    ];

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => [],
      ],
    ];

    $permitidos = [
      'ativo',
      'conteudo',
      'titulo',
      'titulo_ocultar',
    ];

    foreach ($params as $chave => $linha) :

      if ($chave == 'tipo' and in_array($linha, [1, 2])) {
        array_push($permitidos, 'url');
        break;
      }
    endforeach;

    // Campos vazios
    foreach ($campos as $chave => $linha):

      if ($atualizar and ! isset($params[ $chave ])) {

        // Sempre precisa do ID da empresa
        if ($chave != 'empresa_id') {
          continue;
        }
      }

      if ($linha == 'undefined') {
        $linha = '';
      }

      if (! in_array($chave, $permitidos) and empty($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'vazio');
      }
    endforeach;

    // Previne injection via array
    foreach ($campos as $chave => $linha):

      if (is_array($linha)) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro($chave, 'invalido');
      }
    endforeach;

    if (empty($msgErro['erro']['mensagem'])) {
      $campos['ativo'] = (int) $campos['ativo'];
      $campos['artigo_id'] = (int) $campos['artigo_id'];
      $campos['empresa_id'] = (int) $campos['empresa_id'];
      $campos['tipo'] = (int) $campos['tipo'];
      $campos['conteudo'] = htmlspecialchars($campos['conteudo'], ENT_QUOTES, 'UTF-8');
      $campos['titulo'] = htmlspecialchars($campos['titulo']);
      $campos['titulo_ocultar'] = (int) $campos['titulo_ocultar'];
      $campos['tipo'] = (int) $campos['tipo'];
      $campos['url'] = filter_var($campos['url'], FILTER_SANITIZE_URL);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      // 1 - Texto, 2 - Imagem, 3 - Video
      if (isset($params['tipo']) and ! in_array($campos['tipo'], [1, 2, 3])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('tipo', 'valInvalido');
      }

      if (isset($params['titulo_ocultar']) and ! in_array($campos['titulo_ocultar'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('titulo_ocultar', 'valInvalido');
      }

      if ($campos['tipo'] == 3 and isset($params['url']) and filter_var($campos['url'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('url', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $tipoCaracteres = 1;
      $ordemCaracteres = 999999999;
      $urlCaracteres = 255;
      $tituloCaracteres = 255;
      $tituloOcultarCaracteres = 1;
      $artigoIdCaracteres = 999999999;
      $empresaIdCaracteres = 999999999;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['tipo']) > $tipoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('tipo', 'caracteres', $tipoCaracteres);
      }

      if (strlen($campos['artigo_id']) > $artigoIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('artigo_id', 'caracteres', $artigoIdCaracteres);
      }

      if (strlen($campos['empresa_id']) > $empresaIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('empresa_id', 'caracteres', $empresaIdCaracteres);
      }

      if (strlen($campos['titulo']) > $tituloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('titulo', 'caracteres', $tituloCaracteres);
      }

      if (strlen($campos['titulo_ocultar']) > $tituloOcultarCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('titulo_ocultar', 'caracteres', $tituloOcultarCaracteres);
      }

      if (strlen($campos['ordem']) > $ordemCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ordem', 'caracteres', $ordemCaracteres);
      }

      if (strlen($campos['url']) > $urlCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('url', 'caracteres', $urlCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'artigo_id' => $campos['artigo_id'],
      'empresa_id' => $campos['empresa_id'],
      'tipo' => $campos['tipo'],
      'titulo' => $campos['titulo'],
      'titulo_ocultar' => $campos['titulo_ocultar'],
      'conteudo' => $campos['conteudo'],
      'url' => $campos['url'],
      'ordem' => $campos['ordem'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! array_key_exists($chave, $params)) {
          unset($camposValidados[ $chave ]);
        }
      endforeach;
    }

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';

      return $msgErro;
    }

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'artigo_id') {
      $campo = 'ID do artigo';
    }

    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
    }

    if ($campo == 'titulo') {
      $campo = 'título';
    }

    if ($campo == 'titulo_ocultar') {
      $campo = 'ocultar títulio';
    }

    $msgErro = [
      'vazio' => 'O campo ' . $campo . ' não pode ser vazio',
      'invalido' => 'Campo ' . $campo . ' com formato inválido',
      'valInvalido' => 'Campo ' . $campo . ' com valor inválido',
      'caracteres' => 'Campo ' . $campo . ' excedeu o limite de ' . $quantidade . ' caracteres',
    ];

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }
}