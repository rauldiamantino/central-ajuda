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

    return $this->adicionarArtigo($campos, true);
  }

  private function adicionarArtigo(array $campos): array
  {
    $this->database->iniciar();

    try {
      $ativo = (int) $campos['ativo'];
      $titulo = $campos['titulo'];
      $usuarioId = (int) $campos['usuario_id'];
      $empresaId = (int) $campos['empresa_id'];
      $categoriaId = intval($campos['categoria_id'] ?? 0);
      $editar = intval($campos['editar'] ?? 0);
      $ordem = (int) $campos['ordem'];
      $metaTitulo = $campos['meta_titulo'];
      $metaDescricao = $campos['meta_descricao'];

      $sqlCodigo = 'SELECT IFNULL(MAX(codigo), 0) + 1 AS `proximo_codigo` FROM `artigos` WHERE `empresa_id` = ?';
      $proximoCodigo = $this->database->operacoes($sqlCodigo, [ $empresaId ]);

      if (empty($proximoCodigo) or ! isset($proximoCodigo[0]['proximo_codigo'])) {
        throw new Exception("Erro ao obter próximo código sequencial.");
      }

      $colunasValores = [
        'ativo' => $ativo,
        'titulo' => $titulo,
        'usuario_id' => $usuarioId,
        'empresa_id' => $empresaId,
        'categoria_id' => $categoriaId,
        'editar' => $editar,
        'ordem' => $ordem,
        'meta_titulo' => $metaTitulo,
        'meta_descricao' => $metaDescricao,
        'codigo' => $proximoCodigo[0]['proximo_codigo'],
      ];

      if ($categoriaId == 0) {
        unset($colunasValores['categoria_id']);
      }

      $placeholders = implode(', ', array_fill(0, count($colunasValores), '?'));
      $sql = 'INSERT INTO `artigos` (' . implode(', ', array_keys($colunasValores)) . ') VALUES (' . $placeholders . ')';

      $operacao = $this->database->operacoes($sql, $colunasValores);
      $this->database->commit();

      return $operacao;
    }
    catch (Exception $e) {
      $this->database->rollback();
      return ['erro' => $e->getMessage()];
    }
  }

  public function apagarConteudos(int $artigoId, int $empresaId): array
  {
    if (empty($artigoId)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'ID não informado',
        ],
      ];

      return $msgErro;
    }

    if (empty($empresaId)) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Empresa ID não informado',
        ],
      ];

      return $msgErro;
    }

    $sql = 'DELETE FROM `conteudos` WHERE `artigo_id` = ? AND empresa_id = ?';

    $params = [
      0 => $artigoId,
      1 => $empresaId,
    ];

    return parent::executarQuery($sql, $params);
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
      'excluido' => $params['excluido'] ?? 0,
      'titulo' => trim($params['titulo'] ?? ''),
      'usuario_id' => $params['usuario_id'] ?? 0,
      'empresa_id' => $this->empresaPadraoId,
      'categoria_id' => $params['categoria_id'] ?? 0,
      'editar' => intval($params['editar'] ?? 0),
      'ordem' => $params['ordem'] ?? 0,
      'modificado' => $params['modificado'] ?? '',
      'meta_titulo' => $params['meta_titulo'] ?? '',
      'meta_descricao' => $params['meta_descricao'] ?? '',
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
        'excluido',
        'categoria_id',
        'editar',
        'modificado',
        'meta_titulo',
        'meta_descricao',
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
      $campos['editar'] = filter_var($campos['editar'], FILTER_SANITIZE_NUMBER_INT);
      $campos['excluido'] = filter_var($campos['excluido'], FILTER_SANITIZE_NUMBER_INT);
      $campos['titulo'] = htmlspecialchars($campos['titulo']);
      $campos['usuario_id'] = filter_var($campos['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['empresa_id'] = filter_var($campos['empresa_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['categoria_id'] = filter_var($campos['categoria_id'], FILTER_SANITIZE_NUMBER_INT);
      $campos['modificado'] = htmlspecialchars($campos['modificado']);
      $campos['meta_titulo'] = htmlspecialchars($campos['meta_titulo']);
      $campos['meta_descricao'] = htmlspecialchars($campos['meta_descricao']);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      if (isset($params['editar']) and ! in_array($campos['editar'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('editar', 'valInvalido');
      }

      if (isset($params['excluido']) and ! in_array($campos['excluido'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('excluido', 'valInvalido');
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
      $editarCaracteres = 1;
      $excluidoCaracteres = 1;
      $tituloCaracteres = 255;
      $empresaIdCaracteres = 999999999;
      $usuarioIdCaracteres = 999999999;
      $categoriaIdCaracteres = 999999999;
      $ordemCaracteres = 999999999;
      $modificadoCaracteres = 19;
      $metaTituloCaracteres = 255;
      $metaDescricaoCaracteres = 255;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['editar']) > $editarCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $editarCaracteres);
      }

      if (strlen($campos['excluido']) > $excluidoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $excluidoCaracteres);
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

      if (strlen($campos['meta_titulo']) > $metaTituloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('meta_titulo', 'caracteres', $metaTituloCaracteres);
      }

      if (strlen($campos['meta_descricao']) > $metaDescricaoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('meta_descricao', 'caracteres', $metaDescricaoCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'excluido' => $campos['excluido'],
      'titulo' => $campos['titulo'],
      'usuario_id' => $campos['usuario_id'],
      'empresa_id' => $campos['empresa_id'],
      'categoria_id' => $campos['categoria_id'],
      'editar' => $campos['editar'],
      'ordem' => $campos['ordem'],
      'modificado' => $campos['modificado'],
      'meta_titulo' => $campos['meta_titulo'],
      'meta_descricao' => $campos['meta_descricao'],
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
    if ($campo == 'excluido') {
      $campo = 'excluído';
    }

    if ($campo == 'usuario_id') {
      $campo = 'ID do usuário';
    }

    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
    }

    if ($campo == 'categoria_id') {
      $campo = 'categoria';
    }

    if ($campo == 'meta_titulo') {
      $campo = 'meta título';
    }

    if ($campo == 'meta_descricao') {
      $campo = 'meta descrição';
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