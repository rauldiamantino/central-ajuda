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