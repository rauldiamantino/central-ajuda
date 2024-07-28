<?php

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