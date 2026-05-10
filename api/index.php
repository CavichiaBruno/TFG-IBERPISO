<?php

// Forzamos una APP_KEY básica si no existe para evitar que el ExceptionHandler explote
if (!getenv('APP_KEY')) {
    putenv('APP_KEY=base64:ZmFrZV9rZXlfZm9yX2RpYWdub3N0aWNzX29ubHlfMTIzNDU=');
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    header('Content-Type: text/plain');
    echo "=== ERROR REAL DETECTADO ===\n";
    echo "MENSAJE: " . $e->getMessage() . "\n";
    echo "ARCHIVO: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit;
}
