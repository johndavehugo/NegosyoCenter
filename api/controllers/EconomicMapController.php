<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';

class EconomicMapController
{
    private $con;

    // ── Barangay centroids ────────────────────────────────────────────
    private static $COORDS = [
        'Bagonbon'    => [10.5820, 123.3989],
        'Barangay I'  => [10.4939, 123.4273],
        'Barangay II' => [10.4842, 123.4111],
        'Barangay III'=> [10.4844, 123.4236],
        'Barangay IV' => [10.4826, 123.4172],
        'Barangay V'  => [10.4792, 123.4127],
        'Barangay VI' => [10.4800, 123.4222],
        'Buluangan'   => [10.3874, 123.3376],
        'Codcod'      => [10.4574, 123.2173],
        'Ermita'      => [10.4435, 123.4186],
        'Guadalupe'   => [10.4541, 123.3696],
        'Nataban'     => [10.4973, 123.3049],
        'Palampas'    => [10.5135, 123.4106],
        'Prosperidad' => [10.5122, 123.2785],
        'Punao'       => [10.5305, 123.4329],
        'Quezon'      => [10.4360, 123.2604],
        'Rizal'       => [10.4970, 123.3599],
        'San Juan'    => [10.4627, 123.4398],
    ];

    // ── LGU hazard levels (1=Low … 4=Critical) ───────────────────────
    private static $HAZARDS = [
        'Barangay I'   => ['level' => 4, 'label' => 'Critical'],
        'Barangay II'  => ['level' => 4, 'label' => 'Critical'],
        'Barangay III' => ['level' => 4, 'label' => 'Critical'],
        'Barangay IV'  => ['level' => 3, 'label' => 'High'],
        'Barangay V'   => ['level' => 3, 'label' => 'High'],
        'Barangay VI'  => ['level' => 3, 'label' => 'High'],
        'Bagonbon'     => ['level' => 2, 'label' => 'Moderate'],
        'Buluangan'    => ['level' => 3, 'label' => 'High'],
        'Codcod'       => ['level' => 2, 'label' => 'Moderate'],
        'Ermita'       => ['level' => 2, 'label' => 'Moderate'],
        'Guadalupe'    => ['level' => 3, 'label' => 'High'],
        'Nataban'      => ['level' => 2, 'label' => 'Moderate'],
        'Palampas'     => ['level' => 2, 'label' => 'Moderate'],
        'Prosperidad'  => ['level' => 2, 'label' => 'Moderate'],
        'Punao'        => ['level' => 3, 'label' => 'High'],
        'Quezon'       => ['level' => 2, 'label' => 'Moderate'],
        'Rizal'        => ['level' => 3, 'label' => 'High'],
        'San Juan'     => ['level' => 2, 'label' => 'Moderate'],
    ];

    // ── Opportunity reference data ────────────────────────────────────
    private static $OPPORTUNITY = [
        'Bagonbon'    => ['population' =>  5784, 'tourism' => 1, 'agriculture' => 4, 'infrastructure' => 2],
        'Barangay I'  => ['population' => 10616, 'tourism' => 3, 'agriculture' => 1, 'infrastructure' => 4],
        'Barangay II' => ['population' =>  6488, 'tourism' => 3, 'agriculture' => 1, 'infrastructure' => 4],
        'Barangay III'=> ['population' =>  3201, 'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 4],
        'Barangay IV' => ['population' =>   863, 'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 3],
        'Barangay V'  => ['population' =>  7185, 'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 3],
        'Barangay VI' => ['population' =>  5364, 'tourism' => 2, 'agriculture' => 1, 'infrastructure' => 3],
        'Buluangan'   => ['population' => 10962, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
        'Codcod'      => ['population' => 14234, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
        'Ermita'      => ['population' =>  2157, 'tourism' => 4, 'agriculture' => 2, 'infrastructure' => 1],
        'Guadalupe'   => ['population' => 10746, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
        'Nataban'     => ['population' =>  3816, 'tourism' => 1, 'agriculture' => 3, 'infrastructure' => 1],
        'Palampas'    => ['population' =>  9345, 'tourism' => 2, 'agriculture' => 4, 'infrastructure' => 2],
        'Prosperidad' => ['population' =>  5769, 'tourism' => 1, 'agriculture' => 3, 'infrastructure' => 1],
        'Punao'       => ['population' =>  6084, 'tourism' => 3, 'agriculture' => 3, 'infrastructure' => 2],
        'Quezon'      => ['population' => 10596, 'tourism' => 1, 'agriculture' => 4, 'infrastructure' => 2],
        'Rizal'       => ['population' => 16775, 'tourism' => 2, 'agriculture' => 2, 'infrastructure' => 3],
        'San Juan'    => ['population' =>  2665, 'tourism' => 4, 'agriculture' => 2, 'infrastructure' => 1],
    ];

    // ── Sectors: single source of truth = global/industries.php ──────
    private static $SECTOR_KEYS = null;

    private static function sectors()
    {
        if (self::$SECTOR_KEYS === null) {
            $file = dirname(__DIR__, 2) . '/global/industries.php';
            $industries = null;
            if (is_file($file)) {
                include $file; // defines $industries
            }
            if (!is_array($industries) || count($industries) === 0) {
                // Fallback (should never happen unless file missing)
                $industries = [
                    'Agriculture',
                    'Fishing',
                    'Mining and Quarrying',
                    'Manufacturing',
                    'Electricity, Gas, and Water Supply',
                    'Construction',
                    'Wholesale and Retail Trade',
                    'Hotels and Restaurants',
                    'Transport, Storage, and Communication',
                    'Financial Intermediation',
                    'Real Estate, Renting, and Business Activities',
                    'Public Administration and Defense',
                    'Education',
                    'Health and Social Worker',
                    'Other Community, Social and Personal Service Activities',
                    'Activities of Private Households as Employers...',
                    'Extra-territorial Organizations and Bodies',
                ];
            }
            self::$SECTOR_KEYS = array_map('strtoupper', array_values($industries));
        }
        return self::$SECTOR_KEYS;
    }

    public function __construct()
    {
        global $con;
        $this->con = $con;
    }

    // ── GET ?action=sectors ──────────────────────────────────────────
    // Returns the canonical sector list from global/industries.php so the
    // Distribution Map chips/totals/legend always match MSME registration.
    public function getSectors()
    {
        try {
            $sectors = self::sectors();
            return [
                'status'  => 'success',
                'source'  => 'global/industries.php',
                'total'   => count($sectors),
                'sectors' => $sectors,
            ];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── SCIMS cache helpers ────────────────────────────────────────────
    private function scimsCacheFile()
    {
        $cacheDir = dirname(__DIR__, 2) . '/cache';
        return $cacheDir . '/scims_businesses.json';
    }

    private function fetchScimsBusinesses($refresh = false)
    {
        $cacheFile = $this->scimsCacheFile();
        $ttl = 21600;

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

        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0775, true);
        }
        @file_put_contents($cacheFile, json_encode($rows));

        return $rows;
    }

    // ── Aggregate REGISTERED MSMEs per barangay ─────────────────────
    // Primary source: live SCIMS registry API (vamosmobile.app) — the full
    // registered-MSME dataset. Falls back to local juridicals + addresses
    // (same data as GET /api/business) when the API is unreachable.
    // Sectors are STRTOUPPER of global/industries.php in both paths.
    private function aggregate($refresh = false)
    {
        $rows = $this->fetchScimsBusinesses($refresh);
        $source = 'scims';

        if ($rows === false) {
            $source = 'local';
            $stmt = $this->con->query(
                "SELECT
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    j.line_of_industry,
                    j.registration_type,
                    j.category
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 WHERE j.bus_status = 'ACTIVE'"
            );
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $byBarangay = [];
        $citySectors = [];

        foreach ($rows as $r) {
            if ($source === 'scims') {
                $city = trim((string) ($r['juri_city'] ?? ''));
                $b = trim((string) ($r['juri_barangay'] ?? ''));
                // Keep only San Carlos City businesses
                if (stripos($city, 'San Carlos') === false && !isset(self::$COORDS[$b])) {
                    continue;
                }
                if ($b === '') {
                    $b = 'Unspecified';
                }
                $industry = $r['line_of_industry'] ?? '';
                $isNew = strtoupper(trim((string) ($r['status'] ?? ''))) === 'NEW';
            } else {
                $b = trim((string) ($r['barangay'] ?? ''));
                if ($b === '') {
                    $b = 'Unspecified';
                }
                $industry = $r['line_of_industry'] ?? '';
                $isNew = strtoupper(trim((string) ($r['registration_type'] ?? ''))) === 'NEW';
            }

            $cat = $this->categorize($industry);
            $citySectors[$cat] = true;

            if (!isset($byBarangay[$b])) {
                $byBarangay[$b] = ['total' => 0, 'new' => 0, 'sectors' => []];
            }
            $byBarangay[$b]['total']++;
            if ($isNew) $byBarangay[$b]['new']++;
            $byBarangay[$b]['sectors'][$cat] = ($byBarangay[$b]['sectors'][$cat] ?? 0) + 1;
        }

        return ['byBarangay' => $byBarangay, 'citySectors' => $citySectors, 'source' => $source];
    }

    // ── Map line_of_industry → canonical sector from global/industries.php
    // Registered MSMEs store the EXACT sector string (uppercased) via the
    // MSME registration dropdown, so exact case-insensitive match comes
    // first. Keyword rules remain only as fallback for legacy free-text.
    private function categorize($line)
    {
        $raw = trim((string) $line);
        if ($raw === '') return 'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES';

        // 1) Exact match against global/industries.php (case-insensitive;
        // canonical keys are STRTOUPPER of that file).
        $u = strtoupper($raw);
        foreach (self::sectors() as $sector) {
            if ($u === $sector) {
                return $sector;
            }
        }

        $rules = [
            'AGRICULTURE'                                             => ['AGRICULTUR', 'FARM', 'LIVESTOCK', 'POULTRY', 'CROPS', 'PLANTATION', 'AGRI'],
            'FISHING'                                                 => ['FISHING', 'FISHERY', 'AQUACULTURE', 'FISH POND', 'SEAWEED'],
            'MINING AND QUARRYING'                                    => ['MINING', 'QUARRY', 'MINERAL', 'SAND AND GRAVEL', 'EXTRACTION'],
            'MANUFACTURING'                                           => ['MANUFACTUR', 'FACTORY', 'FABRICATION', 'PROCESSING', 'GARMENT', 'PRODUCTION', 'MILL', 'BAKERY', 'BAKING', 'PRINTING'],
            'ELECTRICITY, GAS, AND WATER SUPPLY'                      => ['ELECTRIC', 'POWER', 'GAS', 'WATER SUPPLY', 'UTILITIES'],
            'CONSTRUCTION'                                            => ['CONSTRUCTION', 'BUILDING', 'CONTRACTOR', 'CIVIL WORKS', 'ENGINEERING'],
            'WHOLESALE AND RETAIL TRADE'                              => ['WHOLESALE', 'RETAIL', 'SARI-SARI', 'STORE', 'TRADING', 'TRADE', 'DEALER', 'MARKET', 'SUPERMARKET', 'PHARMACY', 'HARDWARE'],
            'HOTELS AND RESTAURANTS'                                  => ['HOTEL', 'INN', 'LODGING', 'PENSION', 'RESTAURANT', 'EATERY', 'FOOD SERVICE', 'FOODS', 'CAFETERIA', 'CATERING', 'FAST FOOD', 'CANTEEN'],
            'TRANSPORT, STORAGE, AND COMMUNICATION'                   => ['TRANSPORT', 'STORAGE', 'COMMUNICATION', 'LOGISTIC', 'COURIER', 'SHIPPING', 'FREIGHT', 'TRUCKING', 'TAXI', 'TRICYCLE'],
            'FINANCIAL INTERMEDIATION'                                => ['BANK', 'LENDING', 'FINANCE', 'CREDIT', 'INSURANCE', 'PAWNSHOP', 'REMITTANCE', 'MICROFINANCE'],
            'REAL ESTATE, RENTING, AND BUSINESS ACTIVITIES'           => ['REAL ESTATE', 'RENTING', 'RENTAL', 'LEASING', 'PROPERTY', 'CONSULTANCY', 'CONSULTING', 'ADVERTISING', 'MANPOWER'],
            'PUBLIC ADMINISTRATION AND DEFENSE'                       => ['PUBLIC ADMIN', 'GOVERNMENT', 'DEFENSE', 'LGU', 'BARANGAY HALL'],
            'EDUCATION'                                               => ['EDUCATION', 'SCHOOL', 'TUTORIAL', 'REVIEW', 'TRAINING', 'DAYCARE', 'LEARNING'],
            'HEALTH AND SOCIAL WORKER'                                => ['HEALTH', 'CLINIC', 'HOSPITAL', 'DENTAL', 'MEDICAL', 'WELLNESS', 'SPA'],
            'ACTIVITIES OF PRIVATE HOUSEHOLDS AS EMPLOYERS...'        => ['HOUSEHOLD', 'DOMESTIC', 'PRIVATE HOUSEHOLD'],
            'EXTRA-TERRITORIAL ORGANIZATIONS AND BODIES'              => ['EXTRA-TERRITORIAL', 'INTERNATIONAL', 'NGO', 'EMBASSY'],
        ];

        foreach ($rules as $sector => $keywords) {
            foreach ($keywords as $kw) {
                if (strpos($u, $kw) !== false) {
                    // Only return it if it exists in the canonical list
                    foreach (self::sectors() as $canonical) {
                        if ($canonical === $sector) return $canonical;
                    }
                    return $sector;
                }
            }
        }

        return 'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES';
    }

    private function normalizeBusinessName($row)
    {
        foreach (['juri_name', 'business_name', 'trade_name', 'tradename'] as $field) {
            $name = trim((string) ($row[$field] ?? ''));
            if ($name !== '') return $name;
        }
        return 'Unnamed Business';
    }

    private function normalizeBusinessClass($category, $workforce = null)
    {
        $labels = [
            'micro'  => 'Micro',
            'small'  => 'Small',
            'medium' => 'Medium',
            'large'  => 'Large',
        ];
        $key = strtolower(trim((string) $category));
        if (isset($labels[$key])) return $labels[$key];

        $workforce = trim((string) $workforce);
        if ($workforce !== '' && ctype_digit($workforce)) {
            $value = (int) $workforce;
            if ($value <= 9) return 'Micro';
            if ($value <= 99) return 'Small';
            if ($value <= 199) return 'Medium';
            return 'Large';
        }

        return 'Unknown';
    }

    private function registeredBusinessRows($refresh = false)
    {
        $source = 'scims';
        $rows = $this->fetchScimsBusinesses($refresh);

        if ($rows === false) {
            $source = 'local';
            $stmt = $this->con->query(
                "SELECT
                    j.name           AS business_name,
                    j.entity_no,
                    j.line_of_industry,
                    j.category       AS msme_category,
                    j.registration_type,
                    COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                    TRIM(a.street)   AS street,
                    ''               AS juri_city
                 FROM juridicals j
                 LEFT JOIN addresses a ON j.address_id = a.id
                 WHERE j.bus_status = 'ACTIVE'"
            );
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $businesses = [];
        foreach ($rows as $r) {
            if ($source === 'scims') {
                $city = trim((string) ($r['juri_city'] ?? ''));
                $barangay = trim((string) ($r['juri_barangay'] ?? ''));
                if (stripos($city, 'San Carlos') === false && !isset(self::$COORDS[$barangay])) {
                    continue;
                }
                if ($barangay === '') {
                    $barangay = 'Unspecified';
                }
                $name = $this->normalizeBusinessName($r);
                $industry = $r['line_of_industry'] ?? '';
                $regType = $r['status'] ?? '';
                $entityNo = $r['entity_no'] ?? '';
                $msmeCategory = $this->normalizeBusinessClass(
                    $r['category'] ?? '',
                    $r['totalworkforce'] ?? null
                );
                $street = trim((string) ($r['juri_street'] ?? ''));
            } else {
                $barangay = trim((string) ($r['barangay'] ?? ''));
                if ($barangay === '') {
                    $barangay = 'Unspecified';
                }
                $name = $r['business_name'] ?? '';
                $industry = $r['line_of_industry'] ?? '';
                $regType = $r['registration_type'] ?? '';
                $entityNo = $r['entity_no'] ?? '';
                $msmeCategory = $this->normalizeBusinessClass($r['msme_category'] ?? '');
                $street = $r['street'] ?? '';
            }

            $businesses[] = [
                'name'      => $name,
                'entity_no' => $entityNo,
                'industry'  => $industry,
                'category'  => $msmeCategory,
                'reg_type'  => $regType,
                'barangay'  => $barangay,
                'street'    => $street,
            ];
        }

        return ['source' => $source, 'data' => $businesses];
    }

    private function businessCoordinates($barangay, $business)
    {
        $key = (string) ($business['entity_no'] ?? '');
        if ($key === '') {
            $key = (string) ($business['name'] ?? '');
        }
        $key .= '|' . $barangay;
        $latJitter = ((crc32($key . '|lat') % 2001) - 1000) / 1000000;
        $lngJitter = ((crc32($key . '|lng') % 2001) - 1000) / 1000000;

        return [
            'lat' => round(self::$COORDS[$barangay][0] + $latJitter, 6),
            'lng' => round(self::$COORDS[$barangay][1] + $lngJitter, 6),
        ];
    }

    // ── GET ?action=economic_hotspots ─────────────────────────────────
    public function getHotspots()
    {
        $refresh = isset($_GET['refresh']);
        try {
            $agg = $this->aggregate($refresh);
            $byBarangay = $agg['byBarangay'];
            $source = $agg['source'];
            $mapped = $unmapped = [];
            $total  = 0;

            foreach ($byBarangay as $b => $info) {
                $total += $info['total'];
                if (isset(self::$COORDS[$b])) {
                    $mapped[] = [
                        'barangay' => $b,
                        'count'    => $info['total'],
                        'lat'      => self::$COORDS[$b][0],
                        'lng'      => self::$COORDS[$b][1],
                    ];
                } else {
                    $unmapped[] = ['barangay' => $b, 'count' => $info['total']];
                }
            }

            usort($mapped,   fn($a, $b) => $b['count'] <=> $a['count']);
            usort($unmapped, fn($a, $b) => $b['count'] <=> $a['count']);

            return [
                'status'   => 'success',
                'source'   => $source,
                'total'    => $total,
                'mapped'   => $mapped,
                'unmapped' => $unmapped,
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── GET ?action=msme_distribution ────────────────────────────────
    // Per-barangay breakdown of REGISTERED MSMEs by sector
    // (sectors from global/industries.php).
    public function getDistribution()
    {
        $refresh = isset($_GET['refresh']);
        try {
            $agg = $this->aggregate($refresh);
            $byBarangay = $agg['byBarangay'];
            $source = $agg['source'];
            $sectorKeys = self::sectors();

            $categories = array_fill_keys($sectorKeys, 0);
            $mapped = $unmapped = [];
            $total  = 0;

            foreach ($byBarangay as $b => $info) {
                $total += $info['total'];

                foreach ($info['sectors'] as $cat => $n) {
                    if (isset($categories[$cat])) $categories[$cat] += $n;
                }

                if (isset(self::$COORDS[$b])) {
                    $row = [
                        'barangay'   => $b,
                        'lat'        => self::$COORDS[$b][0],
                        'lng'        => self::$COORDS[$b][1],
                        'total'      => $info['total'],
                        'categories' => array_fill_keys($sectorKeys, 0),
                    ];
                    foreach ($info['sectors'] as $cat => $n) {
                        if (isset($row['categories'][$cat])) $row['categories'][$cat] = $n;
                    }
                    $mapped[] = $row;
                } else {
                    $unmapped[] = ['barangay' => $b, 'count' => $info['total']];
                }
            }

            return [
                'status'     => 'success',
                'source'     => $source,
                'total'      => $total,
                'categories' => $categories,
                'data'       => $mapped,
                'unmapped'   => $unmapped,
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── GET ?action=economic_risk[&calamity_id=N] ────────────────────
    public function getRisk()
    {
        $refresh = isset($_GET['refresh']);
        $calamityId = intval($_GET['calamity_id'] ?? 0);

        try {
            $agg = $this->aggregate($refresh);
            $byBarangay = $agg['byBarangay'];
            $source = $agg['source'];

            // Business counts per barangay
            $counts = [];
            foreach ($byBarangay as $b => $info) {
                $counts[$b] = $info['total'];
            }

            // Calamity list for the dropdown
            $calStmt = $this->con->query(
                "SELECT id, name, calamity_type FROM calamities ORDER BY declaration_date DESC"
            );
            $calamities = $calStmt->fetchAll(PDO::FETCH_ASSOC);

            $calamityName = 'All calamities';
            foreach ($calamities as $c) {
                if ((int)$c['id'] === $calamityId) {
                    $calamityName = $c['name'];
                    break;
                }
            }

            // Historical damage per barangay from calamity_incident_businesses
            $dmgSql = "SELECT
                COALESCE(NULLIF(TRIM(a.barangay), ''), 'Unspecified') AS barangay,
                COUNT(DISTINCT cib.juridical_id) AS affected_count,
                COALESCE(SUM(cib.estimated_cost_of_damages), 0) AS total_damage
             FROM calamity_incident_businesses cib
             JOIN juridicals j ON j.id = cib.juridical_id
             LEFT JOIN addresses a ON j.address_id = a.id";

            $dmgParams = [];
            if ($calamityId > 0) {
                $dmgSql .= " JOIN calamity_incidents ci ON ci.id = cib.incident_id WHERE ci.calamity_id = ?";
                $dmgParams[] = $calamityId;
            }
            $dmgSql .= " GROUP BY a.barangay";

            $dmgStmt = $this->con->prepare($dmgSql);
            $dmgStmt->execute($dmgParams);

            $damages = [];
            foreach ($dmgStmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $damages[$r['barangay']] = [
                    'affected' => (int)$r['affected_count'],
                    'damage'   => (float)$r['total_damage'],
                ];
            }

            $maxCount  = max(array_values($counts) ?: [1]);
            $maxDamage = 1.0;
            foreach ($damages as $d) {
                if ($d['damage'] > $maxDamage) $maxDamage = $d['damage'];
            }

            $RISK_RULES = [
                ['level' => 'Critical', 'color' => '#dc3545', 'min' => 0.66],
                ['level' => 'High',     'color' => '#fd7e14', 'min' => 0.40],
                ['level' => 'Moderate', 'color' => '#ffc107', 'min' => 0.20],
                ['level' => 'Low',      'color' => '#28a745', 'min' => 0.00],
            ];

            $rows        = [];
            $levelCounts = ['Critical' => 0, 'High' => 0, 'Moderate' => 0, 'Low' => 0];
            $totalMsmes  = 0;

            foreach ($counts as $b => $cnt) {
                if (!isset(self::$COORDS[$b])) continue;

                $hazard     = self::$HAZARDS[$b] ?? ['level' => 1, 'label' => 'Low'];
                $dmg        = $damages[$b] ?? ['affected' => 0, 'damage' => 0.0];
                $exposure   = $cnt / $maxCount;
                $damageNorm = $dmg['damage'] / $maxDamage;
                $raw        = $exposure * $hazard['level'] * (1 + $damageNorm);
                $totalMsmes += $cnt;

                $rows[] = [
                    'barangay'       => $b,
                    'lat'            => self::$COORDS[$b][0],
                    'lng'            => self::$COORDS[$b][1],
                    'business_count' => $cnt,
                    'exposure'       => round($exposure, 4),
                    'hazard_level'   => $hazard['level'],
                    'hazard_label'   => $hazard['label'],
                    'affected_count' => $dmg['affected'],
                    'total_damage'   => $dmg['damage'],
                    'raw'            => $raw,
                ];
            }

            $maxRaw = max(array_column($rows, 'raw') ?: [1.0]);

            foreach ($rows as &$r) {
                $ratio          = $r['raw'] / $maxRaw;
                $r['risk_score'] = round($ratio, 4);
                $r['risk_level'] = 'Low';
                foreach ($RISK_RULES as $rule) {
                    if ($ratio >= $rule['min']) {
                        $r['risk_level'] = $rule['level'];
                        break;
                    }
                }
                $levelCounts[$r['risk_level']]++;
                unset($r['raw']);
            }
            unset($r);

            return [
                'status'        => 'success',
                'source'        => $source,
                'total_msmes'   => $totalMsmes,
                'levels'        => $levelCounts,
                'rules'         => $RISK_RULES,
                'calamity_id'   => $calamityId,
                'calamity_name' => $calamityName,
                'calamities'    => $calamities,
                'data'          => array_values($rows),
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── GET ?action=economic_opportunity ─────────────────────────────
    public function getOpportunity()
    {
        $refresh = isset($_GET['refresh']);
        try {
            $agg = $this->aggregate($refresh);
            $byBarangay = $agg['byBarangay'];
            $source = $agg['source'];

            $OPP_LEVELS = [
                ['level' => 'Very High', 'color' => '#198754', 'min' => 0.70],
                ['level' => 'High',      'color' => '#28a745', 'min' => 0.45],
                ['level' => 'Moderate',  'color' => '#ffc107', 'min' => 0.25],
                ['level' => 'Low',       'color' => '#6c757d', 'min' => 0.00],
            ];

            $maxPop   = 1; $maxBiz   = 1; $maxNew = 1;
            foreach (self::$OPPORTUNITY as $b => $ref) {
                if ($ref['population'] > $maxPop) $maxPop = $ref['population'];
            }
            foreach ($byBarangay as $info) {
                if ($info['total'] > $maxBiz) $maxBiz = $info['total'];
                if ($info['new']   > $maxNew) $maxNew = $info['new'];
            }

            $rows        = [];
            $levelCounts = ['Very High' => 0, 'High' => 0, 'Moderate' => 0, 'Low' => 0];
            $highlights  = [];

            foreach (self::$COORDS as $b => $coords) {
                $ref  = self::$OPPORTUNITY[$b] ?? ['population' => 0, 'tourism' => 1, 'agriculture' => 1, 'infrastructure' => 1];
                $info = $byBarangay[$b] ?? ['total' => 0, 'new' => 0, 'sectors' => []];

                $bizCount  = $info['total'];
                $newCount  = $info['new'];
                $pop       = $ref['population'];
                $divCount  = count(array_filter($info['sectors'], fn($n) => $n > 0));

                $commercial    = $bizCount  / $maxBiz;
                $growth        = $newCount  / ($maxNew ?: 1);
                $tourism       = ($ref['tourism']       - 1) / 3;
                $agriculture   = ($ref['agriculture']   - 1) / 3;
                $livelihood    = $pop > 0 && $bizCount < 10 ? min(1, $pop / $maxPop) : 0;
                $infrastructure = 1 - (($ref['infrastructure'] - 1) / 3);
                $diversity     = $divCount < 5 ? (5 - $divCount) / 5 : 0;

                $score = ($commercial * 0.25) + ($growth * 0.15) + ($tourism * 0.15)
                       + ($agriculture * 0.15) + ($livelihood * 0.10)
                       + ($infrastructure * 0.10) + ($diversity * 0.10);

                $level = 'Low';
                foreach ($OPP_LEVELS as $rule) {
                    if ($score >= $rule['min']) { $level = $rule['level']; break; }
                }
                $levelCounts[$level]++;

                $rows[] = [
                    'barangay'   => $b,
                    'lat'        => $coords[0],
                    'lng'        => $coords[1],
                    'level'      => $level,
                    'score'      => round($score, 4),
                    'total'      => $bizCount,
                    'new'        => $newCount,
                    'population' => $pop,
                    'components' => [
                        'commercial'     => round($commercial, 4),
                        'growth'         => round($growth, 4),
                        'tourism'        => round($tourism, 4),
                        'agriculture'    => round($agriculture, 4),
                        'livelihood'     => round($livelihood, 4),
                        'infrastructure' => round($infrastructure, 4),
                        'diversity'      => round($diversity, 4),
                    ],
                ];
            }

            usort($rows, fn($a, $b) => $b['score'] <=> $a['score']);

            // Top highlights for the sidebar panel
            $compLabels = [
                'commercial'     => 'Best commercial area',
                'growth'         => 'Fastest growing area',
                'tourism'        => 'Highest tourism potential',
                'agriculture'    => 'Highest agriculture potential',
                'livelihood'     => 'Biggest livelihood gap',
            ];
            $bests = [];
            foreach ($rows as $r) {
                foreach ($compLabels as $key => $label) {
                    if (!isset($bests[$key]) || $r['components'][$key] > $bests[$key]['val']) {
                        $bests[$key] = ['val' => $r['components'][$key], 'barangay' => $r['barangay'], 'label' => $label];
                    }
                }
            }
            foreach ($bests as $h) {
                $highlights[] = ['label' => $h['label'], 'barangay' => $h['barangay']];
            }

            return [
                'status'     => 'success',
                'source'     => $source,
                'levels'     => $levelCounts,
                'highlights' => $highlights,
                'data'       => $rows,
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── GET ?action=sector_businesses&sector=... ─────────────────────
    // Returns every REGISTERED MSME in the given sector (STRTOUPPER of
    // global/industries.php) with barangay coordinates so the JS can
    // render individual pins on the Distribution Map.
    // Source: live SCIMS registry API primary, local DB fallback.
    public function sectorBusinesses()
    {
        $sector = strtoupper(trim($_GET['sector'] ?? ''));
        if ($sector === '') {
            return ['status' => 'error', 'message' => 'sector parameter is required.'];
        }

        $canonical = null;
        foreach (self::sectors() as $s) {
            if ($s === $sector) {
                $canonical = $s;
                break;
            }
        }
        if ($canonical === null) {
            return ['status' => 'error', 'message' => 'Unknown sector. Use action=sectors to list valid sectors.'];
        }

        $refresh = isset($_GET['refresh']);
        try {
            $registered = $this->registeredBusinessRows($refresh);
            $businesses = [];
            $unmapped = [];

            foreach ($registered['data'] as $row) {
                if ($this->categorize($row['industry']) !== $canonical) {
                    continue;
                }

                if (!isset(self::$COORDS[$row['barangay']])) {
                    $unmapped[] = ['barangay' => $row['barangay'], 'name' => $row['name']];
                    continue;
                }

                $businesses[] = array_merge(
                    $row,
                    $this->businessCoordinates($row['barangay'], $row)
                );
            }

            return [
                'status'   => 'success',
                'source'   => $registered['source'],
                'sector'   => $canonical,
                'total'    => count($businesses),
                'data'     => $businesses,
                'unmapped' => $unmapped,
                'unmapped_count' => count($unmapped),
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function barangayBusinesses()
    {
        $requested = trim((string) ($_GET['barangay'] ?? ''));
        if ($requested === '') {
            return ['status' => 'error', 'message' => 'barangay parameter is required.'];
        }

        $barangay = null;
        foreach (array_keys(self::$COORDS) as $knownBarangay) {
            if (strcasecmp($knownBarangay, $requested) === 0) {
                $barangay = $knownBarangay;
                break;
            }
        }
        if ($barangay === null) {
            return ['status' => 'error', 'message' => 'Unknown barangay.'];
        }

        $refresh = isset($_GET['refresh']);
        try {
            $registered = $this->registeredBusinessRows($refresh);
            $businesses = [];

            foreach ($registered['data'] as $row) {
                if (strcasecmp($row['barangay'], $barangay) !== 0) {
                    continue;
                }

                $row['barangay'] = $barangay;
                $businesses[] = array_merge(
                    $row,
                    $this->businessCoordinates($barangay, $row)
                );
            }

            return [
                'status'   => 'success',
                'source'   => $registered['source'],
                'barangay' => $barangay,
                'total'    => count($businesses),
                'data'     => $businesses,
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // ── GET ?action=area_search&q=... ────────────────────────────────
    public function areaSearch($q)
    {
        try {
            $q       = trim((string) $q);
            $results = [];

            // Match known barangay names first
            foreach (array_keys(self::$COORDS) as $b) {
                if ($q === '' || stripos($b, $q) !== false) {
                    $results[] = [
                        'type'  => 'barangay',
                        'label' => $b,
                        'sub'   => 'San Carlos City',
                        'lat'   => self::$COORDS[$b][0],
                        'lng'   => self::$COORDS[$b][1],
                    ];
                }
            }

            // Match street names from the addresses table
            if ($q !== '') {
                $like = '%' . $q . '%';
                $stmt = $this->con->prepare(
                    "SELECT DISTINCT TRIM(a.street) AS street,
                                     TRIM(a.barangay) AS barangay
                     FROM addresses a
                     WHERE a.street LIKE ?
                       AND a.street != ''
                     LIMIT 20"
                );
                $stmt->execute([$like]);
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                    $b = $r['barangay'];
                    if (!isset(self::$COORDS[$b])) continue;
                    $results[] = [
                        'type'  => 'street',
                        'label' => $r['street'],
                        'sub'   => $b . ', San Carlos City',
                        'lat'   => self::$COORDS[$b][0],
                        'lng'   => self::$COORDS[$b][1],
                    ];
                }
            }

            return ['status' => 'success', 'data' => $results];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
