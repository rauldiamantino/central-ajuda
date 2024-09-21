<?php
namespace app\Models;
use app\Models\Model;

class DashboardEmpresaModel extends Model
{
  public function __construct()
  {
    parent::__construct('Empresa');
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
    return parent::buscar($params);
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

    // CNPJ duplicado
    if (isset($campos['cnpj']) and $campos['cnpj'] !== null) {
      $sql = 'SELECT `Empresa`.`id` AS `Empresa.id` FROM `empresas` AS `Empresa` WHERE `Empresa`.`id` != ? AND `Empresa`.`cnpj` = ?';

      $sqlParams = [
        0 => $id,
        1 => $campos['cnpj'],
      ];

      $resultado = parent::executarQuery($sql, $sqlParams);

      if (isset($resultado[0]['Empresa.id'])) {
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
  private function validarCampos(array $params, bool $atualizar = false): array
  {
    $campos = [
      'ativo' => $params['ativo'] ?? 0,
      'nome' => $params['nome'] ?? '',
      'subdominio' => $params['subdominio'] ?? '',
      'telefone' => $params['telefone'] ?? '',
      'logo' => $params['logo'] ?? '',
      'cnpj' => $params['cnpj'] ?? '',
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
        'logo',
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
      $campos['subdominio'] = htmlspecialchars($campos['subdominio']);
      $campos['telefone'] = filter_var($campos['telefone'], FILTER_SANITIZE_NUMBER_INT);
      $cnpjValido = preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/', $campos['cnpj']);
      $campos['logo'] = filter_var($campos['logo'], FILTER_SANITIZE_URL);

      if (isset($params['ativo']) and ! in_array($campos['ativo'], [0, 1])) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('ativo', 'valInvalido');
      }

      if (isset($params['cnpj']) and $cnpjValido) {
        $campos['cnpj'] = preg_replace('/[^0-9]/', '', $campos['cnpj']);
      }
      elseif (isset($params['cnpj']) and $params['cnpj'] != '') {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('cnpj', 'valInvalido');
      }

      if (isset($params['logo']) and filter_var($campos['logo'], FILTER_VALIDATE_URL) == false) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('logo', 'valInvalido');
      }

      $ativoCaracteres = 1;
      $nomeCaracteres = 255;
      $subdominioCaracteres = 255;
      $telefoneCaracteresMin = 10;
      $telefoneCaracteresMax = 11;
      $logoCaracteres = 255;

      if (strlen($campos['ativo']) > $ativoCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('id', 'caracteres', $ativoCaracteres);
      }

      if (strlen($campos['nome']) > $nomeCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('nome', 'caracteres', $nomeCaracteres);
      }

      if (strlen($campos['subdominio']) > $subdominioCaracteres) {
        $msgErro['erro']['mensagem'][] = $this->gerarMsgErro('subdominio', 'caracteres', $subdominioCaracteres);
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
      'telefone' => $campos['telefone'],
      'cnpj' => $campos['cnpj'],
      'logo' => $campos['logo'],
    ];

    if ($atualizar) {
      foreach ($camposValidados as $chave => $linha):

        if (! isset($params[ $chave ])) {
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
      $camposValidados['logo'] = null;
    }

    if (isset($camposValidados['ativo'])) {
      unset($camposValidados['ativo']);
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