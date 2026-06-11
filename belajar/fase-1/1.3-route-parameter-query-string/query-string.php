<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// ============================================
// FASE 1.3 - QUERY STRING
// Cara akses nilai dari URL setelah tanda ?
// Contoh: /cari?keyword=php&halaman=2
// ============================================


// ----------------------------------------------------------
// A). QUERY STRING TUNGGAL
//     URL: /cari?keyword=php
// ----------------------------------------------------------
$app->get('/belajar/querystring/cari', function (Request $request, Response $response, $args) {

    // Ambil SEMUA query string sekaligus
    $params = $request->getQueryParams();

    // Ambil nilai 'keyword', alau kosong default ''
    $keyword = $params['keyword'] ?? '';

    $response->getBody()->write("Kamu cari: " . $keyword);
    return $response;
});


// ----------------------------------------------------------
// B). QUERY STRING GANDA
//     URL: /produk?kategori=elektronik&sort=harga&order=asc
// ----------------------------------------------------------
$app->get('/belajar/querystring/produk', function (Request $request, Response $response, $args) {

    $params = $request->getQueryParams();

    $kategori   = $params['kategori'] ?? 'semua';
    $sort       = $params['sort'] ?? 'nama';
    $order      = $params['order'] ?? 'asc';

    $data = [
        'filter' => [
            'kategori'  => $kategori,
            'sort'      => $sort,
            'order'     => $order,
        ],
        'pesan' => "Menampilkan produk kategori: '$kategori', diurutkan by '$sort', ($order)"
    ];

    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});


// ----------------------------------------------------------
// C). PAGINATION (USE CASE NYATA!)
//     URL: /artikel?page=2&limit=5
// ----------------------------------------------------------
$app->get('/belajar/querystring/artikel', function (Request $request, Response $response, $args) {

    $params = $request->getQueryParams();

    $page   = (int) ($params['page'] ?? 1);   // default halaman 1
    $limit  = (int) ($params['limit'] ?? 1);   // default 10 item

    // Hitung offset untuk query database nanti
    $offset = ($page - 1) * $limit;

    $data = [
        'page'     => $page,
        'limit'    => $limit,
        'offset'   => $offset,
        'pesan'    => "Ambil $limit artikel, mulai dari data ke-$offset"
    ];

    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});


// ----------------------------------------------------------
// D). KOMBINASI ROUTE PARAMETER + QUERY STRING
//     URL: /toko/5/produk?sort=harga&order=desc
// ----------------------------------------------------------
$app->get('/belajar/querystring/toko/{tokoId}/produk', function (Request $request, Response $response, $args) {

    // Dari Route Parameter
    $tokoId = $args['tokoId'];

    // Dari Query String
    $params  = $request->getQueryParams();
    $sort    = $params['sort'] ?? 'nama';
    $order   = $params['order'] ?? 'asc';

    $data = [
        'toko_id'   => (int) $tokoId,
        'sort'      => $sort,
        'order'     => $order,
        'pesan'     => "Produk toko #$tokoId, sort by $sort ($order)"
    ];

    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});