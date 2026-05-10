<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Forward Vercel requests to the Laravel public index
require __DIR__ . '/../public/index.php';
