<?php
// Bypass all error handlers and show the raw truth
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    header('Content-Type: text/plain');
    echo "=== ERROR ORIGINAL DE LARAVEL ===\n";
    echo "CLASE: " . get_class($e) . "\n";
    echo "MENSAJE: " . $e->getMessage() . "\n";
    echo "ARCHIVO: " . $e->getFile() . "\n";
    echo "LINEA: " . $e->getLine() . "\n";
    echo "\n=== STACK TRACE ===\n";
    echo $e->getTraceAsString();
    exit;
}
