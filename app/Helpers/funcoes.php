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

  if (empty($data)) {
    return '';
  }

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

function traduzirDataPtBrAmigavel($data) {

  if (empty($data)) {
     return '';
  }

  $dataAtual = new DateTime();
  $dataAlvo = new DateTime($data);
  $diferenca = $dataAtual->diff($dataAlvo);

  if ($diferenca->invert === 1) { // Data passada
    if ($diferenca->y > 0) {
      return "{$diferenca->y} ano" . ($diferenca->y > 1 ? 's' : '');
    }

    if ($diferenca->m > 0) {
      return "{$diferenca->m} mês" . ($diferenca->m > 1 ? 'es' : '');
    }

    if ($diferenca->d > 0) {
      return "{$diferenca->d} dia" . ($diferenca->d > 1 ? 's' : '');
    }

    if ($diferenca->h > 0) {
      return "{$diferenca->h} hora" . ($diferenca->h > 1 ? 's' : '');
    }

    if ($diferenca->i > 0) {
      return "{$diferenca->i} minuto" . ($diferenca->i > 1 ? 's' : '');
    }

    return "poucos segundos";
  }

  return "no futuro";
}


function traduzirDataTimestamp($timestamp) {
  return date('d/m/Y \à\s H:i', $timestamp);
}

function converterInteiroParaDecimal(int $valor = 0) {
  $valorConvertido = $valor / 100;

  return number_format($valorConvertido, 2, '.', '');
}

function registrarLog($nome, $arquivo, $debug = false) {
  // Remove quebras de linha e espaços extras
  if ($nome == 'database' and isset($arquivo['sql']) && is_string($arquivo['sql'])) {
    $arquivo['sql'] = preg_replace('/\s+/', ' ', $arquivo['sql']);
    $arquivo['sql'] = str_replace('\r\n', ' ', $arquivo['sql']);
  }

  $logMensagem = str_repeat("-", 150) . PHP_EOL . PHP_EOL;
  $logMensagem .= date('Y-m-d H:i:s') . ' - ' . $nome . PHP_EOL . PHP_EOL;
  $logMensagem .= json_encode($arquivo, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL;

  global $sessaoUsuario;
  $sessaoUsuario = $sessaoUsuario;
  $debugAtivo = $sessaoUsuario->buscar('debugAtivo');

  if ($debugAtivo) {
    $sessaoUsuario->definir('debug', $logMensagem, true);
  }

  if (HOST_LOCAL or $debug === true) {
    error_log($logMensagem, 3, '../app/logs/' . $nome . '-' . date('Y-m-d') . '.log');
  }
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

function registrarSentry($erro, $params = [], $nivel = null) {

  if (HOST_LOCAL) {
    return;
  }

  if ($erro instanceof Exception) {
    Sentry\configureScope(function (Sentry\State\Scope $scope) use ($params, $nivel) {
      $scope->setExtras($params);

      if ($nivel) {
        $scope->setLevel(new Sentry\Severity($nivel));
      }
    });

    Sentry\captureException($erro);
  }
  elseif (is_string($erro)) {
    Sentry\configureScope(function (Sentry\State\Scope $scope) use ($params, $nivel) {
      $scope->setExtras($params);

      if ($nivel) {
        $scope->setLevel(new Sentry\Severity($nivel));
      }
    });

    Sentry\captureMessage($erro);
  }
  else {
    throw new InvalidArgumentException('O parâmetro $erro deve ser uma string ou uma instância de Exception.');
  }
}
