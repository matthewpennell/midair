<?php

/**
 * Mock ATproto XRPC server for local development.
 *
 * Simulates the com.atproto.repo.createRecord endpoint so the ATproto
 * integration in Midair\Blog\Controllers\Import can be tested without
 * publishing anything to bsky.social.
 *
 * Usage:
 *   php -S localhost:8080 scripts/mock_atproto_server.php
 *
 * Then set in .env:
 *   atproto.endpoint = http://localhost:8080
 *
 * Behaviour:
 *   - POST /xrpc/com.atproto.server.createSession returns 200 and a fake
 *     {accessJwt, did} response when the body has both "identifier" and
 *     "password" fields; 400 otherwise.
 *   - POST /xrpc/com.atproto.repo.createRecord with an Authorization header
 *     returns 200 and a fake {uri, cid} response.
 *   - POST /xrpc/com.atproto.repo.createRecord with
 *     "Authorization: Bearer mock-expired" returns 401 (to test the expired
 *     token path), and without any Authorization header returns 401 as well.
 *   - Every request (headers + body) is logged to the console and appended
 *     to scripts/atproto_mock.log so the exact payload can be inspected.
 *
 * To simulate a different failure (e.g. 422), set MOCK_HTTP_CODE before
 * starting the server, e.g.:
 *   $env:MOCK_HTTP_CODE = 422; php -S localhost:8080 scripts/mock_atproto_server.php
 */

// Router script mode: output here goes to the terminal, not the client.
$logFile = __DIR__ . '/atproto_mock.log';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $_SERVER['REQUEST_URI'] ?? '/';
$body = file_get_contents('php://input');
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

// Mask the JWT so it doesn't sit in the log verbatim.
$authMasked = '';
if (preg_match('/^Bearer\s+(\S{0,12})\S*$/', $authHeader, $m)) {
    $authMasked = 'Bearer ' . ($m[1] ?: '') . '...';
}

$entry = sprintf(
    "[%s] %s %s | Authorization: %s | Body: %s\n",
    date('c'),
    $method,
    $path,
    $authMasked !== '' ? $authMasked : '(missing)',
    $body
);
fwrite(STDOUT, $entry);
file_put_contents($logFile, $entry, FILE_APPEND);

header('Content-Type: application/json');

// Simulate an explicit failure code if requested.
$mockCode = (int) ($_SERVER['MOCK_HTTP_CODE'] ?? getenv('MOCK_HTTP_CODE') ?: 200);

if ($method === 'POST' && $path === '/xrpc/com.atproto.server.createSession') {

    $req = json_decode($body, true);

    if (empty($req['identifier']) || empty($req['password'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'InvalidRequest',
            'message' => 'Request requires "identifier" and "password" fields.',
        ]);
        exit;
    }

    echo json_encode([
        'did' => 'did:plc:mockmockmockmockmockmock',
        'handle' => 'mock.bsky.social',
        'accessJwt' => 'mock-access-jwt',
        'refreshJwt' => 'mock-refresh-jwt',
        'active' => true,
    ]);

} elseif ($method === 'POST' && $path === '/xrpc/com.atproto.repo.createRecord') {

    if ($mockCode !== 200) {
        http_response_code($mockCode);
        echo json_encode([
            'error' => 'Mock simulated error',
            'message' => 'Simulated ' . $mockCode . ' response (MOCK_HTTP_CODE)',
        ]);
        exit;
    }

    if ($authHeader === '') {
        http_response_code(401);
        echo json_encode([
            'error' => 'Unauthorized',
            'message' => 'Missing Authorization header.',
        ]);
        exit;
    }

    if ($authHeader === 'Bearer mock-expired') {
        http_response_code(401);
        echo json_encode([
            'error' => 'ExpiredToken',
            'message' => 'Access JWT expired.',
        ]);
        exit;
    }

    // Generate a plausible ATproto URI. Real rkeys are base32; this is close enough
    // for the Import controller, which only stores the URI in the database.
    $rkey = strtolower(substr(strtr(base64_encode(random_bytes(6)), '+/', '01'), 0, 11));
    $uri = 'at://did:plc:mockmockmockmockmockmock/site.standard.document/' . $rkey;

    echo json_encode([
        'uri' => $uri,
        'cid' => 'bafkmock' . $rkey . 'mockmockmockmockmock',
    ]);

} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'NotFound',
        'message' => 'Unknown endpoint: ' . $method . ' ' . $path,
    ]);
}
