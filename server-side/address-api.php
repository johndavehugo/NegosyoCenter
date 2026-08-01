<?php

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$code = $_GET['code'] ?? '';
$baseUrl = 'https://psgc.gitlab.io/api';
$refresh = isset($_GET['refresh']);


$cacheDir = __DIR__ . '/../cache';

if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

switch ($action) {

    case 'regions':
        $cacheFile = $cacheDir . '/regions.json';
        

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

    case 'provinces':
        $cacheFile = $cacheDir . '/provinces_' . $code . '.json';
        if (!$refresh && file_exists($cacheFile) && time()-filemtime($cacheFile) < 86400) {
            readfile($cacheFile); exit;
        }
        $json = @file_get_contents("$baseUrl/regions/$code/provinces/");
        if ($json === false) { http_response_code(502); exit; }
        file_put_contents($cacheFile, $json);
        echo $json;
        break;

    case 'cities':
        $parentType = $_GET['parent'] ?? 'province';
        $cacheFile = $cacheDir . '/places_' . $parentType . '_' . $code . '.json';
        
        if (!$refresh && file_exists($cacheFile) && time()-filemtime($cacheFile) < 86400) {
            readfile($cacheFile); exit;
        }

        $suffix = $parentType === 'region' ? "regions/$code" : "provinces/$code";
        $cities = json_decode(@file_get_contents("$baseUrl/$suffix/cities/"), true);
        $municipalities = json_decode(@file_get_contents("$baseUrl/$suffix/municipalities/"), true);

        if ($cities === null || $municipalities === null) { http_response_code(502); exit; }
        $merged = array_merge(
            array_map(fn($c) => $c + ['type' => 'city'], $cities),
            array_map(fn($m) => $m + ['type' => 'municipality'], $municipalities)
        );

        usort($merged, fn($a, $b) => strcmp($a['name'], $b['name']));
        file_put_contents($cacheFile, json_encode($merged, JSON_UNESCAPED_UNICODE));
        echo json_encode($merged, JSON_UNESCAPED_UNICODE);
        break;

    case 'barangays':
        $type = $_GET['type'] ?? 'city';
        $cacheFile = $cacheDir . '/barangays_' . $type . '_' . $code . '.json';

        if (!$refresh && file_exists($cacheFile) && time()-filemtime($cacheFile) < 86400) {
            readfile($cacheFile); exit;
        }

        $plural = $type === 'municipality' ? 'municipalities' : 'cities';
        $json = @file_get_contents("$baseUrl/$plural/$code/barangays/");

        if ($json === false) { http_response_code(502); exit; }

        file_put_contents($cacheFile, $json);
        echo $json;
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}