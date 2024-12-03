<?php
namespace app\Models;
use DateTime;
use app\Models\Model;

class DashboardEmpresaModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Empresa');
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

  public function buscar(array $params = []): array
  {
    return parent::selecionar($params)
                 ->executarConsulta();
  }

  public function buscarEmpresaSemId(string $coluna, $valor): array
  {
    $permitidas = [
      'id',
      'subdominio',
      'subdominio_2',
    ];

    if (! in_array($coluna, $permitidas)) {
      return [];
    }

    // Previne injection
    $valor = htmlspecialchars($valor);

    if (is_array($valor)) {
      $valor = '';
    }

    $condicoes[] = [
      'campo' => 'Empresa.' . $coluna,
      'operador' => '=',
      'valor' => $valor,
    ];

    $colunas = [
      'Empresa.id',
      'Empresa.ativo',
      'Empresa.subdominio',
      'Empresa.subdominio_2',
      'Assinatura.asaas_id',
      'Assinatura.status',
      'Assinatura.gratis_prazo',
    ];

    $juntarAssinatura = [
      'tabelaJoin' => 'Assinatura',
      'campoA' => 'Assinatura.empresa_id',
      'campoB' => 'Empresa.id',
    ];

    $resultado = $this->selecionar($colunas)
                      ->condicao($condicoes)
                      ->juntar($juntarAssinatura, 'left')
                      ->executarConsulta();

    return $resultado;
  }

  public function atualizar(array $params, int $id, bool $sistema = false): array
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
    $campos = $this->validarCampos($params, $atualizar, $sistema);

    if (isset($campos['erro'])) {
      return $campos;
    }

    // CNPJ duplicado
    if (isset($campos['cnpj']) and $campos['cnpj'] !== null) {
      $condicao = [
        ['campo' => 'Empresa.id', 'operador' => '!=', 'valor' => $id],
        ['campo' => 'Empresa.cnpj', 'operador' => '=', 'valor' => $campos['cnpj']],
      ];

      $colunas = [
        'Empresa.id',
      ];

      $resultado = parent::selecionar($colunas)
                         ->condicao($condicao)
                         ->executarConsulta();

      if (isset($resultado[0]['Empresa']['id'])) {
        $msgErro = [
          'erro' => [
            'codigo' => 400,
            'mensagem' => 'O CNPJ <span class="font-semibold">' . $params['cnpj'] . '</span> não pode ser utilizado, pois está associado a outro cadastro',
          ],
        ];

        return $msgErro;
      }
    }

    // Não permite alterar subdominio
    if (isset($campos['subdominio'])) {
      unset($campos['subdominio']);
    }

    $retorno = parent::atualizar($campos, $id);

    return $retorno;
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
  private function validarCampos(array $params, bool $atualizar = false, bool $sistema = false): array
  {
    $campos = [
      'ativo' => $params['ativo'] ?? 0,
      'nome' => $params['nome'] ?? '',
      'subdominio' => $params['subdominio'] ?? '',
      'subdominio_2' => $params['subdominio_2'] ?? '',
      'telefone' => $params['telefone'] ?? '',
      'logo' => $params['logo'] ?? '',
      'favicon' => $params['favicon'] ?? '',
      'cnpj' => $params['cnpj'] ?? '',
      'cor_primaria' => intval($params['cor_primaria'] ?? 1),
      'url_site' => $params['url_site'] ?? '',
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
        'nome',
        'cnpj',
        'telefone',
        'subdominio',
        'subdominio_2',
        'logo',
        'favicon',
        'cor_primaria',
        'url_site',
        'meta_titulo',
        'meta_descricao',
      ];

      if ($atualizar and ! isset($params[ $chave ])) {
        continue;
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
      $campos['logo'] = htmlspecialchars($campos['logo']);
      $campos['favicon'] = htmlspecialchars($campos['favicon']);
      $campos['subdominio'] = htmlspecialchars($campos['subdominio']);
      $campos['subdominio_2'] = filter_var($campos['subdominio_2'], FILTER_SANITIZE_URL);
      $campos['telefone'] = filter_var($campos['telefone'], FILTER_SANITIZE_NUMBER_INT);
      $cnpjValido = preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/', $campos['cnpj']);
      $campos['cor_primaria'] = filter_var($campos['cor_primaria'], FILTER_SANITIZE_NUMBER_INT);
      $campos['meta_titulo'] = htmlspecialchars($campos['meta_titulo']);
      $campos['meta_descricao'] = htmlspecialchars($campos['meta_descricao']);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [INATIVO, ATIVO])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      if (isset($params['cnpj']) and $cnpjValido) {
        $campos['cnpj'] = preg_replace('/[^0-9]/', '', $campos['cnpj']);
      }
      elseif (isset($params['cnpj']) and $params['cnpj'] != '') {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('cnpj', 'valInvalido');
      }

      if ($campos['url_site'] and filter_var($campos['url_site'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('url_site', 'valInvalido');
      }

      if ($campos['subdominio_2'] and filter_var($campos['subdominio_2'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio_2', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nomeCaracteres = 255;
      $subdominioCaracteres = 255;
      $subdominio2Caracteres = 255;
      $telefoneCaracteresMin = 10;
      $telefoneCaracteresMax = 11;
      $logoCaracteres = 50;
      $faviconCaracteres = 50;
      $urlSiteCaracteres = 255;
      $corPrimariaCaracteres = 2;
      $metaTituloCaracteres = 255;
      $metaDescricaoCaracteres = 255;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['subdominio']) > $subdominioCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio', 'caracteres', $subdominioCaracteres);
      }

      if (strlen($campos['subdominio_2']) > $subdominio2Caracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio_2', 'caracteres', $subdominio2Caracteres);
      }

      if ($campos['telefone'] and strlen($campos['telefone']) > $telefoneCaracteresMax) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('telefone', 'caracteres', $telefoneCaracteresMax);
      }

      if ($campos['telefone'] and strlen($campos['telefone']) < $telefoneCaracteresMin) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('telefone', 'caracteres', $telefoneCaracteresMin);
      }

      if (strlen($campos['logo']) > $logoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('logo', 'caracteres', $logoCaracteres);
      }

      if (strlen($campos['favicon']) > $faviconCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('favicon', 'caracteres', $faviconCaracteres);
      }

      if (strlen($campos['url_site']) > $urlSiteCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('url_site', 'caracteres', $urlSiteCaracteres);
      }

      if (strlen($campos['cor_primaria']) > $corPrimariaCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('cor_primaria', 'caracteres', $corPrimariaCaracteres);
      }

      if (strlen($campos['meta_titulo']) > $metaTituloCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('meta_titulo', 'caracteres', $metaTituloCaracteres);
      }

      if (strlen($campos['meta_descricao']) > $metaDescricaoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('meta_descricao', 'caracteres', $metaDescricaoCaracteres);
      }

      $campos['cnpj'] = trim($campos['cnpj']);
      $campos['subdominio'] = trim($campos['subdominio']);
    }

    if ($msgErro['erro']['mensagem']) {
      return $msgErro;
    }

    $camposValidados = [
      'ativo' => $campos['ativo'],
      'nome' => $campos['nome'],
      'subdominio' => $campos['subdominio'],
      'subdominio_2' => $campos['subdominio_2'],
      'telefone' => $campos['telefone'],
      'cnpj' => $campos['cnpj'],
      'logo' => $campos['logo'],
      'favicon' => $campos['favicon'],
      'cor_primaria' => $campos['cor_primaria'],
      'url_site' => $campos['url_site'],
      'meta_titulo' => $campos['meta_titulo'],
      'meta_descricao' => $campos['meta_descricao'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! array_key_exists($chave, $params)) {
          unset($camposValidados[ $chave ]);
        }
      endforeach;
    }

    if (isset($camposValidados['cnpj']) and empty($camposValidados['cnpj'])) {
      $camposValidados['cnpj'] = null;
    }

    if (isset($camposValidados['subdominio']) and empty($camposValidados['subdominio'])) {
      $camposValidados['subdominio'] = null;
    }

    if (isset($camposValidados['logo']) and empty($camposValidados['logo'])) {
      unset($camposValidados['logo']);
    }

    if (isset($camposValidados['favicon']) and empty($camposValidados['favicon'])) {
      unset($camposValidados['favicon']);
    }

    if ($sistema == false and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($camposValidados['ativo'])) {
      unset($camposValidados['ativo']);
    }

    if ($sistema == false and $this->usuarioLogado['padrao'] != USUARIO_SUPORTE and isset($camposValidados['assinatura_status'])) {
      unset($camposValidados['assinatura_status']);
    }

    if (empty($camposValidados)) {
      $msgErro['erro']['mensagem'][] = 'Nenhum campo informado';

      return $msgErro;
    }

    return $camposValidados;
  }

  private function gerarMsgErro(string $campo, string $tipo, int $quantidade = 0): string
  {
    if ($campo == 'cnpj') {
      $campo = 'CNPJ';
    }

    if ($campo == 'cor_primaria') {
      $campo = 'Cor primária';
    }

    if ($campo == 'url_site') {
      $campo = 'Link para o site';
    }

    if ($campo == 'subdominio_2') {
      $campo = 'Subdomínio personalizado';
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

    if ($campo == 'telefone') {
      $msgErro['caracteres'] = 'Telefone com tamanho inválido';
    }

    if (isset($msgErro[ $tipo ])) {
      return $msgErro[ $tipo ];
    }

    return 'Campo inválido';
  }
}