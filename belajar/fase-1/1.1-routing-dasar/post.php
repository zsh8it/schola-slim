<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.1 - Routing Dasar - POST
// ============================================


// ----- POST Tambah User -----
$app->post('/users', function (Request $request, Response $response) {
    
    $body = $request->getParsedBody();

    $newUser = [
        "id"    => 4,
        "nama"  => $body['nama'],
        "email" => $body['email']
    ];

    $payload = json_encode([
        "message" => "User berhasil ditambahkan",
        "data"    => $newUser
    ]);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});