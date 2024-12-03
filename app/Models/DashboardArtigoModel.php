<?php
namespace app\Models;
use DateTime;
use app\Models\Model;

class DashboardArtigoModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Artigo');
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

    return parent::adicionar($campos, true);
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

    $sql = 'UPDATE artigos SET ordem = CASE id ' . implode(' ', $cases) . ' END WHERE id IN (' . implode(', ', $ids) . ')';

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
      'ativo' => $params['ativo'] ?? 0,
      'titulo' => trim($params['titulo'] ?? ''),
      'usuario_id' => $params['usuario_id'] ?? 0,
      'empresa_id' => $this->empresaPadraoId,
      'categoria_id' => $params['categoria_id'] ?? 0,
      'visualizacoes' => $params['visualizacoes'] ?? 0,
      'ordem' => $params['ordem'] ?? 0,
      'modificado' => $params['modificado'] ?? '',
    ];

    $msgErro = [
      'erro' => [
        'codigo' => 400,
        'mensagem' => [],
      ],
    ];

    // Campos vazios
    foreach ($campos as $chave => $linha):
      $permitidos = [
        'ativo',
        'categoria_id',
        'visualizacoes',
        'modificado',
      ];

      if ($atualizar and ! isset($params[ $chave ])) {

        // Sempre precisa do ID da empresa
        if ($chave != 'empresa_id') {
          continue;
        }
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
      $campos['ativo'] = filter_var($campos['ativo'], FILTER_SANITIZE_NUMBER_INT);
      $campos['titulo'] = htmlspecialchars($campos['titulo']);
      $campos['usuario_id'] = filter_var($campos['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['empresa_id'] = filter_var($campos['empresa_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['categoria_id'] = filter_var($campos['categoria_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['modificado'] = htmlspecialchars($campos['modificado']);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      if ($campos['modificado']) {

        try {
          $campos['modificado'] = new DateTime($campos['modificado']);
          $campos['modificado'] = $campos['modificado']->format('Y-m-d H:i:s');
        }
        catch (Exception $e) {
          $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('modificado', 'valInvalido');
        }
      }

      $ativoCaracteres = 1;
      $tituloCaracteres = 255;
      $empresaIdCaracteres = 999999999;
      $usuarioIdCaracteres = 999999999;
      $categoriaIdCaracteres = 999999999;
      $ordemCaracteres = 999999999;
      $modificadoCaracteres = 19;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['titulo']) > $tituloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('titulo', 'caracteres', $tituloCaracteres);
      }

      if (strlen($campos['usuario_id']) > $usuarioIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('usuario_id', 'caracteres', $usuarioIdCaracteres);
      }

      if (strlen($campos['empresa_id']) > $empresaIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('empresa_id', 'caracteres', $empresaIdCaracteres);
      }

      if (strlen($campos['categoria_id']) > $categoriaIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('categoria_id', 'caracteres', $categoriaIdCaracteres);
      }

      if (strlen($campos['ordem']) > $ordemCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ordem', 'caracteres', $ordemCaracteres);
      }

      if (strlen($campos['modificado']) > $modificadoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('modificado', 'caracteres', $modificadoCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'titulo' => $campos['titulo'],
      'usuario_id' => $campos['usuario_id'],
      'empresa_id' => $campos['empresa_id'],
      'categoria_id' => $campos['categoria_id'],
      'visualizacoes' => $campos['visualizacoes'],
      'ordem' => $campos['ordem'],
      'modificado' => $campos['modificado'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (isset($params['categoria_id']) and $chave == 'categoria_id' and empty($linha)) {
          $camposValidados[ $chave ] = null;
        }
        elseif (! isset($params[ $chave ])) {
          unset($camposValidados[ $chave ]);
        }

      endforeach;
    }

    if (isset($camposValidados['modificado']) and empty($camposValidados['modificado'])) {
      unset($camposValidados['modificado']);
    }

    if (isset($camposValidados['categoria_id']) and empty($camposValidados['categoria_id'])) {
      unset($camposValidados['categoria_id']);
    }

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';

      return $msgErro;
    }

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'usuario_id') {
      $campo = 'ID do usuário';
    }

    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
    }

    if ($campo == 'categoria_id') {
      $campo = 'categoria';
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