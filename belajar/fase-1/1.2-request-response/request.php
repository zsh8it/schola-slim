<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.2 - REQUEST
// Belajar cara baca semua data yang masuk dari client
// ============================================


// ----------------------------------------------------------
// A). BACA METHOD & URI
//     Tau client pakai method apa dan akses URL mana
// ----------------------------------------------------------
$app->get('/belajar/request/info', function (Request $request, Response $response, $args){

    $method     = $request->getMethod();            // GET, POST, PUT, PATCH, DELETE, dll
    $uri        = $request->getUri();               // Object URI lengkap
    $path       = $request->getUri()->getPath();    // /belajar/request/info

    $data = "Method : $method\n"
          . "URI    : $uri\n"
          . "Path   : $path\n";

    $response->getBody()->write($data);
    return $response;
});


// ----------------------------------------------------------
// B). BACA QUERY STRING
//     URL: /belajar/request/query?nama=Budi&umur=25
//     Cara baca parameter setelah tanda "?"
// ----------------------------------------------------------
$app->get('/belajar/request/query', function (Request $request, Response $response, $args) {

    // Ambl SEMUA query params sekaligus (bentuknya array)
    $params = $request->getQueryParams();

    // Ambil SATU query param tertentu
    $nama   = $params['nama'] ?? 'tidak ada'; // ?? artinya kalau kosong, pakai default
    $umur   = $params['umur'] ?? 'tidak ada'; 

    $data   = "Semua params : " . print_r($params, true) . "\n"
            . "Nama         : $nama\n"
            . "Umur         : $umur\n";

    $response->getBody()->write($data);
    return $response;
});


// ----------------------------------------------------------
// C). BACA BODY (FORM DATA / JSON)
//     Data yang dikirim lewat body request (POST, PUT, dll)
//     Butuh $app->addBodyParsingMiddleWare()
// ----------------------------------------------------------
$app->post('/belajar/request/body', function (Request $request, Response $response, $args) {

    // Ambil semua data dari body (form-data / x-www-form-urlencoded / JSON)
    $body = $request->getParsedBody();

    // Ambil field tertentu
    $nama   = $body['nama'] ?? 'tidak ada';
    $email  = $body['email'] ?? 'tidak ada';

    $data   = "Semua Body : " . print_r($body, true) . "\n"
            . "Nama       : $nama\n"
            . "Email      : $email\n";

    $response->getBody()->write($data);
    return $response;
});



// ----------------------------------------------------------
// D). BACA HEADER
//     Info tambahan yang dikirim client (Content-Type, Token, dll)
// ----------------------------------------------------------
$app->get('/belajar/request/header', function (Request $request, Response $response, $args) {

    // Ambil SEMUA header (bentuknya array of array)
    $allHeaders = $request->getHeaders();

    // Ambil SATU header tertentu
    // getHeaderLine() (langsung string, lebih praktis)
    $contentType    = $request->getHeaderLine('Content-Type');
    $userAgent      = $request->getHeaderLine('User-Agent');
    $accept         = $request->getHeaderLine('Accept');

    $data = "Content-Type   : $contentType\n"
          . "User-Agent     : $userAgent\n"
          . "Accept         : $accept\n"
          . "=== SEMUA HEADER ===\n"
          . print_r($allHeaders, true);
    
    $response->getBody()->write($data);
    return $response;
});


// ----------------------------------------------------------
// E). BACA RAW BODY
//     Kalau mau baca body mentah (misalnya JSON string)
//     Berguna untuk debug atau custom parsing
// ----------------------------------------------------------
$app->post('/belajar/request/raw', function ( Request $request, Response $response, $args) {

    // Ambil hasil parse dari middleware
    $parsed = $request->getParsedBody();

    // Baca body mentah sebagai string
    // $rawBody = (string) $request->getBody(); // error karna sudah pakai addBodyParsingMiddleware()
    $rawBody = json_encode($parsed);

    // Coba decode kalau isinya  JSON
    $decoded = json_decode($rawBody, true);
    var_dump($rawBody);
    var_dump($decoded);

    $data = "Raw Body   : $rawBody\n\n"
          . "Decoded    : " . print_r($decoded, true);

    $response->getBody()->write($data);
    return $response;
});


// ----------------------------------------------------------
// F). CEK APAKAH HEADER ADA 
//     Validasi sebelum pakai headernya
// ----------------------------------------------------------
$app->post('/belajar/request/cek-header', function ( Request $request, Response $response, $args) {

    // hasHeader() -> true/false
    $adaAuth        = $request->hasHeader('Authorization');
    $adaContentType = $request->hasHeader('Content-Type');

    $data = "Ada Authorization   :" . ($adaAuth ? 'YA' : 'TIDAK') ."\n"
          . "Ada Content-Type    :" . ($adaContentType ? 'YA' : 'TIDAK') . "\n";

    $response->getBody()->write($data);
    return $response;
});