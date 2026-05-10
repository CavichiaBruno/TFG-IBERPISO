<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Forzamos a que no se use el dumper de Symfony que está fallando
putenv('APP_DEBUG=false'); 

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die("ERROR: Vendor autoload not found!");
}

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>ERROR ORIGINAL DETECTADO:</h1>";
    echo "<h3>" . get_class($e) . "</h3>";
    echo "<p><b>Mensaje:</b> " . $e->getMessage() . "</p>";
    echo "<p><b>Archivo:</b> " . $e->getFile() . " en línea " . $e->getLine() . "</p>";
    echo "<hr>";
    echo "<h4>Detalles para el técnico:</h4>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
