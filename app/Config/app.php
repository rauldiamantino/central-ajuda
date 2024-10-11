<?php

define('DB_HOST', 'localhost');
define('DB_NOME', 'central-ajuda');
define('DB_USUARIO', 'root');
define('DB_SENHA', 'root');
define('SEGREDO_SESSAO', '!Central@Ajuda@2024@Dev@1');
define('APLICACAO', $_SERVER['HTTP_HOST'] . 'central-ajuda');
define('DOMINIO', 'http://' . $_SERVER['HTTP_HOST']);
define('HOST_LOCAL', strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);