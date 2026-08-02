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

function loadJson($cacheFile, $url, $refresh) {
    if (!$refresh && file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    $json = @file_get_contents($url);
    if ($json === false) return null;
    file_put_contents($cacheFile, $json);
    return json_decode($json, true);
}

function loadRegions($refresh) {
    global $cacheDir, $baseUrl;
    return loadJson($cacheDir . '/regions.json', "$baseUrl/regions/", $refresh);
}

function loadProvinces($code, $refresh) {
    global $cacheDir, $baseUrl;
    return loadJson($cacheDir . '/provinces_' . $code . '.json', "$baseUrl/regions/$code/provinces/", $refresh);
}

function loadPlaces($code, $parentType, $refresh) {
    global $cacheDir, $baseUrl;
    $cacheFile = $cacheDir . '/places_' . $parentType . '_' . $code . '.json';

    if (!$refresh && file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    $suffix = $parentType === 'region' ? "regions/$code" : "provinces/$code";
    $cities = json_decode(@file_get_contents("$baseUrl/$suffix/cities/"), true);
    $municipalities = json_decode(@file_get_contents("$baseUrl/$suffix/municipalities/"), true);

    if ($cities === null || $municipalities === null) return null;

    $merged = array_merge(
        array_map(fn($c) => $c + ['type' => 'city'], $cities),
        array_map(fn($m) => $m + ['type' => 'municipality'], $municipalities)
    );
    usort($merged, fn($a, $b) => strcmp($a['name'], $b['name']));

    file_put_contents($cacheFile, json_encode($merged, JSON_UNESCAPED_UNICODE));
    return $merged;
}

function loadBarangays($code, $type, $refresh) {
    global $cacheDir, $baseUrl;
    $cacheFile = $cacheDir . '/barangays_' . $type . '_' . $code . '.json';
    $plural = $type === 'municipality' ? 'municipalities' : 'cities';
    return loadJson($cacheFile, "$baseUrl/$plural/$code/barangays/", $refresh);
}

switch ($action) {

    case 'regions':
        $regions = loadRegions($refresh);
        if ($regions === null) { http_response_code(502); exit; }
        echo json_encode($regions, JSON_UNESCAPED_UNICODE);
        break;

    case 'provinces':
        $provinces = loadProvinces($code, $refresh);
        if ($provinces === null) { http_response_code(502); exit; }
        echo json_encode($provinces, JSON_UNESCAPED_UNICODE);
        break;

    case 'cities':
        $parentType = $_GET['parent'] ?? 'province';
        $places = loadPlaces($code, $parentType, $refresh);
        if ($places === null) { http_response_code(502); exit; }
        echo json_encode($places, JSON_UNESCAPED_UNICODE);
        break;

    case 'barangays':
        $type = $_GET['type'] ?? 'city';
        $barangays = loadBarangays($code, $type, $refresh);
        if ($barangays === null) { http_response_code(502); exit; }
        echo json_encode($barangays, JSON_UNESCAPED_UNICODE);
        break;

    case 'addresses':
        $regionName   = $_GET['region'] ?? '';
        $provinceName = $_GET['province'] ?? '';
        $cityName     = $_GET['city'] ?? '';
        $barangayName = $_GET['barangay'] ?? '';

        $regions = loadRegions($refresh);
        $region = null;
        if ($regions !== null) {
            foreach ($regions as $r) {
                if (($r['regionName'] ?? '') === $regionName || ($r['name'] ?? '') === $regionName) {
                    $region = $r;
                    break;
                }
            }
        }

        $provinces = []; $places = []; $barangays = [];
        if ($region) {
            $provinces = loadProvinces($region['code'], $refresh) ?: [];

            $province = null;
            foreach ($provinces as $p) {
                if ($p['name'] === $provinceName) { $province = $p; break; }
            }

            $parentCode = $province ? $province['code'] : $region['code'];
            $parentType = $province ? 'province' : 'region';
            $places = loadPlaces($parentCode, $parentType, $refresh) ?: [];

            $city = null;
            foreach ($places as $c) {
                if ($c['name'] === $cityName) { $city = $c; break; }
            }
            if ($city) {
                $barangays = loadBarangays($city['code'], $city['type'], $refresh) ?: [];
            }
        }

        echo json_encode([
            'status' => 'success',
            'regions'   => $regions ?: [],
            'provinces' => $provinces,
            'places'    => $places,
            'barangays' => $barangays,
        ], JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}