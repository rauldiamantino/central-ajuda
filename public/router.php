<?php
$request_uri = __DIR__ . $_SERVER["REQUEST_URI"];

if (file_exists($request_uri) && ! preg_match('/\.php$/', $request_uri)) {
    return false;
}

if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

include __DIR__ . '/index.php';
