<?php
function pr($valor, $dump = false) {

  echo '<pre>';

  if ($dump) {
    var_dump($valor);
  }
  else {
    print_r($valor);
  }

  echo '</pre>';
}

function baseUrl($url = '') {
  return RAIZ . ltrim($url, '/');
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

function traduzirDataTimestamp($timestamp) {
  return date('d/m/Y \à\s H:i', $timestamp);
}

function registrarLog($nome, $arquivo) {

  // Remove quebras de linha e espaços extras
  if ($nome == 'database' and isset($arquivo['sql']) && is_string($arquivo['sql'])) {
    $arquivo['sql'] = preg_replace('/\s+/', ' ', $arquivo['sql']);
  }

  $logMensagem = str_repeat("-", 150) . PHP_EOL . PHP_EOL;
  $logMensagem .= date('Y-m-d H:i:s') . ' - ' . $nome . PHP_EOL . PHP_EOL;
  $logMensagem .= json_encode($arquivo, JSON_UNESCAPED_SLASHES) . PHP_EOL . PHP_EOL;

  error_log($logMensagem, 3, '../logs/' . $nome . '-' . date('Y-m-d') . '.log');
}

function subdominioDominio(string $subdominio = '', $protocolo = true) {

  $dominio = $_SERVER['HTTP_HOST'];
  $http = 'https://';

  // if ($subdominio) {
  //   $dominio = $subdominio . '.' . $dominio;
  // }

  $dominio = $subdominio;

  if (strpos($dominio, 'localhost')) {
    $http = 'http://';
  }

  if ($protocolo) {
    return $http . $dominio;
  }

  return $dominio;
}