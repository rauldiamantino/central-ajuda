<?php
require '../vendor/autoload.php';

use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;

$sessaoUsuario = new SessaoUsuario();
$roteador = new Roteador();

// $roteador->rotear();





// TESTES - APAGAR
use app\Models\PreparaConsultaModel;

$preparaModel = new PreparaConsultaModel('Usuario');
// $resultado = $preparaModel->selecionar(['Usuario.id', 'Usuario.nome']);

$campos = [
  'Usuario.id',
  'Usuario.nome',
  'Empresa.id'
];

$addJoin = [
  'tabelaJoin' => 'Empresa',
  'campoA' => 'Empresa.id',
  'campoB' => 'Usuario.empresa_id',
];

$condicoes = [
 'campo' => 'Usuario.id',
 'operador' => '=',
 'valor' => 1,
];

$resultado = $preparaModel->selecionar($campos)
                          ->adicionarJoin($addJoin)
                          ->adicionarCondicao($condicoes)
                          ->adicionarOrdem('Usuario.id')
                          ->adicionarLimite(5)
                          ->executarConsulta();

debug($resultado);