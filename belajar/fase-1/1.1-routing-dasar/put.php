<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.1 - Routing Dasar - PUT
// ============================================


// ----- PUT Update User -----
$app->put('/users/{id}', function (Request $request, Response $response, $args) {

    $id   = $args['id'];
    $body = $request->getParsedBody();

    // ✅ Cek apakah body null atau tidak
    if (empty($body)) {
        $payload = json_encode(["message" => "Body request kosong atau Content-Type salah"]);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $updatedUser = [
        "id"    => $id,
        "nama"  => $body['nama']  ?? null,
        "email" => $body['email'] ?? null
    ];

    $payload = json_encode([
        "message" => "User berhasil diupdate",
        "data"    => $updatedUser
    ]);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});
