<?php
require '../vendor/autoload.php';

use app\Core\SessaoUsuario;
use app\Roteamento\Roteador;

$sessaoUsuario = new SessaoUsuario();
$roteador = new Roteador();

$roteador->rotear();


// // TESTES - APAGAR
// use app\Models\Model;

// $model = new Model(1, 1, 'Usuario');
// // $resultado = $model->selecionar(['Usuario.id', 'Usuario.nome']);

// $campos = [
//   'Usuario.id',
//   'Empresa.id'
// ];

// $addJoin = [
//   'tabelaJoin' => 'Empresa',
//   'campoA' => 'Empresa.id',
//   'campoB' => 'Usuario.empresa_id',
// ];

// $condicoes = [
//   ['campo' => 'Usuario.id', 'operador' => 'IN', 'valor' => [1,86,87]],
// ];

// $resultado = $model->selecionar($campos)
//                    ->juntar($addJoin)
//                    ->condicao($condicoes)
//                    ->executarConsulta();

// debug($resultado);