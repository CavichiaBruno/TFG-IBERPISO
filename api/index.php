<?php

use Illuminate\Http\Request;

// 1. Cargamos el autoload para tener las constantes de Laravel
require __DIR__ . '/../vendor/autoload.php';

// 2. Arrancamos la aplicación
$app = require __DIR__ . '/../bootstrap/app.php';

// 3. Forzamos la ruta de bootstrap a /tmp para evitar errores de escritura en Vercel
if (isset($_SERVER['VERCEL_URL'])) {
    $app->useBootstrapPath('/tmp');
    
    // Aseguramos que existan los archivos mínimos en /tmp para que no intente recrearlos
    if (!file_exists('/tmp/packages.php')) {
        file_put_contents('/tmp/packages.php', '<?php return []; ?>');
    }
    if (!file_exists('/tmp/services.php')) {
        file_put_contents('/tmp/services.php', '<?php return []; ?>');
    }
}

// 4. Procesamos la petición
$app->handleRequest(Request::capture());
