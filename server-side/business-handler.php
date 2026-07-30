<?php
require_once __DIR__ . '/../config/db_connect.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'detail') {
    $entityNo = $_GET['entity_no'] ?? '';
    echo json_encode(getBusinessDetail($entityNo, $con));
    exit;
}

if ($action === 'import') {
    $entityNo = $_POST['entity_no'] ?? '';
    echo json_encode(importBusiness($entityNo, $con));
    exit;
}

$draw = intval($_GET['draw'] ?? 0);
$start = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? 10);
$search = $_GET['search']['value'] ?? '';
$orderColumn = intval($_GET['order'][0]['column'] ?? 0);
$orderDir = $_GET['order'][0]['dir'] ?? 'asc';

$columns = ['j.entity_no', 'j.name', 'j.category', 'concat(e.first_name, " ", e.middle_name, " ", e.last_name)'];
$orderBy = $columns[$orderColumn] ?? 'j.entity_no';
$orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';

$totalStmt = $con->query("SELECT COUNT(*) FROM juridicals j LEFT JOIN employers e ON j.employer_id = e.id");
$totalRecords = $totalStmt->fetchColumn();

$data = [];

if (!empty($search)) {
    $like = '%' . $search . '%';

    $stmt = $con->prepare("SELECT
        j.id, j.entity_no AS juri_entity_no, j.name AS juri_name,
        j.category AS juri_msme_category,
        e.first_name, e.middle_name, e.last_name
        FROM juridicals j
        LEFT JOIN employers e ON j.employer_id = e.id
        WHERE j.entity_no LIKE ? OR j.name LIKE ? OR j.category LIKE ?
           OR e.first_name LIKE ? OR e.last_name LIKE ?
        ORDER BY $orderBy $orderDir");
    $stmt->execute([$like, $like, $like, $like, $like]);
    $localRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $merged = [];
    foreach ($localRows as $row) {
        $fullName = trim(implode(' ', array_filter([$row['first_name'] ?? '', $row['middle_name'] ?? '', $row['last_name'] ?? ''])));
        $merged[$row['juri_entity_no']] = [
            'id' => $row['id'],
            'juridical' => [
                'entity_no' => $row['juri_entity_no'],
                'name' => $row['juri_name'],
                'msme_category' => $row['juri_msme_category'],
            ],
            'employer' => [
                'full_name' => $fullName,
            ],
        ];
    }

    $scimsRaw = @file_get_contents('https://vamosmobile.app/api/testjuridical/business');
    if ($scimsRaw) {
        $scimsAll = json_decode($scimsRaw, true)['data'] ?? [];
        foreach ($scimsAll as $item) {
            $en = $item['entity_no'] ?? '';
            if ($en && !isset($merged[$en])) {
                if (stripos($item['entity_no'] ?? '', $search) !== false ||
                    stripos($item['juri_name'] ?? '', $search) !== false ||
                    stripos($item['juri_category'] ?? '', $search) !== false ||
                    stripos($item['juri_employer'] ?? '', $search) !== false) {
                    $merged[$en] = [
                        'id' => '',
                        'juridical' => [
                            'entity_no' => $en,
                            'name' => $item['juri_name'] ?? '',
                            'msme_category' => $item['juri_category'] ?? '',
                        ],
                        'employer' => [
                            'full_name' => $item['juri_employer'] ?? '',
                        ],
                    ];
                }
            }
        }
    }

    $allData = array_values($merged);
    $recordsFiltered = count($allData);
    $data = array_slice($allData, $start, $length);

} else {
    $stmt = $con->prepare("SELECT
        j.id, j.entity_no AS juri_entity_no, j.name AS juri_name,
        j.category AS juri_msme_category,
        e.first_name, e.middle_name, e.last_name
        FROM juridicals j
        LEFT JOIN employers e ON j.employer_id = e.id
        ORDER BY $orderBy $orderDir
        LIMIT ? OFFSET ?");
    $stmt->execute([$length, $start]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $fullName = trim(implode(' ', array_filter([$row['first_name'] ?? '', $row['middle_name'] ?? '', $row['last_name'] ?? ''])));
        $data[] = [
            'id' => $row['id'],
            'juridical' => [
                'entity_no' => $row['juri_entity_no'],
                'name' => $row['juri_name'],
                'msme_category' => $row['juri_msme_category'],
            ],
            'employer' => [
                'full_name' => $fullName,
            ],
        ];
    }

    $recordsFiltered = $totalRecords;
}

echo json_encode([
    'draw' => intval($draw),
    'recordsTotal' => intval($totalRecords),
    'recordsFiltered' => intval($recordsFiltered),
    'data' => $data,
]);

function getBusinessDetail($entityNo, $con) {
    $stmt = $con->prepare("SELECT
        j.id, j.entity_no AS juri_entity_no, j.name AS juri_name,
        j.registration_type AS juri_registration_type,
        j.bus_status AS juri_business_status,
        j.capitalization AS juri_capitalization,
        j.category AS juri_msme_category,
        j.contact_no AS juri_contact_no,
        j.contact_email AS juri_contact_email,
        j.line_of_industry AS juri_line_of_industry,
        j.special_category AS juri_special_category,
        e.entity_no AS emp_entity_no,
        e.first_name AS emp_first_name,
        e.middle_name AS emp_middle_name,
        e.last_name AS emp_last_name,
        e.special_category AS emp_special_category,
        a.street AS juri_street, a.subdivision AS juri_subdivision,
        a.barangay AS juri_barangay, a.city AS juri_city,
        a.province AS juri_province, a.region AS juri_region,
        ea.street AS emp_street, ea.subdivision AS emp_subdivision,
        ea.barangay AS emp_barangay, ea.city AS emp_city,
        ea.province AS emp_province, ea.region AS emp_region
        FROM juridicals j
        LEFT JOIN employers e ON j.employer_id = e.id
        LEFT JOIN addresses a ON j.address_id = a.id
        LEFT JOIN addresses ea ON e.address_id = ea.id
        WHERE j.entity_no = ?");
    $stmt->execute([$entityNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $fullName = trim(implode(' ', array_filter([$row['emp_first_name'] ?? '', $row['emp_middle_name'] ?? '', $row['emp_last_name'] ?? ''])));
        return ['status' => 'success', 'data' => [
            'id' => $row['id'],
            'juridical' => [
                'entity_no' => $row['juri_entity_no'],
                'name' => $row['juri_name'],
                'registration_type' => $row['juri_registration_type'],
                'business_status' => $row['juri_business_status'],
                'capitalization' => $row['juri_capitalization'],
                'msme_category' => $row['juri_msme_category'],
                'contact_no' => $row['juri_contact_no'],
                'contact_email' => $row['juri_contact_email'],
                'line_of_industry' => $row['juri_line_of_industry'],
                'special_category' => $row['juri_special_category'],
                'street' => $row['juri_street'],
                'subdivision' => $row['juri_subdivision'],
                'barangay' => $row['juri_barangay'],
                'city' => $row['juri_city'],
                'province' => $row['juri_province'],
                'region' => $row['juri_region'],
            ],
            'employer' => [
                'entity_no' => $row['emp_entity_no'],
                'full_name' => $fullName,
                'street' => $row['emp_street'],
                'subdivision' => $row['emp_subdivision'],
                'barangay' => $row['emp_barangay'],
                'city' => $row['emp_city'],
                'province' => $row['emp_province'],
                'region' => $row['emp_region'],
                'special_category' => $row['emp_special_category'] ?? '',
            ],
        ]];
    }

    $scimsRaw = @file_get_contents('https://vamosmobile.app/api/testjuridical/business');
    if ($scimsRaw) {
        $scimsAll = json_decode($scimsRaw, true)['data'] ?? [];
        foreach ($scimsAll as $item) {
            if (($item['entity_no'] ?? '') === $entityNo) {
                return ['status' => 'success', 'data' => [
                    'id' => '',
                    'juridical' => [
                        'entity_no' => $item['entity_no'],
                        'name' => $item['juri_name'] ?? '',
                        'registration_type' => $item['type_organization'] ?? '',
                        'business_status' => $item['status'] ?? '',
                        'capitalization' => '',
                        'msme_category' => $item['juri_category'] ?? '',
                        'contact_no' => $item['contact_no'] ?? '',
                        'contact_email' => $item['contact_email'] ?? '',
                        'line_of_industry' => $item['line_of_industry'] ?? '',
                        'special_category' => '',
                        'street' => $item['juri_street'] ?? '',
                        'subdivision' => $item['juri_subdivision'] ?? '',
                        'barangay' => $item['juri_barangay'] ?? '',
                        'city' => $item['juri_city'] ?? '',
                        'province' => $item['juri_province'] ?? '',
                        'region' => $item['juri_region'] ?? '',
                    ],
                    'employer' => [
                        'entity_no' => $item['employer_entity_no'] ?? '',
                        'full_name' => $item['juri_employer'] ?? '',
                        'street' => $item['emp_street'] ?? '',
                        'subdivision' => $item['emp_subdivision'] ?? '',
                        'barangay' => $item['emp_barangay'] ?? '',
                        'city' => $item['emp_city'] ?? '',
                        'province' => $item['emp_province'] ?? '',
                        'region' => $item['emp_region'] ?? '',
                    ],
                ]];
            }
        }
    }

    return ['status' => 'error', 'message' => 'Business not found.'];
}



function importBusiness($entityNo, $con) {
    $scimsRaw = @file_get_contents('https://vamosmobile.app/api/testjuridical/business');
    if (!$scimsRaw) {
        return ['status' => 'error', 'message' => 'Cannot connect to external API.'];
    }

    $scimsAll = json_decode($scimsRaw, true)['data'] ?? [];
    $item = null;
    foreach ($scimsAll as $b) {
        if (($b['entity_no'] ?? '') === $entityNo) {
            $item = $b;
            break;
        }
    }

    if (!$item) {
        return ['status' => 'error', 'message' => 'Business not found in external API.'];
    }

    try {
        $con->beginTransaction();

        $fullName = explode(' ', $item['juri_employer'] ?? '', 3);
        $stmt = $con->prepare("INSERT INTO employers 
            (entity_no, first_name, middle_name, last_name, address_id, special_category)
            VALUES (?, ?, ?, ?, NULL, ?)");
        $stmt->execute([
            $item['employer_entity_no'] ?? '',
            $fullName[0] ?? '',
            $fullName[1] ?? '',
            $fullName[2] ?? '',
            '',
        ]);
        $employerId = $con->lastInsertId();

        $stmt = $con->prepare("INSERT INTO juridicals
            (entity_no, name, registration_type, bus_status, capitalization,
             contact_no, contact_email, line_of_industry, special_category,
             employer_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $entityNo,
            $item['juri_name'] ?? '',
            $item['type_organization'] ?? '',
            $item['status'] ?? '',
            0,
            $item['contact_no'] ?? '',
            $item['contact_email'] ?? '',
            $item['line_of_industry'] ?? '',
            '',
            $employerId,
        ]);

        $con->commit();
        return ['status' => 'success', 'message' => 'Business imported successfully.'];

    } catch (Exception $e) {
        $con->rollBack();
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}