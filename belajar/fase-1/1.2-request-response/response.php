<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.2 - RESPONSE
// Belajar cara kirim berbagai jenis response ke client
// ============================================

// ----------------------------------------------------------
// A). RESPONSE TEKS BIASA
//     Paling simple, langsung tulis teks
// ----------------------------------------------------------
$app->get('/belajar/response/teks', function (Request $request, Response $response, $args) {

    $response->getBody()->write("Halo! Ini response teks biasa.");

    return $response;

});


// ----------------------------------------------------------
// B). RESPONSE DENGAN STATUS CODE
//     Kasih tau client: sukses, error, dll
//     Default status code = 200
// ----------------------------------------------------------
$app->get('/belajar/response/status', function (Request $request, Response $response, $args) {

    $response->getBody()->write("Resource berhasil dibuat!");

    // withStatus (kode, pesan_opsional)
    return $response->withStatus(201, 'Created');
});



// ----------------------------------------------------------
// C). RESPONSE JSON
//     Format paling umum untuk API
//     Harus set header Content-Type: application/json
// ----------------------------------------------------------
$app->get('/belajar/response/json', function (Request $request, Response $response, $args) {
    
    $data = [
        'status' => 'sukses',
        'pesan' => 'Halo dari Slim!',
        'data' => [
            'nama' => 'Budi',
            'umur' => 25,
            'kota' => 'Jakarta'
        ]
    ];

    // Encode array ke JSON string
    $jsonString = json_encode($data);

    // Tulis ke body
    $response->getBody()->write($jsonString);

    // Set header Content-Type (kasih tau client ini JSON)
    return $response->withHeader('Content-Type', 'application/json');
});


// ----------------------------------------------------------
// D). RESPONSE DENGAN CUSTOM HEADER
//     Tambah header sendiri ke response
// ----------------------------------------------------------
$app->get('/belajar/response/header', function (Request $request, Response $response, $args) {

    $response->getBody()->write("Cek header di browser/Postman ya!");

    return $response
        ->withHeader('Content-Type', 'text/plain')
        ->withHeader('X-Custom-Header', 'BelajarSlim') // header buatan sendiri
        ->withHeader('X-Powered-By', 'Slim Framework v4')
        ->withStatus(200);
        // withHeader() bisa di-chain terus pakai ->
});


// ----------------------------------------------------------
// E). RESPONSE REDIRECT
//     Arahkan client ke URL lain
// ----------------------------------------------------------
$app->get('/belajar/response/redirect', function (Request $request, Response $response, $args) {

    // 302 = temporary redirect (paling umum)
    // 301 = permanent redirect
    return $response
        ->withHeader('Location', '/belajar/response/teks')
        ->withStatus(302);
});


// ----------------------------------------------------------
// F). RESPONSE BERDASARKAN KONDISI
//     Kirim response berbeda tergantung situasi
// ----------------------------------------------------------
$app->get('/belajar/response/kondisi', function (Request $request, Response $response, $args) {

    $params = $request->getQueryParams();
    $role   = $params['role'] ?? '';

    // Kondisi 1: role admin
    if ($role === 'admin') {
        $data = ['status' => 'ok', 'pesan' => 'Selamat datang, Admin!', 'akses' => 'FULL'];
        
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    // Kondisi 2: role user biasa
    if($role === 'user') {
        $data = ['status' => 'ok', 'pesan' => 'Selamat datang, User!', 'akses' => 'TERBATAS'];
        
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    // Kondisi 3: role tidak dikenal → error 403
    $data = ['status' => 'error', 'pesan' => 'Akses ditolak! Role tidak valid.'];
    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(403);
});


// ----------------------------------------------------------
// G). GABUNGAN REQUEST + RESPONSE
//     Baca data dari request, lalu kirim response yang sesuai
//     Ini pola yang paling sering kamu pakai di dunia nyata!
// ----------------------------------------------------------
$app->post('/belajar/response/gabungan', function (Request $request, Response $response, $args) {

    $body   = $request->getParsedBody();
    $nama   = $body['nama'] ?? null;
    $email  = $body['email'] ?? null;

    // Valdiasi sederhana 
    if (empty($nama) || empty($email)) {
        $data = [
            'status' => 'error',
            'pesan'  => 'Nama dan email wajib diisi!'
        ];

        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400); // 400 = Bad Request
    }

    // Kalau data lengkap (success)
    $data = [
        'status' => 'sukses',
        'pesan' => 'Data diterima',
        'data' => [
            'nama'  => $nama,
            'email' => $email
        ]
    ];

    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});
