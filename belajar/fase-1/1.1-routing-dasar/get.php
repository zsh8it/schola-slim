<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.1 - Routing Dasar - GET
// ============================================

// ----- GET Semua User -----
$app->get('/users', function (Request $request, Response $response) {
    // Data user (nanti dari database, sekarang kita buat manual dulu)
    $data = [
        ["id" => 1, "nama" => "Budi", "email" => "budi@gmail.com"],
        ["id" => 1, "nama" => "Ani", "email" => "ani@gmail.com"],
        ["id" => 1, "nama" => "Caca", "email" => "caca@gmail.com"]
    ];

    // Ubah array PHP menjadi JSON
    $payload = json_encode($data);

    // Tuilis JSON ke response
    $response->getBody()->write($payload);

    // Kasih tau browser bahwa ini adalah JSON
    return $response->withHeader('Content-Type', 'application/json');
});

// ----- GET User by ID -----
$app->get('/users/{id}', function (Request $request, Response $response, $args){

    // Ambil ID dari URL
    $id = $args['id'];

    $data = [
        ["id" => 1, "nama" => "Budi", "email" => "budi@gmail.com"],
        ["id" => 1, "nama" => "Ani", "email" => "ani@gmail.com"],
        ["id" => 1, "nama" => "Caca", "email" => "caca@gmail.com"]
    ];

    // Cari user yang ID-nya cocok
    $user = null;
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            $user = $item;
            break;
        }
    }

    // Kalau tidak ketemu
    if ($user === null) {
        $response->getBody()->write(json_encode(["message" => "User tidak ditemukan"]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $payload = json_encode($user);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
});