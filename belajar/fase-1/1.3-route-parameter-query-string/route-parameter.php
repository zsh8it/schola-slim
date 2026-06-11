<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.3 - ROUTE PARAMETER
// Cara akses nilai dari URL langsung
// Contoh: /user/42 -> {id} = 42
// ============================================


// ----------------------------------------------------------
// A). PARAMETER TUNGGAL
//     URL: /user/32
// ----------------------------------------------------------
$app->get('/belajar/routeparameter/user/{id}', function (Request $request, Response $response, $args) {

    $id = $args['id']; // ambil dari args

    $response->getBody()->write("User ID: " . $id);
    return $response;
});


// ----------------------------------------------------------
// B). PARAMETER GANDA
//     URL: /post/5/comment/12
// ----------------------------------------------------------
$app->get('/belajar/routeparamter/post/{postId}/comment/{commentId}', function (Request $request, Response $response, $args) {

    $postId     = $args['postId'];
    $commentId  = $args['commentId'];

    $response->getBody()->write("Post ID : $postId | Comment ID: $commentId");
    return $response;
});


// ----------------------------------------------------------
// C). PARAMETER OPTIONAL
//     URL: /kategori           -> tanpa kategori
//     URL: /kategori/berita    -> dengan kategori
// ----------------------------------------------------------
$app->get('/belajar/routeparamter/kategori[/{nama}]', function (Request $request, Response $response, $args) {
   
    // Kalau tidak diisi, kasih nilai default
    $nama = $args['nama'] ?? 'semua';

    $response->getBody()->write("Kategori: " . $nama);
    return $response;
});


// ----------------------------------------------------------
// D). PARAMETER DENGAN REGEX (VALIDASI TIPE DATA)
//     URL: /produk/99        -> OK (angka)
//     URL: /produk/abc       -> NOT FOUND (bukan angka)
// ----------------------------------------------------------
$app->get('/belajar/routeparameter/produk/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    
    $id = $args['id'];

    $response->getBody()->write("Produk ID: " . $id . " (pasti angka!)");
    return $response;
});


// ----------------------------------------------------------
// E). RETURN JSON (LEBIH REALITSTIS)
//     URL: /belajar/routeparameter/api/user/7
// ----------------------------------------------------------
$app->get('/belajar/routeparameter/api/user/{id}', function (Request $request, Response $response, $args) {

    $id     = $args['id'];

    // Simulasi data user
    $user = [
        'id'     => (int) $id,
        'nama'   => 'Budi Santoso',
        'email'  => 'budi@example.com'
    ];

    $response->getBody()->write(json_encode($user));
    return $response->withHeader('Content-Type', 'application/json');
});
