<?php

$conteudo = divAbrir();
$conteudo .= sessionAbrir();
$conteudo .= tableAbrir();
$conteudo .= tableTheadAbrir();
$conteudo .= tableTrAbrir();
$conteudo .= tableThAbrir();
$conteudo .= checkbox('artigo', 'artigoTodos', 'flex items-center');
$conteudo .= tableThFechar();
$conteudo .= tableThAbrir();
$conteudo .= 'ID';
$conteudo .= tableThFechar();
$conteudo .= tableThAbrir();
$conteudo .= 'Status';
$conteudo .= tableThFechar();
$conteudo .= tableThAbrir();
$conteudo .= 'Título';
$conteudo .= tableThFechar();
$conteudo .= tableThAbrir();
$conteudo .= 'Criado';
$conteudo .= tableThFechar();
$conteudo .= tableThAbrir();
$conteudo .= 'Modificado';
$conteudo .= tableThFechar();
$conteudo .= tableTrFechar();
$conteudo .= tableTheadFechar();

$conteudo .= tableTbodyAbrir();
foreach ($artigos as $chave => $artigo):

  $conteudo .= tableTrAbrir();
  $conteudo .= tableTdAbrir('min-w-10');
  $conteudo .= checkbox('artigo', 'artigoUnico');
  $conteudo .= tableTdFechar();
  foreach ($artigo as $subChave => $subLinha):

    if ($subChave == 'Artigo.usuario_id') {
      continue;
    }

    if ($subChave == 'Artigo.categoria_id') {
      continue;
    }

    if ($subChave == 'Artigo.visualizacoes') {
      continue;
    }

    if ($subChave == 'Artigo.id') {
      $subLinha = '#' . $subLinha;
      $conteudo .= tableTdAbrir('min-w-32');
    }
    elseif ($subChave == 'Artigo.ativo') {

      if ($subLinha == 1)  {
        $subLinha = 'Ativo';
      }
      elseif ($subLinha == 0) {
        $subLinha = 'Inativo';
      }

      $conteudo .= tableTdAbrir('min-w-32');
    }
    elseif ($subChave == 'Artigo.titulo') {
      $conteudo .= tableTdAbrir('min-w-min w-full text-black font-semibold');
    }
    elseif ($subChave == 'Artigo.criado') {
      $conteudo .= tableTdAbrir('min-w-60');
    }
    elseif ($subChave == 'Artigo.modificado') {
      $conteudo .= tableTdAbrir('min-w-60');
    }

    $conteudo .= $subLinha;
    $conteudo .= tableTdFechar();
  endforeach;

  $conteudo .= tableTrFechar();
endforeach;

$conteudo .= tableTbodyFechar();
$conteudo .= tableFechar();
$conteudo .= sessionFechar();
$conteudo .= divFechar();

function divAbrir($classes = '') {
  $classesBase = 'flex gap-6 w-full h-full';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<div class="' . $classesBase . '">';
}

function checkbox($nome, $id, $classes = '') {
  $classesBase = '';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<input type="checkbox" name="' . $nome . '" id="' . $id . '" class="' . $classesBase . '">';
}

function sessionAbrir($classes = '') {
  $classesBase = 'w-full h-full bg-white p-6 border border-slate-200 rounded-lg';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<session class="' . $classesBase . '">';
}

function tableAbrir($classes = '') {
  $classesBase = 'w-full table-auto text-sm text-left text-gray-500';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<table class="' . $classesBase . '">';
}

function tableTheadAbrir($classes = '') {
  $classesBase = 'text-xs text-gray-700 uppercase bg-gray-50';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<thead class="' . $classesBase . '">';
}

function tableThAbrir($classes = '') {
  $classesBase = 'px-6 py-3';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<th scope="col" class="' . $classesBase . '">';
}

function tableTdAbrir($classes = '') {
  $classesBase = 'whitespace-nowrap p-6';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<td class="' . $classesBase . '">';
}

function tableTrAbrir($classes = '') {
  $classesBase = 'bg-white border-b hover:bg-gray-50';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<tr class="' . $classesBase . '">';
}

function tableTbodyAbrir($classes = '') {
  $classesBase = '';

  if ($classes) {
    $classesBase .= ' ' . $classes;
  }

  return '<tbody class="' . $classesBase . '">';
}

function divFechar() {
  return '</div>';
}

function sessionFechar() {
  return '</session>';
}

function tableFechar() {
  return '</table>';
}

function tableTheadFechar() {
  return '</thead>';
}

function tableTbodyFechar() {
  return '</tbody>';
}

function tableTrFechar() {
  return '</tr>';
}

function tableThFechar() {
  return '</th>';
}

function tableTdFechar() {
  return '</td>';
}