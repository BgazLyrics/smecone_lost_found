<?php

// 1. Buat folder temporary yang dibutuhkan oleh Laravel & Blade compiler
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set environment variable penting untuk cache & views
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_STORAGE=/tmp/storage');

// 3. Load Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 4. Inisialisasi Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Arahkan storage path
$app->useStoragePath('/tmp/storage');

// 6. Handle Request menggunakan Kernel HTTP Standar
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
