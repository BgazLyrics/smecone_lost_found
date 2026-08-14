<?php

// 1. Tangkap semua error PHP agar tampil jelas di layar jika terjadi crash
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 2. Buat direktori storage sementara di /tmp (Wajib untuk Vercel Serverless)
    $storageDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/logs',
        '/tmp/storage/app/public',
    ];

    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // 3. Set environment path storage & compile Blade ke /tmp
    putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

    // 4. Load Composer Autoloader
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new Exception("Vendor directory not found. Composer install might have failed during build.");
    }
    require __DIR__ . '/../vendor/autoload.php';

    // 5. Bootstrap Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath('/tmp/storage');

    // 6. Handle Request (Mendukung Laravel 11 & versi sebelumnya)
    if (method_exists($app, 'handleRequest')) {
        $app->handleRequest(\Illuminate\Http\Request::capture());
    } else {
        $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle(
            $request = \Illuminate\Http\Request::capture()
        );
        $response->send();
        $kernel->terminate($request, $response);
    }

} catch (\Throwable $e) {
    http_response_code(500);
    echo '<div style="font-family: monospace; padding: 24px; background: #fff1f0; border: 2px solid #ff4d4f; border-radius: 8px; margin: 20px; color: #000;">';
    echo '<h2 style="color: #cf1322; margin-top: 0;">Laravel Serverless Crash Report</h2>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ' (Line: ' . $e->getLine() . ')</p>';
    echo '<h3 style="margin-bottom: 8px;">Stack Trace:</h3>';
    echo '<pre style="background: #ffffff; padding: 12px; border: 1px solid #d9d9d9; border-radius: 4px; overflow-x: auto;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}
