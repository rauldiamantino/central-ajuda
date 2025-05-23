<?php

if (isset($_SERVER['HTTP_HOST'])) {
  $host = $_SERVER['HTTP_HOST'];
  $host = htmlspecialchars($host, ENT_QUOTES, 'UTF-8');
}
else {
  $host = $_SERVER['SERVER_NAME'];
  $host = htmlspecialchars($host, ENT_QUOTES, 'UTF-8');
}

if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on') {
  $protocolo = 'https://';
}
else {
  $protocolo = 'http://';
}

// Core
define('HOST_LOCAL', strpos($host, 'localhost') !== false);

define('REFERER', $_SERVER['HTTP_REFERER'] ?? '/login');
define('RAIZ', '/');
define('PROTOCOLO', $protocolo);
define('REQUISICAO_FETCH', isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');

// Constantes
define('USUARIO_SUPORTE', 99);
define('USUARIO_SISTEMA', 98);
define('USUARIO_PADRAO', 1);
define('USUARIO_COMUM', 2);

define('USUARIO_TOTAL', 1);
define('USUARIO_LEITURA', 2);
define('ATIVO', 1);
define('INATIVO', 0);

// Erros
define('MSG_ERRO_PERMISSAO', 'Você não tem permissão para realizar esta ação.');

// Classes
define('CLASSES_LOGIN_INPUT', 'block w-full border-0 px-6 py-4 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-white rounded-md shadow-sm');
define('CLASSES_LOGIN_BUTTON', 'w-full flex justify-center bg-blue-900 px-6 py-4 text-sm font-semibold leading-6 text-white hover:bg-blue-500 rounded-md shadow-sm');
define('CLASSES_LOGIN_REDIR_BUTTON', 'h-full flex items-center justify-center bg-blue-900 px-6 py-2 text-sm font-semibold leading-6 text-white hover:bg-blue-500 rounded-md shadow-sm');
define('CLASSES_DASH_INPUT', 'block w-full border-0 px-6 py-4 text-gray-900 ring-1 ring-inset ring-gray-200 hover:ring-gray-300 duration-100 focus:ring-1 focus:ring-inset focus:ring-gray-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-white rounded-md shadow-sm');
define('CLASSES_DASH_INPUT_BUSCA', 'block w-full border border-slate-200 px-6 py-2 text-gray-600 sm:text-md font-light sm:leading-6 outline-none placeholder:text-gray-600 bg-white focus:bg-gray-50 rounded-md input-busca');
define('CLASSES_DASH_INPUT_BUSCA_GRANDE1', 'border border-gray-200 block w-full text-black px-6 py-4 sm:text-md font-light sm:leading-6 outline-none rounded-lg hover:shadow-lg focus:shadow-lg input-busca');
define('CLASSES_DASH_INPUT_BUSCA_GRANDE2', 'border border-transparent block w-full text-black px-6 py-4 sm:text-md font-light sm:leading-6 outline-none rounded-lg shadow-lg input-busca');
define('CLASSES_DASH_INPUT_BUSCA_GRANDE_TRANSPARENTE', 'border border-transparent px-6 py-4 text-white focus:text-gray-600 sm:leading-6 placeholder-white font-extralight focus:placeholder-gray-600 rounded-lg outline-none ring-none transition-all shadow-md w-full focus:bg-white bg-black/15 hover:shadow-lg focus:shadow-lg input-busca-efeito');
define('CLASSES_DASH_INPUT_BLOCK', 'block w-full border-0 px-6 py-4 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-gray-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-gray-100 rounded-md shadow-sm');
define('CLASSES_DASH_TEXTAREA', 'block w-full border-0 px-6 py-4 h-56 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_VOLTAR', 'border border-slate-300 w-full sm:w-max flex justify-center bg-gray-50 hover:bg-gray-200 px-6 py-2 text-sm text-gray-600 rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_LIMPAR', 'border-dashed border border-slate-300 w-full sm:w-max flex justify-center bg-gray-50 hover:bg-gray-50/75 px-6 py-2 text-sm text-gray-600 rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_GRAVAR', 'border border-blue-800 hover:border-blue-600 w-full sm:w-max flex justify-center bg-blue-800 hover:bg-blue-600 px-6 py-2 text-sm text-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_ASSINAR', 'border border-blue-800 hover:border-blue-600 w-full flex justify-center bg-blue-800 hover:bg-blue-600 px-4 py-1 text-sm text-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_ADICIONAR', 'border border-green-800 hover:border-green-600 w-full sm:w-max flex justify-center items-center gap-2 bg-green-800 hover:bg-green-600 px-6 py-2 text-sm text-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_REMOVER', 'border border-red-800 hover:border-red-600 w-full sm:w-max flex justify-center items-center gap-2 bg-red-800 hover:bg-red-600 px-6 py-2 text-sm text-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_REMOVER_2', 'border border-slate-300 w-full sm:w-max flex justify-center bg-gray-50 hover:bg-gray-200 px-6 py-2 text-sm text-red-600 rounded-md shadow-sm');

// JSON
define('JSON_FORMATADO', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);