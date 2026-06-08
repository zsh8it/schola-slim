<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.1 - Routing Dasar - DELETE
// ============================================


// ----- DELETE User -----
$app->delete('/users/{id}', function (Request $request, Response $response, $args) {
    $id     = $args['id'];

    // Contoh: proses hapus user (simulasi)
    $payload = json_encode([
        "message" => "User dengan ID $id berhasil dihapus"
    ]);

    $response->getBody()->write($payload);

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});