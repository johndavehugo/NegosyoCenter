<?php
require_once __DIR__ . '/../config/db_connect.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ============================================================
// ECONOMIC MAP ACTIONS
// ============================================================

// Known coordinates of the 18 barangays of San Carlos City,
// Negros Occidental (approx. centroids from PhilAtlas).
$BARANGAY_COORDS = [
    'Bagonbon'           => [10.5820, 123.3989],
    'Barangay I'         => [10.4939, 123.4273],
    'Barangay II'        => [10.4842, 123.4111],
    'Barangay III'       => [10.4844, 123.4236],
    'Barangay IV'        => [10.4826, 123.4172],
    'Barangay V'         => [10.4792, 123.4127],
    'Barangay VI'        => [10.4800, 123.4222],
    'Buluangan'          => [10.3874, 123.3376],
    'Codcod'             => [10.4574, 123.2173],
    'Ermita'             => [10.4435, 123.4186],
    'Guadalupe'          => [10.4541, 123.3696],
    'Nataban'            => [10.4973, 123.3049],
    'Palampas'           => [10.5135, 123.4106],
    'Prosperidad'        => [10.5122, 123.2785],
    'Punao'              => [10.5305, 123.4329],
    'Quezon'             => [10.4360, 123.2604],
    'Rizal'              => [10.4970, 123.3599],
    'San Juan'           => [10.4627, 123.4398],
];

// LGU-assessed hazard level per barangay (1=Low, 2=Moderate,
// 3=High, 4=Critical). Based on proximity to the coast, river
// systems and low-lying/flood-prone terrain. Adjust these values
// to match the official City DRRM hazard maps.
$BARANGAY_HAZARDS = [
    'Barangay I'    => ['level' => 4, 'label' => 'Critical'],
    'Barangay II'   => ['level' => 4, 'label' => 'Critical'],
    'Barangay III'  => ['level' => 4, 'label' => 'Critical'],
    'Barangay IV'   => ['level' => 3, 'label' => 'High'],
    'Barangay V'    => ['level' => 3, 'label' => 'High'],
    'Barangay VI'   => ['level' => 3, 'label' => 'High'],
    'Bagonbon'      => ['level' => 2, 'label' => 'Moderate'],
    'Buluangan'     => ['level' => 3, 'label' => 'High'],
    'Codcod'        => ['level' => 2, 'label' => 'Moderate'],
    'Ermita'        => ['level' => 2, 'label' => 'Moderate'],
    'Guadalupe'     => ['level' => 3, 'label' => 'High'],
    'Nataban'       => ['level' => 2, 'label' => 'Moderate'],
    'Palampas'      => ['level' => 2, 'label' => 'Moderate'],
    'Prosperidad'   => ['level' => 2, 'label' => 'Moderate'],
    'Punao'         => ['level' => 3, 'label' => 'High'],
    'Quezon'        => ['level' => 2, 'label' => 'Moderate'],
    'Rizal'         => ['level' => 3, 'label' => 'High'],
    'San Juan'      => ['level' => 2, 'label' => 'Moderate'],
];

// Reference data for the Economic Opportunity Map.
// - population  : 2020 census (PhilAtlas)
// - tourism     : 1..4  tourism/eco-tourism potential (coastal,
//                 island & natural attractions)
// - agriculture : 1..4  agricultural production potential (land
//                 area, crop suitability)
// - infrastructure: 1..4  existing infrastructure / services level
// All values are LGU-adjustable — update to match City plans.
$BARANGAY_OPPORTUNITY = [
    'Bagonbon'    => ['population' => 5784,  'tourism' => 1, 'agriculture' => 4, 'infrastructure' => 2],
    'Barangay I'  => ['population' => 10616, 'tourism' => 3, 'agriculture' => 1, 'infrastructure' => 4],
    'Barangay II' => ['population' => 6488,  'tourism' => 3, 'agriculture' => 1, 'infrastructure' => 4],
    'Barangay III'=> ['population' => 3201,  'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 4],
    'Barangay IV' => ['population' => 863,   'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 3],
    'Barangay V'  => ['population' => 7185,  'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 3],
    'Barangay VI' => ['population' => 5364,  'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 3],
    'Buluangan'   => ['population' => 10962, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
    'Codcod'      => ['population' => 14234, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
    'Ermita'      => ['population' => 2157,  'tourism' => 4, 'agriculture' => 2, 'infrastructure' => 1],
    'Guadalupe'   => ['population' => 10746, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
    'Nataban'     => ['population' => 3816,  'tourism' => 1, 'agriculture' => 3, 'infrastructure' => 1],
    'Palampas'    => ['population' => 9345,  'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
    'Prosperidad' => ['population' => 5769,  'tourism' => 1, 'agriculture' => 3, 'infrastructure' => 1],
    'Punao'       => ['population' => 6084,  'tourism' => 3, 'agriculture' => 3, 'infrastructure' => 2],
    'Quezon'      => ['population' => 10596, 'tourism' => 1, 'agriculture' => 4, 'infrastructure' => 2],
    'Rizal'       => ['population' => 16775, 'tourism' => 2, 'agriculture' => 2, 'infrastructure' => 3],
    'San Juan'    => ['population' => 2665,  'tourism' => 4, 'agriculture' => 2, 'infrastructure' => 1],
];

// ── categorize_msme_industry ────────────────────────────────
// Maps a line_of_industry value to one of the eight MSME
// distribution categories used by the Economic Map.
// ─────────────────────────────────────────────────────────────
function categorize_msme_industry($line)
{
    $line = strtoupper(trim((string) $line));
    if ($line === '') {
        return 'Other services';
    }

    $rules = [
        'Retail'              => ['WHOLESALE', 'RETAIL', 'SARI-SARI', 'STORE', 'TRADE'],
        'Food services'       => ['FOOD SERVICE', 'FOODS', 'EATERY', 'CAFETERIA', 'HOTELS', 'RESTAURANT'],
        'Manufacturing'       => ['MANUFACTUR', 'FACTORY', 'FABRICATION', 'PROCESSING', 'GARMENT', 'PRODUCTION', 'MILL'],
        'Agriculture-related' => ['AGRICULTUR', 'FARM', 'FISHING', 'FISHERY', 'LIVESTOCK', 'POULTRY', 'CROPS', 'PLANTATION'],
        'Transportation'      => ['TRANSPORT', 'STORAGE', 'COMMUNICATION', 'LOGISTIC', 'COURIER', 'SHIPPING'],
        'Tourism'             => ['TOURISM', 'TOURIST', 'RESORT', 'TRAVEL', 'HOSPITALITY'],
        'Construction'        => ['CONSTRUCTION', 'BUILDING', 'CONTRACTOR', 'CIVIL WORKS'],
    ];

    foreach ($rules as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($line, $keyword) !== false) {
                return $category;
            }
        }
    }

    return 'Other services';
}

// ── SCIMS (vamosmobile.app) helpers ─────────────────────────
// The Economic Map now uses the live SCIMS registry as its
// primary source of business data:
//   GET https://vamosmobile.app/api/juridical/business
// Responses are cached to disk for 6 hours to avoid hammering
// the external API on every page load. Pass &refresh=1 to force
// a refetch. If the API is unreachable, each endpoint falls back
// to the local NCIMS database.
// ─────────────────────────────────────────────────────────────
function scims_business_list($refresh)
{
    $cacheDir  = __DIR__ . '/../cache';
    $cacheFile = $cacheDir . '/scims_businesses.json';
    $ttl       = 21600; // 6 hours

    if (!$refresh && is_file($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (is_array($data) && count($data) > 100) {
            return $data;
        }
    }

    $ctx = stream_context_create(['http' => ['timeout' => 60]]);
    $raw = @file_get_contents('https://vamosmobile.app/api/juridical/business', false, $ctx);
    if (!$raw) {
        return false;
    }
    $rows = json_decode($raw, true)['data'] ?? null;
    if (!is_array($rows) || count($rows) < 100) {
        return false;
    }

    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0775, true);
    }
    @file_put_contents($cacheFile, json_encode($rows));

    return $rows;
}

// Aggregates the raw SCIMS list into per-barangay business counts,
// new-registration counts and sector (category) counts. Records that
// are clearly outside San Carlos City are excluded.
function scims_barangay_aggregate($refresh)
{
    global $BARANGAY_COORDS;

    $rows = scims_business_list($refresh);
    if ($rows === false) {
        return false;
    }

    $byBarangay  = [];
    $citySectors = [];

    foreach ($rows as $r) {
        $city = trim((string) ($r['juri_city'] ?? ''));
        $b    = trim((string) ($r['juri_barangay'] ?? ''));

        // keep only San Carlos City businesses
        if (stripos($city, 'San Carlos') === false && !isset($BARANGAY_COORDS[$b])) {
            continue;
        }
        if ($b === '') {
            $b = 'Unspecified';
        }

        $cat   = categorize_msme_industry($r['line_of_industry'] ?? '');
        $isNew = strtoupper(trim((string) ($r['status'] ?? ''))) === 'NEW';
        $citySectors[$cat] = true;

        if (!isset($byBarangay[$b])) {
            $byBarangay[$b] = [
                'barangay' => $b,
                'total'    => 0,
                'new'      => 0,
                'sectors'  => [],
            ];
        }
        $byBarangay[$b]['total']++;
        if ($isNew) {
            $byBarangay[$b]['new']++;
        }
        $byBarangay[$b]['sectors'][$cat] = ($byBarangay[$b]['sectors'][$cat] ?? 0) + 1;
    }

    return ['byBarangay' => $byBarangay, 'citySectors' => $citySectors];
}

// ── calamity_damages ────────────────────────────────────────
// Historical damage per barangay, sourced from this same
// handler's risk_damage endpoint (which queries the Calamity
// Monitoring tables). Falls back to a direct query if the
// endpoint is unreachable.
// Returns: array of barangay => ['affected' => int, 'damage' => float]
// ─────────────────────────────────────────────────────────────
function calamity_damages($con, $calamityId = 0, &$calamities = null, &$calamityName = null)
{
    $damages     = [];
    $calamities  = [];
    $calamityName = 'All calamities';

    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
               . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $basePath = dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '')); // /NegosyoCenter
    $calUrl = $baseUrl . $basePath . '/server-side/economic-map-handler.php?action=risk_damage&calamity_id=' . $calamityId;
    $calRaw = @file_get_contents($calUrl, false, stream_context_create(['http' => ['timeout' => 5]]));
    $calJson = $calRaw ? json_decode($calRaw, true) : null;
    if (is_array($calJson) && ($calJson['status'] ?? '') === 'success') {
        $calamities = $calJson['calamities'] ?? [];
        $damages    = $calJson['damage'] ?? [];
        foreach ($calamities as $c) {
            if ((int) ($c['id'] ?? 0) === $calamityId) {
                $calamityName = $c['name'] ?? $calamityName;
                break;
            }
        }
    }

    if (empty($damages)) {
        // Fallback: direct query (same tables the controller uses)
        $stmtDamage = $con->query(
            "SELECT
                COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                COUNT(DISTINCT ci.juridical_id) AS affected_count,
                COALESCE(SUM(ci.estimated_cost_of_damages), 0) AS total_damage
             FROM (
                SELECT ci.juridical_id, ci.estimated_cost_of_damages
                FROM calamity_incidents ci
                UNION ALL
                SELECT ib.juridical_id, ib.estimated_cost_of_damages
                FROM calamity_incident_businesses ib
             ) ci
             JOIN juridicals j ON j.id = ci.juridical_id
             LEFT JOIN addresses a ON j.address_id = a.id
             GROUP BY a.barangay"
        );
        foreach ($stmtDamage->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $damages[$r['barangay']] = [
                'affected' => (int) $r['affected_count'],
                'damage'   => (float) $r['total_damage'],
            ];
        }
    }

    return $damages;
}

// ── economic_hotspots ───────────────────────────────────────
// Business concentration per barangay (hotspot score = number
// of registered MSMEs). GET ?action=economic_hotspots[&refresh=1]
// ─────────────────────────────────────────────────────────────
if ($action === 'economic_hotspots') {
    global $BARANGAY_COORDS;
    $refresh = isset($_GET['refresh']);
    try {
        $mapped   = [];
        $unmapped = [];
        $total    = 0;
        $source   = 'scims';

        $agg = scims_barangay_aggregate($refresh);
        if ($agg === false) {
            // Fallback to the local NCIMS database
            $source = 'local';
            $stmt = $con->query(
                "SELECT
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    COUNT(*) AS cnt
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 GROUP BY a.barangay"
            );
            $agg = ['byBarangay' => [], 'citySectors' => []];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $b = $r['barangay'];
                $agg['byBarangay'][$b] = [
                    'barangay' => $b,
                    'total'    => (int) $r['cnt'],
                    'new'      => 0,
                    'sectors'  => [],
                ];
            }
        }

        foreach ($agg['byBarangay'] as $b => $info) {
            $total += $info['total'];
            if (isset($BARANGAY_COORDS[$b])) {
                $mapped[] = [
                    'barangay' => $b,
                    'count'    => $info['total'],
                    'lat'      => $BARANGAY_COORDS[$b][0],
                    'lng'      => $BARANGAY_COORDS[$b][1],
                ];
            } else {
                $unmapped[] = [
                    'barangay' => $b,
                    'count'    => $info['total'],
                ];
            }
        }

        usort($mapped, function ($a, $b) { return $b['count'] <=> $a['count']; });
        usort($unmapped, function ($a, $b) { return $b['count'] <=> $a['count']; });

        echo json_encode([
            'status'   => 'success',
            'source'   => $source,
            'total'    => $total,
            'mapped'   => $mapped,
            'unmapped' => $unmapped,
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── msme_distribution ───────────────────────────────────────
// Per-barangay breakdown of MSMEs by the eight distribution
// categories. GET ?action=msme_distribution[&refresh=1]
// ─────────────────────────────────────────────────────────────
if ($action === 'msme_distribution') {
    global $BARANGAY_COORDS;
    $refresh = isset($_GET['refresh']);
    try {
        $categories = [
            'Retail'              => 0,
            'Food services'       => 0,
            'Manufacturing'       => 0,
            'Agriculture-related' => 0,
            'Transportation'      => 0,
            'Tourism'             => 0,
            'Construction'        => 0,
            'Other services'      => 0,
        ];

        $byBarangay = [];
        $unmapped   = [];
        $total      = 0;
        $source     = 'scims';

        $agg = scims_barangay_aggregate($refresh);
        if ($agg === false) {
            // Fallback to the local NCIMS database
            $source = 'local';
            $stmt = $con->query(
                "SELECT
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    j.line_of_industry,
                    COUNT(*) AS cnt
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 GROUP BY a.barangay, j.line_of_industry"
            );
            $agg = ['byBarangay' => [], 'citySectors' => []];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $cat = categorize_msme_industry($r['line_of_industry']);
                $b   = $r['barangay'];
                if (!isset($agg['byBarangay'][$b])) {
                    $agg['byBarangay'][$b] = [
                        'barangay' => $b,
                        'total'    => 0,
                        'new'      => 0,
                        'sectors'  => [],
                    ];
                }
                $agg['byBarangay'][$b]['total'] += (int) $r['cnt'];
                $agg['byBarangay'][$b]['sectors'][$cat] =
                    ($agg['byBarangay'][$b]['sectors'][$cat] ?? 0) + (int) $r['cnt'];
            }
        }

        foreach ($agg['byBarangay'] as $b => $info) {
            $cnt = $info['total'];
            $total += $cnt;

            foreach ($info['sectors'] as $cat => $n) {
                $categories[$cat] += $n;
            }

            if (isset($BARANGAY_COORDS[$b])) {
                $byBarangay[$b] = [
                    'barangay'   => $b,
                    'lat'        => $BARANGAY_COORDS[$b][0],
                    'lng'        => $BARANGAY_COORDS[$b][1],
                    'total'      => $cnt,
                    'categories' => array_fill_keys(array_keys($categories), 0),
                ];
                foreach ($info['sectors'] as $cat => $n) {
                    $byBarangay[$b]['categories'][$cat] = $n;
                }
            } else {
                $unmapped[] = ['barangay' => $b, 'count' => $cnt];
            }
        }

        echo json_encode([
            'status'        => 'success',
            'source'        => $source,
            'total'         => $total,
            'categories'    => $categories,
            'data'          => array_values($byBarangay),
            'unmapped'      => $unmapped,
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── economic_risk ────────────────────────────────────────────
// Economic Risk = Business Exposure x Hazard Level x Historical
// Damage (per barangay). Exposure and damage are normalised to
// 0..1; hazard is the LGU-assessed level (1..4). Damage acts as
// an amplifier, so areas with prior calamity losses rank higher.
// GET ?action=economic_risk[&refresh=1]
// ─────────────────────────────────────────────────────────────
if ($action === 'economic_risk') {
    global $BARANGAY_COORDS, $BARANGAY_HAZARDS;
    $refresh    = isset($_GET['refresh']);
    $calamityId = intval($_GET['calamity_id'] ?? 0);
    try {
        // Business exposure per barangay (SCIMS primary, local fallback)
        $source = 'scims';
        $agg = scims_barangay_aggregate($refresh);
        if ($agg === false) {
            $source = 'local';
            $stmtCounts = $con->query(
                "SELECT
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    COUNT(*) AS cnt
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 GROUP BY a.barangay"
            );
            $agg = ['byBarangay' => [], 'citySectors' => []];
            foreach ($stmtCounts->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $b = $r['barangay'];
                $agg['byBarangay'][$b] = [
                    'barangay' => $b,
                    'total'    => (int) $r['cnt'],
                    'new'      => 0,
                    'sectors'  => [],
                ];
            }
        }

        $counts = [];
        foreach ($agg['byBarangay'] as $b => $info) {
            $counts[$b] = $info['total'];
        }

        // Historical calamity damage per barangay, sourced from the
        // Calamity Monitoring controller (calamity-handler.php),
        // optionally filtered to a single calamity event.
        $calamities   = [];
        $calamityName = 'All calamities';
        $damages      = calamity_damages($con, $calamityId, $calamities, $calamityName);

        $maxCount = max(array_values($counts)) ?: 1;
        $maxDamage = 1.0;
        foreach ($damages as $d) {
            if ($d['damage'] > $maxDamage) $maxDamage = $d['damage'];
        }

        $rows       = [];
        $levelCounts = ['Critical' => 0, 'High' => 0, 'Moderate' => 0, 'Low' => 0];
        $totalMsmes = 0;

        foreach ($counts as $b => $cnt) {
            if (!isset($BARANGAY_COORDS[$b])) {
                continue; // outside the city / unknown barangay
            }
            $hazard = $BARANGAY_HAZARDS[$b] ?? ['level' => 1, 'label' => 'Low'];
            $dmg    = $damages[$b] ?? ['affected' => 0, 'damage' => 0.0];

            $exposure   = $cnt / $maxCount;                       // 0..1
            $damageNorm = $dmg['damage'] / $maxDamage;            // 0..1
            $raw        = $exposure * $hazard['level'] * (1 + $damageNorm);
            $totalMsmes += $cnt;

            $rows[] = [
                'barangay'       => $b,
                'lat'            => $BARANGAY_COORDS[$b][0],
                'lng'            => $BARANGAY_COORDS[$b][1],
                'business_count' => $cnt,
                'exposure'       => round($exposure, 4),
                'hazard_level'   => $hazard['level'],
                'hazard_label'   => $hazard['label'],
                'affected_count' => $dmg['affected'],
                'total_damage'   => $dmg['damage'],
                'raw'            => $raw,
            ];
        }

        $maxRaw = 1.0;
        foreach ($rows as $r) {
            if ($r['raw'] > $maxRaw) $maxRaw = $r['raw'];
        }

        $RISK_RULES = [
            ['level' => 'Critical', 'color' => '#dc3545', 'min' => 0.66],
            ['level' => 'High',     'color' => '#fd7e14', 'min' => 0.40],
            ['level' => 'Moderate', 'color' => '#ffc107', 'min' => 0.20],
            ['level' => 'Low',      'color' => '#28a745', 'min' => 0.00],
        ];

        foreach ($rows as &$r) {
            $ratio = $r['raw'] / $maxRaw;
            $r['risk_score'] = round($ratio, 4);
            $r['risk_level'] = 'Low';
            foreach ($RISK_RULES as $rule) {
                if ($ratio >= $rule['min']) {
                    $r['risk_level'] = $rule['level'];
                    break;
                }
            }
            $levelCounts[$r['risk_level']]++;
        }
        unset($r);

        echo json_encode([
            'status'        => 'success',
            'source'        => $source,
            'total_msmes'   => $totalMsmes,
            'max_damage'    => round($maxDamage, 2),
            'levels'        => $levelCounts,
            'rules'         => $RISK_RULES,
            'calamity_id'   => $calamityId,
            'calamity_name' => $calamityName,
            'calamities'    => $calamities,
            'data'          => array_values($rows),
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── economic_pressure ───────────────────────────────────────
// Price / Economic Pressure Map.
// Links Price Monitoring (DA / DTI / DOE) with MSME sectors:
// price pressure of a commodity category (latest monitored
// price vs SRP) weighted by the number of MSMEs in the sectors
// that depend on it (e.g. fuel x transportation).
// GET ?action=economic_pressure
// ─────────────────────────────────────────────────────────────
if ($action === 'economic_pressure') {
    global $BARANGAY_COORDS;
    $refresh = isset($_GET['refresh']);

    // commodity category -> affected MSME distribution sectors
    $CATEGORY_SECTORS = [
        'Fuel & Petroleum Products'      => ['Transportation'],
        'Grains & Rice'                  => ['Retail', 'Food services'],
        'Vegetables & Root Crops'        => ['Retail', 'Food services'],
        'Livestock & Poultry Products'   => ['Retail', 'Food services'],
        'Canned Goods & Processed Foods' => ['Retail', 'Food services'],
    ];

    $PRICE_LEVELS = [
        ['level' => 'Critical', 'color' => '#dc3545', 'min' => 1.15],
        ['level' => 'High',     'color' => '#fd7e14', 'min' => 1.00],
        ['level' => 'Moderate', 'color' => '#ffc107', 'min' => 0.85],
        ['level' => 'Low',      'color' => '#28a745', 'min' => 0.00],
    ];

    try {
        // Latest ACTIVE price per commodity, grouped by category
        $stmt = $con->query(
            "SELECT
                c.id AS category_id,
                c.name AS category_name,
                ag.name AS agency_name,
                co.srp,
                pl.prevailing_price,
                pl.monitored_at
             FROM commodity_categories c
             JOIN agencies ag ON ag.id = c.agency_id
             LEFT JOIN commodities co ON co.category_id = c.id AND co.is_active = 1
             LEFT JOIN price_logs pl ON pl.commodity_id = co.id AND pl.status = 'ACTIVE'
                AND pl.id = (
                    SELECT MAX(p2.id) FROM price_logs p2
                    WHERE p2.commodity_id = co.id AND p2.status = 'ACTIVE'
                )
             ORDER BY c.id"
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $byCategory = [];
        foreach ($rows as $r) {
            $byCategory[$r['category_name']] = $byCategory[$r['category_name']] ?? [
                'agency'    => $r['agency_name'],
                'prices'    => [],
                'srps'      => [],
                'periods'   => [],
            ];
            if ($r['prevailing_price'] !== null) {
                $byCategory[$r['category_name']]['prices'][]  = (float) $r['prevailing_price'];
                $byCategory[$r['category_name']]['srps'][]    = (float) ($r['srp'] ?? 0);
                $byCategory[$r['category_name']]['periods'][] = $r['monitored_at'];
            }
        }

        $categories = [];
        $catIndices = [];
        $hasPriceData = false;

        foreach ($byCategory as $name => $agg) {
            $pairs = [];
            foreach ($agg['prices'] as $i => $p) {
                if ($p > 0) {
                    $pairs[] = ['price' => $p, 'srp' => $agg['srps'][$i]];
                }
            }
            $nPriced = count($pairs);

            $avgPrice = $nPriced ? array_sum(array_column($pairs, 'price')) / $nPriced : null;
            $avgSrp   = $nPriced ? array_sum(array_column($pairs, 'srp')) / $nPriced : null;

            $index = null;
            $level = 'No data';
            $color = '#6c757d';
            if ($nPriced > 0 && $avgSrp > 0) {
                $hasPriceData = true;
                $index = $avgPrice / $avgSrp;
                $level = 'Low';
                foreach ($PRICE_LEVELS as $rule) {
                    if ($index >= $rule['min']) {
                        $level = $rule['level'];
                        $color = $rule['color'];
                        break;
                    }
                }
            }

            $catIndices[$name] = $index;
            $categories[] = [
                'name'          => $name,
                'agency'        => $agg['agency'],
                'avg_price'     => $avgPrice === null ? null : round($avgPrice, 2),
                'avg_srp'       => $avgSrp === null ? null : round($avgSrp, 2),
                'price_count'   => $nPriced,
                'index'         => $index === null ? null : round($index, 3),
                'level'         => $level,
                'color'         => $color,
                'period_from'   => $agg['periods'] ? min($agg['periods']) : null,
                'period_to'     => $agg['periods'] ? max($agg['periods']) : null,
            ];
        }

        // MSME counts per barangay by distribution sector
        // (SCIMS primary, local fallback)
        $source = 'scims';
        $agg = scims_barangay_aggregate($refresh);
        if ($agg === false) {
            $source = 'local';
            $stmtBiz = $con->query(
                "SELECT
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    j.line_of_industry,
                    COUNT(*) AS cnt
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 GROUP BY a.barangay, j.line_of_industry"
            );
            $agg = ['byBarangay' => [], 'citySectors' => []];
            foreach ($stmtBiz->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $cat = categorize_msme_industry($r['line_of_industry']);
                $b   = $r['barangay'];
                if (!isset($agg['byBarangay'][$b])) {
                    $agg['byBarangay'][$b] = [
                        'barangay' => $b,
                        'total'    => 0,
                        'new'      => 0,
                        'sectors'  => [],
                    ];
                }
                $agg['byBarangay'][$b]['total'] += (int) $r['cnt'];
                $agg['byBarangay'][$b]['sectors'][$cat] =
                    ($agg['byBarangay'][$b]['sectors'][$cat] ?? 0) + (int) $r['cnt'];
            }
        }

        $sectorByBarangay = [];
        $unmapped = [];
        foreach ($agg['byBarangay'] as $b => $info) {
            if (isset($BARANGAY_COORDS[$b])) {
                $sectorByBarangay[$b] = [
                    'barangay' => $b,
                    'lat'      => $BARANGAY_COORDS[$b][0],
                    'lng'      => $BARANGAY_COORDS[$b][1],
                    'total'    => $info['total'],
                    'sectors'  => $info['sectors'],
                ];
            } else {
                $unmapped[] = ['barangay' => $b, 'count' => $info['total']];
            }
        }

        // Economic pressure score per barangay
        $rowsData = [];
        foreach ($sectorByBarangay as $b => $info) {
            $score = 0.0;
            foreach ($CATEGORY_SECTORS as $catName => $sectors) {
                $idx = $catIndices[$catName] ?? null;
                if ($idx === null) continue;
                foreach ($sectors as $sec) {
                    $score += $idx * ($info['sectors'][$sec] ?? 0);
                }
            }
            $rowsData[] = [
                'barangay' => $b,
                'lat'      => $info['lat'],
                'lng'      => $info['lng'],
                'total'    => $info['total'],
                'sectors'  => $info['sectors'],
                'score'    => $score,
            ];
        }

        $maxScore = 1.0;
        foreach ($rowsData as $r) {
            if ($r['score'] > $maxScore) $maxScore = $r['score'];
        }

        foreach ($rowsData as &$r) {
            $ratio = $r['score'] / $maxScore;
            $r['score'] = round($ratio, 4);
            $r['level'] = $hasPriceData ? 'Low' : 'No data';
            $r['color'] = $hasPriceData ? '#28a745' : '#6c757d';
            if ($hasPriceData) {
                foreach ($PRICE_LEVELS as $rule) {
                    if ($ratio >= $rule['min']) {
                        $r['level'] = $rule['level'];
                        $r['color'] = $rule['color'];
                        break;
                    }
                }
            }
        }
        unset($r);

        echo json_encode([
            'status'          => 'success',
            'source'          => $source,
            'has_price_data'  => $hasPriceData,
            'categories'      => $categories,
            'data'            => array_values($rowsData),
            'unmapped'        => $unmapped,
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── economic_opportunity ────────────────────────────────────
// Economic Opportunity Map.
// Identifies where the City should invest, support or develop
// by combining MSME data with reference data (population,
// tourism, agriculture, infrastructure ratings):
//   commercial potential + growth momentum + tourism +
//   agriculture + livelihood gap + infrastructure gap +
//   sector diversity gap
// GET ?action=economic_opportunity[&refresh=1]
// ─────────────────────────────────────────────────────────────
if ($action === 'economic_opportunity') {
    global $BARANGAY_COORDS, $BARANGAY_OPPORTUNITY;
    $refresh = isset($_GET['refresh']);

    $OPP_WEIGHTS = [
        'commercial'     => 0.20,
        'growth'         => 0.15,
        'tourism'        => 0.15,
        'agriculture'    => 0.15,
        'livelihood'     => 0.15,
        'infrastructure' => 0.10,
        'diversity'      => 0.10,
    ];

    $OPP_LEVELS = [
        ['level' => 'Very High', 'color' => '#198754', 'min' => 0.66],
        ['level' => 'High',      'color' => '#28a745', 'min' => 0.40],
        ['level' => 'Moderate',  'color' => '#ffc107', 'min' => 0.20],
        ['level' => 'Low',       'color' => '#6c757d', 'min' => 0.00],
    ];

    try {
        // Business counts + new registrations per barangay
        // (SCIMS primary, local fallback)
        $source = 'scims';
        $agg = scims_barangay_aggregate($refresh);
        if ($agg === false) {
            $source = 'local';
            $stmtBiz = $con->query(
                "SELECT
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    j.line_of_industry,
                    j.registration_type,
                    COUNT(*) AS cnt
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 GROUP BY a.barangay, j.line_of_industry, j.registration_type"
            );
            $agg = ['byBarangay' => [], 'citySectors' => []];
            foreach ($stmtBiz->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $b = $r['barangay'];
                if (!isset($BARANGAY_COORDS[$b])) {
                    continue; // outside the city / unknown barangay
                }
                $cat = categorize_msme_industry($r['line_of_industry']);
                $agg['citySectors'][$cat] = true;

                if (!isset($agg['byBarangay'][$b])) {
                    $agg['byBarangay'][$b] = [
                        'barangay' => $b,
                        'total'    => 0,
                        'new'      => 0,
                        'sectors'  => [],
                    ];
                }
                $agg['byBarangay'][$b]['total'] += (int) $r['cnt'];
                if (strtoupper((string) $r['registration_type']) === 'NEW') {
                    $agg['byBarangay'][$b]['new'] += (int) $r['cnt'];
                }
                $agg['byBarangay'][$b]['sectors'][$cat] = true;
            }
        }

        $byBarangay = $agg['byBarangay'];
        $citySectors = $agg['citySectors'];

        $maxCount = 1;
        $maxNew   = 1;
        $maxPop   = 1;
        foreach ($BARANGAY_OPPORTUNITY as $b => $ref) {
            if (($byBarangay[$b]['total'] ?? 0) > $maxCount) $maxCount = $byBarangay[$b]['total'];
            if (($byBarangay[$b]['new'] ?? 0)   > $maxNew)   $maxNew   = $byBarangay[$b]['new'];
            if ($ref['population'] > $maxPop) $maxPop = $ref['population'];
        }
        $totalCitySectors = count($citySectors);

        $rows = [];
        foreach ($BARANGAY_OPPORTUNITY as $b => $ref) {
            $info = $byBarangay[$b] ?? [
                'barangay' => $b,
                'lat'      => $BARANGAY_COORDS[$b][0],
                'lng'      => $BARANGAY_COORDS[$b][1],
                'total'    => 0,
                'new'      => 0,
                'sectors'  => [],
            ];

            $exposure = $info['total'] / $maxCount;
            $growth   = $info['new'] / $maxNew;
            $tourism  = $ref['tourism'] / 4;
            $agri     = $ref['agriculture'] / 4;
            $popNorm  = $ref['population'] / $maxPop;
            $livelihood = $popNorm * (1 - $exposure);                    // many people, few businesses
            $infraGap   = $exposure * (1 - $ref['infrastructure'] / 4);  // businesses but limited infra
            $diversity  = 1 - (count($info['sectors']) / max($totalCitySectors, 1)); // underrepresented sectors

            $components = [
                'commercial'     => round($exposure, 4),
                'growth'         => round($growth, 4),
                'tourism'        => round($tourism, 4),
                'agriculture'    => round($agri, 4),
                'livelihood'     => round($livelihood, 4),
                'infrastructure' => round($infraGap, 4),
                'diversity'      => round($diversity, 4),
            ];

            $score = 0.0;
            foreach ($OPP_WEIGHTS as $key => $w) {
                $score += $w * $components[$key];
            }

            $rows[] = [
                'barangay'   => $b,
                'lat'        => $BARANGAY_COORDS[$b][0],
                'lng'        => $BARANGAY_COORDS[$b][1],
                'total'      => $info['total'],
                'new'        => $info['new'],
                'population' => $ref['population'],
                'components' => $components,
                'raw'        => $score,
            ];
        }

        $maxRaw = 1.0;
        foreach ($rows as $r) {
            if ($r['raw'] > $maxRaw) $maxRaw = $r['raw'];
        }

        $levelCounts = ['Very High' => 0, 'High' => 0, 'Moderate' => 0, 'Low' => 0];
        foreach ($rows as &$r) {
            $ratio = $r['raw'] / $maxRaw;
            $r['score'] = round($ratio, 4);
            $r['level'] = 'Low';
            $r['color'] = '#6c757d';
            foreach ($OPP_LEVELS as $rule) {
                if ($ratio >= $rule['min']) {
                    $r['level'] = $rule['level'];
                    $r['color'] = $rule['color'];
                    break;
                }
            }
            $levelCounts[$r['level']]++;
        }
        unset($r);

        // Highlights: best barangay per opportunity dimension
        $best = [
            'commercial'     => ['label' => 'Top commercial hub',     'key' => 'commercial'],
            'growth'         => ['label' => 'Fastest-growing activity','key' => 'growth'],
            'tourism'        => ['label' => 'Best tourism potential', 'key' => 'tourism'],
            'agriculture'    => ['label' => 'Best agriculture potential','key' => 'agriculture'],
            'livelihood'     => ['label' => 'Livelihood priority',    'key' => 'livelihood'],
            'infrastructure' => ['label' => 'Infrastructure investment','key' => 'infrastructure'],
            'diversity'      => ['label' => 'Sector diversification', 'key' => 'diversity'],
        ];
        $highlights = [];
        foreach ($best as $id => $def) {
            $top = null;
            foreach ($rows as $r) {
                if ($top === null || $r['components'][$def['key']] > $top['components'][$def['key']]) {
                    $top = $r;
                }
            }
            $highlights[] = [
                'label' => $def['label'],
                'barangay' => $top ? $top['barangay'] : '—',
                'value'    => $top ? $top['components'][$def['key']] : 0,
            ];
        }

        echo json_encode([
            'status'     => 'success',
            'source'     => $source,
            'weights'    => $OPP_WEIGHTS,
            'levels'     => $levelCounts,
            'highlights' => $highlights,
            'data'       => array_values($rows),
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── area_search ─────────────────────────────────────────────
// Autocomplete suggestions for the Economic Map search bar:
// matches barangay names and streets (from the SCIMS registry).
// GET ?action=area_search&q=
// ─────────────────────────────────────────────────────────────
if ($action === 'area_search') {
    global $BARANGAY_COORDS;
    $q = trim($_GET['q'] ?? '');
    if ($q === '') {
        echo json_encode(['status' => 'success', 'matches' => []]);
        exit;
    }

    $source = 'scims';
    $rows = scims_business_list(isset($_GET['refresh']));
    if ($rows === false) {
        $source = 'local';
        $rows = $con->query(
            "SELECT j.line_of_industry, j.category,
                COALESCE(NULLIF(TRIM(a.barangay), ''), '') AS barangay,
                COALESCE(NULLIF(TRIM(a.street), ''), '') AS street
             FROM juridicals j
             LEFT JOIN addresses a ON j.address_id = a.id"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    $matches = [];

    // Barangay matches (always available)
    foreach ($BARANGAY_COORDS as $b => $coords) {
        if (stripos($b, $q) !== false) {
            $matches[] = [
                'type'     => 'barangay',
                'label'    => $b,
                'barangay' => $b,
                'street'   => null,
                'lat'      => $coords[0],
                'lng'      => $coords[1],
            ];
        }
    }

    // Street matches from the registry
    $seen = [];
    foreach ($rows as $r) {
        $street = trim((string) ($r['juri_street'] ?? $r['street'] ?? ''));
        $b      = trim((string) ($r['juri_barangay'] ?? $r['barangay'] ?? ''));
        if ($street === '' || $b === '') {
            continue;
        }
        if (stripos($street, $q) === false) {
            continue;
        }
        $key = strtoupper($street) . '|' . strtoupper($b);
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;
        $matches[] = [
            'type'     => 'street',
            'label'    => $street . ' — ' . $b,
            'barangay' => $b,
            'street'   => $street,
            'lat'      => $BARANGAY_COORDS[$b][0] ?? null,
            'lng'      => $BARANGAY_COORDS[$b][1] ?? null,
        ];
    }

    usort($matches, function ($a, $b2) {
        return strcmp($a['label'], $b2['label']);
    });

    echo json_encode([
        'status'  => 'success',
        'source'  => $source,
        'matches' => array_slice($matches, 0, 30),
    ]);
    exit;
}

// ── area_summary ────────────────────────────────────────────
// Full profile of a searched barangay / street:
// total MSMEs, Micro/Small/Medium/Large (by workforce, DTI-style),
// top industry, economic risk level and economic activity.
// GET ?action=area_summary&barangay=[&street=]
// ─────────────────────────────────────────────────────────────
if ($action === 'area_summary') {
    global $BARANGAY_COORDS, $BARANGAY_HAZARDS;
    $barangay = trim($_GET['barangay'] ?? '');
    $street   = trim($_GET['street'] ?? '');
    if ($barangay === '') {
        echo json_encode(['status' => 'error', 'message' => 'Barangay is required.']);
        exit;
    }

    $refresh = isset($_GET['refresh']);
    $source  = 'scims';
    $rows    = scims_business_list($refresh);
    if ($rows === false) {
        $source = 'local';
        $rows = $con->query(
            "SELECT j.line_of_industry, j.category, j.registration_type,
                COALESCE(NULLIF(TRIM(a.barangay), ''), '') AS barangay,
                COALESCE(NULLIF(TRIM(a.street), ''), '') AS street
             FROM juridicals j
             LEFT JOIN addresses a ON j.address_id = a.id"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    $filtered = [];
    $counts   = [];
    foreach ($rows as $r) {
        $b = trim((string) ($r['juri_barangay'] ?? $r['barangay'] ?? ''));
        if ($b === '') {
            $b = 'Unspecified';
        }
        $counts[$b] = ($counts[$b] ?? 0) + 1;

        if (strcasecmp($b, $barangay) !== 0) {
            continue;
        }
        if ($street !== '') {
            $rs = trim((string) ($r['juri_street'] ?? $r['street'] ?? ''));
            if (stripos($rs, $street) === false) {
                continue;
            }
        }
        $filtered[] = $r;
    }

    $total = count($filtered);

    // MSME classification by workforce (DTI-style employment bands)
    $class = ['Micro' => 0, 'Small' => 0, 'Medium' => 0, 'Large' => 0];
    $sectors = [
        'Retail' => 0, 'Food services' => 0, 'Manufacturing' => 0,
        'Agriculture-related' => 0, 'Transportation' => 0, 'Tourism' => 0,
        'Construction' => 0, 'Other services' => 0,
    ];
    $industries = [];
    $new = 0;

    foreach ($filtered as $r) {
        $w = (int) ($r['totalworkforce'] ?? 0);
        if ($w <= 9)            $class['Micro']++;
        elseif ($w <= 99)       $class['Small']++;
        elseif ($w <= 199)      $class['Medium']++;
        else                    $class['Large']++;

        $cat = categorize_msme_industry($r['line_of_industry'] ?? '');
        $sectors[$cat]++;

        $ind = trim((string) ($r['line_of_industry'] ?? ''));
        if ($ind === '') {
            $ind = 'Unspecified';
        }
        $industries[$ind] = ($industries[$ind] ?? 0) + 1;

        $st = strtoupper(trim((string) ($r['status'] ?? $r['registration_type'] ?? '')));
        if ($st === 'NEW') {
            $new++;
        }
    }
    arsort($industries);
    $topIndustries = array_slice($industries, 0, 3, true);
    $topIndustry   = $topIndustries ? array_key_first($topIndustries) : null;
    $topIndustryCount = $topIndustries ? reset($topIndustries) : 0;
    $topSector = $sectors ? array_search(max($sectors), $sectors, true) : null;

    // Economic risk for the area (same formula as the risk map)
    $maxCount = max($counts) ?: 1;
    $damages  = calamity_damages($con, 0);
    $maxDamage = 1.0;
    foreach ($damages as $d) {
        if ($d['damage'] > $maxDamage) $maxDamage = $d['damage'];
    }

    $maxRaw = 1.0;
    $raws   = [];
    foreach ($counts as $b => $cnt) {
        if (!isset($BARANGAY_COORDS[$b])) continue;
        $hazard = $BARANGAY_HAZARDS[$b] ?? ['level' => 1];
        $dmg    = $damages[$b] ?? ['damage' => 0.0];
        $raw = ($cnt / $maxCount) * $hazard['level'] * (1 + $dmg['damage'] / $maxDamage);
        $raws[$b] = $raw;
        if ($raw > $maxRaw) $maxRaw = $raw;
    }

    $ratio = ($raws[$barangay] ?? 0) / $maxRaw;
    $riskLevel = 'Low';
    $riskColor = '#28a745';
    foreach ([[0.66, 'Critical', '#dc3545'], [0.40, 'High', '#fd7e14'], [0.20, 'Moderate', '#ffc107']] as $rule) {
        if ($ratio >= $rule[0]) {
            $riskLevel = $rule[1];
            $riskColor = $rule[2];
            break;
        }
    }

    $industriesOut = [];
    foreach ($topIndustries as $name => $n) {
        $industriesOut[] = ['name' => $name, 'count' => $n];
    }

    echo json_encode([
        'status'       => 'success',
        'source'       => $source,
        'barangay'     => $barangay,
        'street'       => $street ?: null,
        'lat'          => $BARANGAY_COORDS[$barangay][0] ?? null,
        'lng'          => $BARANGAY_COORDS[$barangay][1] ?? null,
        'total'        => $total,
        'classification' => $class,
        'top_industry' => $topIndustry,
        'top_industry_count' => $topIndustryCount,
        'top_sector'   => $topSector,
        'economic_risk' => [
            'score' => round($ratio, 4),
            'level' => $riskLevel,
            'color' => $riskColor,
        ],
        'economic_activity' => [
            'sectors'    => $sectors,
            'new'        => $new,
            'industries' => $industriesOut,
        ],
    ]);
    exit;
}

// ── risk_damage ──────────────────────────────────────────────
// Serves the Economic Risk Map's historical-damage data:
// the list of calamity events plus per-barangay damage
// (affected count + total estimated cost), optionally filtered
// to a single calamity event via ?calamity_id=.
// GET ?action=risk_damage[&calamity_id=]
// ─────────────────────────────────────────────────────────────
if ($action === 'risk_damage') {
    $calamityId = intval($_GET['calamity_id'] ?? 0);

    $calamities = $con->query(
        "SELECT c.id, c.name, c.calamity_type, c.declaration_date
         FROM calamities c
         ORDER BY c.declaration_date, c.name"
    )->fetchAll(PDO::FETCH_ASSOC);

    // Part 1: incident-level damage, attributed once to the main
    // business's barangay (avoids multiplying an incident's total
    // cost across every affected business).
    $incidentSql =
        "SELECT
            COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
            SUM(ci.estimated_cost_of_damages) AS damage,
            COUNT(DISTINCT ci.id) AS incidents
         FROM calamity_incidents ci
         LEFT JOIN juridicals j ON j.id = ci.juridical_id
         LEFT JOIN addresses a ON j.address_id = a.id
         WHERE ci.estimated_cost_of_damages > 0";
    if ($calamityId > 0) {
        $incidentSql .= " AND ci.calamity_id = $calamityId";
    }
    $incidentSql .= " GROUP BY a.barangay";

    // Part 2: per-business damage recorded in the affected-business
    // list, attributed to each affected business's barangay.
    $bizSql =
        "SELECT
            COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
            SUM(ib.estimated_cost_of_damages) AS damage,
            COUNT(DISTINCT ib.juridical_id) AS affected
         FROM calamity_incident_businesses ib
         JOIN calamity_incidents ci ON ci.id = ib.incident_id
         LEFT JOIN juridicals j ON j.id = ib.juridical_id
         LEFT JOIN addresses a ON j.address_id = a.id
         WHERE ib.estimated_cost_of_damages > 0";
    if ($calamityId > 0) {
        $bizSql .= " AND ci.calamity_id = $calamityId";
    }
    $bizSql .= " GROUP BY a.barangay";

    $damage = [];
    foreach ($con->query($incidentSql) as $r) {
        $damage[$r['barangay']] = [
            'damage'    => (float) $r['damage'],
            'affected'  => 0,
            'incidents' => (int) $r['incidents'],
        ];
    }
    foreach ($con->query($bizSql) as $r) {
        if (!isset($damage[$r['barangay']])) {
            $damage[$r['barangay']] = ['damage' => 0.0, 'affected' => 0, 'incidents' => 0];
        }
        $damage[$r['barangay']]['damage']   += (float) $r['damage'];
        $damage[$r['barangay']]['affected'] += (int) $r['affected'];
    }

    $totalDamage  = 0.0;
    $totalAffected = 0;
    foreach ($damage as $d) {
        $totalDamage  += $d['damage'];
        $totalAffected += $d['affected'];
    }

    echo json_encode([
        'status'         => 'success',
        'calamity_id'    => $calamityId,
        'calamities'     => $calamities,
        'damage'         => $damage,
        'total_damage'   => round($totalDamage, 2),
        'total_affected' => $totalAffected,
    ]);
    exit;
}

// Unknown action
echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
exit;