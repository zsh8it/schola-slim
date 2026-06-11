<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as BuatResponse;

// ============================================
// FASE 1.4 - MIDDLEWARE GROUP
// Satu middleware untuk banyak route sekaligus
// ============================================

// ----------------------------------------------------------
// ---- Cara bikin Middleware ----
// Middleware itu cuma sebuah function dengan 3 parameter:
//      $request  -> data request yang masuk
//      $handler  -> "lanjutkan ke route berikutnya"
//      $return   -> response yang keluar
// ----------------------------------------------------------


// Middleware: Simulasi cek login 
$cekLogin = function ($request, $handler) {
    
    // Simulasi: cek header 'X-User-Token'
    $token = $request->getHeaderLine('X-User-Token');

    if (empty($token) || $token !== 'user-sudah-login') {
        $response = new BuatResponse();
        $response->getBody()->write(json_encode([
            'status'    => 'error',
            'pesan'     => 'Kamu belum login!'.$token
        ]));
    
        return $response
            ->withStatus(403)
            ->withHeader('Content-Type', 'application/json');    
    }

    return $handler->handle($request);
};


// ----Group Rote dengan Middleware
// Semua route di dalam group ini kena middleware $cekLogin

$app->group('/dashboard', function ($group) {

    // GET /dashboard
    $group->get('', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode([
            'status'    => 'ok',
            'halaman'   => 'Dashboard Utama'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // GET /dashboard/profil
    $group->get('/profil', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode([
            'status'    => 'ok',
            'halaman'   => 'Profil User'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // GET /dashboard/setting
    $group->get('/setting', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode([
            'status'    => 'ok',
            'halaman'   => 'Setting Akun'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

})->add($cekLogin); // semua route dalam group kena middleware ini!