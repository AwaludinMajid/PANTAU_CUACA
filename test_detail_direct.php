<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Http\Controllers\WeatherController;

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

try {
    $controller = new WeatherController();
    $result = $controller->detail('Jakarta');

    echo "SUCCESS: Method detail executed without errors\n";
    echo "Result type: " . gettype($result) . "\n";

    if (is_object($result)) {
        echo "Result class: " . get_class($result) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}