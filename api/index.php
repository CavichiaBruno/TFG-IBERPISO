<?php
echo "<h1>SISTEMA DE DIAGNÓSTICO IBERPISO</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
echo "Vendor exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'SÍ' : 'NO') . "<br>";
echo "Public exists: " . (is_dir(__DIR__ . '/../public') ? 'SÍ' : 'NO') . "<br>";
echo "<hr>";

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h2>Error de Laravel:</h2>";
    echo $e->getMessage();
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
