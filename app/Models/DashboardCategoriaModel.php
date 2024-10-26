<?php
namespace app\Models;
use app\Core\Cache;
use app\Models\Model;

class DashboardCategoriaModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Categoria');
  }

  // --- CRUD ---
  public function adicionar(array $params = []): array
  {
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

    $sql = 'UPDATE categorias SET ordem = CASE id ' . implode(' ', $cases) . ' END WHERE id IN (' . implode(', ', $ids) . ')';

    return parent::executarQuery($sql);
  }

  public function apagarCategoria(int $id): array
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

    // Categoria possui artigos
    $condicoes = [
      'campo' => 'Categoria.id',
      'operador' => '=',
      'valor' => $id,
    ];

    $juntar = [
      'tabelaJoin' => 'Artigo',
      'campoA' => 'Artigo.categoria_id',
      'campoB' => 'Categoria.id',
    ];

    $categoriaArtigos = parent::contar('Categoria.id')
                              ->condicao($condicoes)
                              ->juntar($juntar)
                              ->executarConsulta();

    if (isset($categoriaArtigos['total']) and (int) $categoriaArtigos['total'] > 0) {
      $msgErro = [
        'erro' => [
          'codigo' => 400,
          'mensagem' => 'Esta categoria possui artigos publicados, não é possível apagá-la',
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
      'nome' => $params['nome'] ?? '',
      'descricao' => $params['descricao'] ?? '',
      'empresa_id' => $this->empresaPadraoId,
      'ordem' => $params['ordem'] ?? 0,
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
        'descricao',
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
      $campos['nome'] = htmlspecialchars($campos['nome']);
      $campos['empresa_id'] = filter_var($campos['empresa_id'], FILTER_SANITIZE_NUMBER_INT);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nomeCaracteres = 255;
      $descricaoCaracteres = 255;
      $empresaIdCaracteres = 999999999;
      $ordemCaracteres = 999999999;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['descricao']) > $descricaoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('descricao', 'caracteres', $descricaoCaracteres);
      }

      if (strlen($campos['empresa_id']) > $empresaIdCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('empresa_id', 'caracteres', $empresaIdCaracteres);
      }

      if (strlen($campos['ordem']) > $ordemCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ordem', 'caracteres', $ordemCaracteres);
      }
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'nome' => $campos['nome'],
      'descricao' => $campos['descricao'],
      'empresa_id' => $campos['empresa_id'],
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
    if ($campo == 'empresa_id') {
      $campo = 'empresa ID';
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