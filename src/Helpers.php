<?php
function loadSecrets($file = __DIR__ . '/../secrets.env') {
    if (!file_exists($file)) return;
    $env = parse_ini_file($file);
    foreach ($env as $k => $v) {
        if (getenv($k) === false) putenv("$k=$v");
    }
}

function env($name, $default = null) {
    $v = getenv($name);
    return $v === false ? $default : $v;
}

spl_autoload_register(function($class){
    $path = __DIR__ . '/' . str_replace('\\','/',$class) . '.php';
    if (file_exists($path)) require $path;
});

loadSecrets();
