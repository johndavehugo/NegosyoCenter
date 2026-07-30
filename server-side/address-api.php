<?php

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$code = $_GET['code'] ?? '';
$baseUrl = 'https://psgc.gitlab.io/api';

$cacheDir = __DIR__ . '/../cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

switch ($action) {

    case 'regions':
        $cacheFile = $cacheDir . '/regions.json';
        $refresh = isset($_GET['refresh']);

        if (!$refresh && file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
            readfile($cacheFile);
            exit;
        }

        $json = @file_get_contents("$baseUrl/regions/");
        if ($json === false) {
            http_response_code(502);
            echo json_encode(['error' => 'Failed to connect to PSGC API']);
            exit;
        }

        file_put_contents($cacheFile, $json);
        echo $json;
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}