<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.1 - Routing Dasar - PATCH
// ============================================

// ----- PATCH Update (Partial) User -----
$app->patch('/users/{id}', function (Request $request, Response $response, $args) {
    $id     = $args['id'];
    $body   = $request->getParsedBody();

    // Contoh: hanya update email
    $updatedUser = [
        "id"        => $id,
        "email"     => $body['email'] ?? 'email lama'
    ];

    $payload = json_encode([
        "message"   => "User berhasil diupdate sebagian (email)",
        "data"      => $updatedUser
    ]);

    $response->getBody()->write($payload);
    
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});