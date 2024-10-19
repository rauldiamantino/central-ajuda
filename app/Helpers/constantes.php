<?php
// Base
define('DOMINIO', 'http://' . $_SERVER['HTTP_HOST']);
define('HOST_LOCAL', strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
define('RAIZ', '/');

// Constantes
define('USUARIO_SUPORTE', 99);
define('USUARIO_SISTEMA', 98);
define('USUARIO_PADRAO', 1);
define('USUARIO_COMUM', 2);
define('USUARIO_TOTAL', 1);
define('USUARIO_RESTRITO', 2);
define('ATIVO', 1);
define('INATIVO', 0);

// Login
define('CLASSES_LOGIN_INPUT', 'block w-full border-0 px-6 py-4 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-white rounded-md shadow-sm');
define('CLASSES_LOGIN_BUTTON', 'w-full flex justify-center bg-blue-900 px-6 py-4 text-sm font-semibold leading-6 text-white hover:bg-blue-500 rounded-md shadow-sm');

// Dashboard
define('CLASSES_DASH_INPUT', 'block w-full border-0 px-6 py-4 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-white rounded-md shadow-sm');
define('CLASSES_DASH_INPUT_BUSCA', 'block w-full border border-slate-100 px-6 py-2 text-gray-600 sm:text-md font-light sm:leading-6 outline-none placeholder:text-gray-600 bg-white focus:bg-gray-50 rounded-md input-busca');
define('CLASSES_DASH_INPUT_BUSCA_GRANDE', 'block w-full border border-slate-100 px-6 py-4 text-gray-600 sm:text-md font-light sm:leading-6 outline-none placeholder:text-gray-600 bg-white focus:bg-gray-50 rounded-md input-busca');
define('CLASSES_DASH_INPUT_BLOCK', 'block w-full border-0 px-6 py-4 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-gray-100 rounded-md shadow-sm');
define('CLASSES_DASH_TEXTAREA', 'block w-full border-0 px-6 py-4 h-56 text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-1 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 outline-none placeholder:text-gray-400 bg-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_VOLTAR', 'border border-slate-300 w-max flex justify-center bg-gray-50 hover:bg-gray-200 px-6 py-2 text-sm text-gray-600 rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_GRAVAR', 'border border-blue-800 hover:border-blue-600 w-max flex justify-center bg-blue-800 hover:bg-blue-600 px-6 py-2 text-sm text-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_ADICIONAR', 'border border-green-800 hover:border-green-600 w-max flex justify-center items-center gap-2 bg-green-800 hover:bg-green-600 px-6 py-2 text-sm text-white rounded-md shadow-sm');
define('CLASSES_DASH_BUTTON_REMOVER', 'border border-red-800 hover:border-red-600 w-max flex justify-center items-center gap-2 bg-red-800 hover:bg-red-600 px-6 py-2 text-sm text-white rounded-md shadow-sm');

define('JSON_FORMATADO', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);