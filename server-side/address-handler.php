<?php

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$psgcId = $_GET['psgc_id'] ?? '';
$refresh = isset($_GET['refresh']);

$baseUrl = 'https://vamosmobile.app/api/addresses';
$cacheDir = __DIR__ . '/../cache';

if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

function fetchList(string $cacheFile, string $url, bool $refresh): ?array
{
    if (!$refresh && file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        $json = file_get_contents($cacheFile);
    } else {
        $json = @file_get_contents($url);
        if ($json === false)
            return null;
        file_put_contents($cacheFile, $json);
    }
    return json_decode($json, true)['data'] ?? null;
}

function normalize(array $item): array
{
    return [
        'psgc_id' => $item['psgc_id'] ?? '',
        'name' => $item['name'] ?? '',
        'geographical_level' => $item['geographical_level'] ?? '',
    ];
}

function filterByPrefix(array $items, string $parentPsgcId, int $len): array
{
    $prefix = substr($parentPsgcId, 0, $len);

    return array_values(array_filter($items, function (array $item) use ($prefix, $len): bool {
        return substr($item['psgc_id'], 0, $len) === $prefix;
    }));
}

function placesByProvince(array $places, array $province): array
{
    $provCorr = substr($province['correspondence_code'] ?? '', 0, 4);
    $psgc5 = substr($province['psgc_id'] ?? '', 0, 5);

    return array_values(array_filter($places, function (array $item) use ($provCorr, $psgc5): bool {
        $corr4 = substr($item['correspondence_code'] ?? '', 0, 4);

        return $corr4 === $provCorr
            || ($provCorr === '' && substr($item['psgc_id'] ?? '', 0, 5) === $psgc5);
    }));
}

function findByName(array $items, string $name): ?array
{
    $needle = strtolower(trim($name));

    foreach ($items as $item) {
        if (strtolower(trim($item['name'])) === $needle) {
            return $item;
        }
    }
    return null;
}

function loadRegions(bool $refresh): ?array
{
    global $cacheDir, $baseUrl;
    return fetchList($cacheDir . '/regions.json', "$baseUrl/region/", $refresh);
}

function loadProvinces(bool $refresh): ?array
{
    global $cacheDir, $baseUrl;
    return fetchList($cacheDir . '/provinces.json', "$baseUrl/province/", $refresh);
}

function loadPlaces(bool $refresh): ?array
{
    global $cacheDir, $baseUrl;
    return fetchList($cacheDir . '/municipality-city.json', "$baseUrl/municipality-city/", $refresh);
}

function loadBarangays(bool $refresh): ?array
{
    global $cacheDir, $baseUrl;
    return fetchList($cacheDir . '/barangays.json', "$baseUrl/barangay/", $refresh);
}

function toNormalized(array $items): array
{
    return array_map('normalize', $items);
}

function barangaysOf(array $barangays, string $parentPsgcId): array
{
    return array_values(array_filter($barangays, function (array $item) use ($parentPsgcId): bool {
        return substr($item['psgc_id'], 0, 7) === substr($parentPsgcId, 0, 7)
            && $item['psgc_id'] !== $parentPsgcId;
    }));
}

switch ($action) {

    case 'regions':
        $regions = loadRegions($refresh);
        if ($regions === null) {
            http_response_code(502);
            exit;
        }
        echo json_encode(toNormalized($regions), JSON_UNESCAPED_UNICODE);
        break;

    case 'provinces':
        $provinces = loadProvinces($refresh);
        if ($provinces === null) {
            http_response_code(502);
            exit;
        }
        echo json_encode(filterByPrefix(toNormalized($provinces), $psgcId, 2), JSON_UNESCAPED_UNICODE);
        break;

    case 'cities':
        $parent = $_GET['parent'] ?? 'province';
        $places = loadPlaces($refresh);
        if ($places === null) {
            http_response_code(502);
            exit;
        }

        if ($parent === 'region') {
            $items = filterByPrefix($places, $psgcId, 2);
        } else {
            $prov = null;
            foreach (loadProvinces($refresh) ?? [] as $p) {
                if (($p['psgc_id'] ?? '') === $psgcId) {
                    $prov = $p;
                    break;
                }
            }
            if ($prov) {
                $items = placesByProvince($places, $prov);
            } else {
                $items = [];
            }
        }

        echo json_encode(toNormalized($items), JSON_UNESCAPED_UNICODE);
        break;

    case 'barangays':
        $barangays = loadBarangays($refresh);
        if ($barangays === null) {
            http_response_code(502);
            exit;
        }
        echo json_encode(toNormalized(barangaysOf($barangays, $psgcId)), JSON_UNESCAPED_UNICODE);
        break;

    case 'resolve':
        $regions = loadRegions($refresh);
        $provinces = loadProvinces($refresh);
        $places = loadPlaces($refresh);
        $barangays = loadBarangays($refresh);

        if ($regions === null || $provinces === null || $places === null || $barangays === null) {
            http_response_code(502);
            exit;
        }

        $region = findByName(toNormalized($regions), $_GET['region'] ?? '');
        $province = $city = $barangay = null;

        $provinceOptions = [];
        $cityOptions = [];
        $barangayOptions = [];

        if ($region) {
            $provinceOptions = filterByPrefix(toNormalized($provinces), $region['psgc_id'], 2);
            $province = findByName($provinceOptions, $_GET['province'] ?? '');
        }

        if ($province) {
            $cityOptions = toNormalized(placesByProvince($places, $province));
            $city = findByName($cityOptions, $_GET['city'] ?? '');
        }

        if ($city) {
            $barangayOptions = toNormalized(barangaysOf($barangays, $city['psgc_id']));
            $barangay = findByName($barangayOptions, $_GET['barangay'] ?? '');
        }

        echo json_encode([
            'region' => $region,
            'provinces' => $provinceOptions,
            'province' => $province,
            'places' => $cityOptions,
            'city' => $city,
            'barangays' => $barangayOptions,
            'barangay' => $barangay,
        ], JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}