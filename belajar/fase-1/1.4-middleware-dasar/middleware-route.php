<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as BuatResponse;

// ============================================
// FASE 1.4 - MIDDLEWARE ROUTE
// Hanya jalan di route yang dipasangi saja
// ============================================

// ----------------------------------------------------------
// ---- Cara bikin Middleware ----
// Middleware itu cuma sebuah function dengan 3 parameter:
//      $request  -> data request yang masuk
//      $handler  -> "lanjutkan ke route berikutnya"
//      $return   -> response yang keluar
// ----------------------------------------------------------


// ----------------------------------------------------------
// ---- Buat Middleware sebagai variable  ----
// Biar bisa dipasang ke route mana aja yang mau
// ----------------------------------------------------------

// Middleware: Cek apakah ada API Key
$cekApiKey = function ($request, $handler) {

    // Ambil header 'X-Api-Key' dari request
    $apiKey = $request->getHeaderLine('X-Api-Key');

    // Kalau kosong atau salah -> tolak!
    if (empty($apiKey) || $apiKey !== 'rahasia123') {

        // Buat response penolakan
        $response = new BuatResponse();
        $response->getBody()->write(json_encode([
            'status'    => 'error',
            'pesan'     => 'API Key tidak valid atau tidak ada!'
        ]));

        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json');
    }

    // Kalau API Key benar -> lanjutkan!
    return $handler->handle($request);
};

// Middleware: Cek apakah request pakai JSON
$cekJson = function ($request, $handler) {

    $contentType = $request->getHeaderLine('Content-Type');


    if (!str_contains($contentType, 'application/json')) {

        $response = new BuatResponse();
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'pesan'  => 'Content-Type harus application/json!'
        ]));

        return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }

    return $handler->handle($request);
};


// ---- Pasang Middleware ke Route Tertentu ----

// Route TANPA Middleware -> bebas diakses
$app->get('/publik', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode([
        'status' => 'ok',
        'pesan' => 'Ini route publik, siapa aja bisa akses!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});


// Route DENGAN 1 Middleware
// Harus kirim header: X-Api-Key: rahasia123
$app->get('/privat', function (Request $request, Response $response, $args) {
    
    $response->getBody()->write(json_encode([
        'status' => 'ok',
        'pesan'  => 'Selamat! API Key kamu valid!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');

})->add($cekApiKey);


// Route DENGAN 2 Middleware sekaligus
// Harus kirim: X-Api-Key + Content-Type: application/json
$app->post('/privat-json', function (Request $request, Response $response, $args) {

    $response->getBody()->write(json_encode([
        'status' => 'ok',
        'pesan'  => 'API Key valid dan Content-Type JSON!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');

})->add($cekJson)->add($cekApiKey); // 2 Middleware, dibaca dari kanan kekiri!