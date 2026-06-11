<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.4 - MIDDLEWARE GLOBAL
// Jalan di SEMUA route otomatis
// ============================================


// ----------------------------------------------------------
// ---- Cara bikin Middleware ----
// Middleware itu cuma sebuah function dengan 3 parameter:
//      $request  -> data request yang masuk
//      $handler  -> "lanjutkan ke route berikutnya"
//      $return   -> response yang keluar
// ----------------------------------------------------------


// ----------------------------------------------------------
// A). MIDDLEWARE LOGGER SEDERHANA
//     Mencatat setiap request yang masuk
// ----------------------------------------------------------
$app->add(function ($request, $handler) {

    // BEFORE - jalan sebelum route
    $method     = $request->getMethod();            // GET, POST, dll
    $uri        = $request->getUri()->getPath();    // /user/1, /produk, dll
    $waktu      = date('Y-m-d H:i:s');

    echo "[$waktu] Request: $method $uri\n";

    // Lanjutkan ke route (WAJIB ada!)
    $response = $handler->handle($request);

    // AFTER - jalan setelah route selesai
    echo "\n[$waktu] [SELESAI] Response dikirim!";

    return $response;
});


// ----------------------------------------------------------
// B). MIDDLEWARE TAMBAH HEADER OTOMATIS
//     Semua response otomatis dapat header ini
// ----------------------------------------------------------
$app->add(function ($request, $handler) {

    // Lanjut ke route dulu
    $response = $handler->handle($request);

    // AFTER: tambahkan header ke semua response
    return $response
        ->withHeader('X-Powered-By', 'Slim Framework v4')
        ->withHeader('X-App-Version', '1.0.0');
});


// Route untuk test
$app->get('/mw-global', function ($request, $response, $args) {
    $response->getBody()->write("\n\nHalo dari route! Cek header di Thunder Client ya!");
    return $response;
});
