<?php
use app\Models\Model;

function buscarAjuste(string $nome) {
  $ajusteModel = new Model('Ajuste');

  $condicoes = [
    'Ajuste.nome' => $nome,
  ];

  $colunas = [
    'Ajuste.ativo',
  ];

  $resultado = $ajusteModel->condicao($condicoes)
                            ->buscar($colunas);

  return intval($resultado[0]['Ajuste.ativo'] ?? 0);
}

function debug($valor, $dump = false) {

  echo '<pre>';

  if ($dump) {
    var_dump($valor);
  }
  else {
    print_r($valor);
  }

  echo '</pre>';
}

function traduzirDataPtBr($data) {
  $dateTime = new DateTime($data);

  $formatter = new IntlDateFormatter(
    'pt_BR'
    ,IntlDateFormatter::FULL
    ,IntlDateFormatter::NONE
    ,'America/Sao_Paulo'
    ,IntlDateFormatter::GREGORIAN
    ,"dd'/'MM'/'yyyy 'às' HH:mm"
  );

  return $formatter->format($dateTime);
}

function registrarLog($nome, $arquivo) {
  $logMensagem = str_repeat("-", 150) . PHP_EOL . PHP_EOL;
  $logMensagem .= date('Y-m-d H:i:s') . ' - ' . $nome . PHP_EOL . PHP_EOL;
  $logMensagem .= json_encode($arquivo, JSON_UNESCAPED_SLASHES) . PHP_EOL . PHP_EOL;
  error_log($logMensagem, 3, './app/logs/' . $nome . '-' . date('Y-m-d') . '.log');
}