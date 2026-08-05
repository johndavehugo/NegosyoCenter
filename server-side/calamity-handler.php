<?php
require_once __DIR__ . '/../config/db_connect.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'detail') {
    $incidentId = intval($_GET['incident_id'] ?? 0);

    $stmt = $con->prepare("SELECT
        ci.id, ci.juridical_id, ci.calamity_id, ci.date_occurred,
        ci.nature_of_damage, ci.estimated_cost_of_damages, ci.status, ci.remarks,
        c.name AS calamity_name, c.calamity_type,
        j.entity_no AS juri_entity_no, j.name AS juri_name
        FROM calamity_incidents ci
        LEFT JOIN calamities c ON ci.calamity_id = c.id
        LEFT JOIN juridicals j ON ci.juridical_id = j.id
        WHERE ci.id = ?");
    $stmt->execute([$incidentId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode(['status' => 'success', 'data' => $row]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incident not found.']);
    }
    exit;
}


if ($action === 'affected') {
    $calamityId = intval($_GET['calamity_id'] ?? 0);

    $draw = intval($_GET['draw'] ?? 0);
    $start = intval($_GET['start'] ?? 0);
    $length = intval($_GET['length'] ?? 10);
    $search = $_GET['search']['value'] ?? '';
    $orderColumn = intval($_GET['order'][0]['column'] ?? 0);
    $orderDir = $_GET['order'][0]['dir'] ?? 'asc';

    $columns = ['j.name', 'j.entity_no', 'CONCAT(e.first_name, " ", e.middle_name, " ", e.last_name)', 'c.declaration_date', 'ci.nature_of_damage', 'ci.estimated_cost_of_damages', 'ci.status'];
    $orderBy = $columns[$orderColumn] ?? 'c.declaration_date';
    $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';

    $baseQuery = "FROM calamity_incidents ci
        LEFT JOIN juridicals j ON ci.juridical_id = j.id
        LEFT JOIN employers e ON j.employer_id = e.id
        LEFT JOIN calamities c ON ci.calamity_id = c.id
        WHERE ci.calamity_id = $calamityId";

    $totalStmt = $con->query("SELECT COUNT(*) $baseQuery");
    $totalRecords = $totalStmt->fetchColumn();

    $data = [];

    if (!empty($search)) {
        $like = '%' . $search . '%';

        $stmt = $con->prepare("SELECT
            ci.id, c.declaration_date, ci.nature_of_damage,
            ci.estimated_cost_of_damages, ci.status, ci.remarks,
            j.entity_no AS juri_entity_no, j.name AS juri_name,
            e.first_name, e.middle_name, e.last_name
            $baseQuery
            AND (j.name LIKE ? OR j.entity_no LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?
                OR ci.nature_of_damage LIKE ? OR ci.status LIKE ?)
            ORDER BY $orderBy $orderDir
            LIMIT ? OFFSET ?");
        $stmt->execute([$like, $like, $like, $like, $like, $like, $length, $start]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $con->prepare("SELECT COUNT(*)
            $baseQuery
            AND (j.name LIKE ? OR j.entity_no LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?
                OR ci.nature_of_damage LIKE ? OR ci.status LIKE ?)");
        $countStmt->execute([$like, $like, $like, $like, $like, $like]);
        $recordsFiltered = $countStmt->fetchColumn();
    } else {
        $stmt = $con->prepare("SELECT
            ci.id, c.declaration_date, ci.date_occurred, ci.nature_of_damage,
            ci.estimated_cost_of_damages, ci.status, ci.remarks,
            j.entity_no AS juri_entity_no, j.name AS juri_name,
            e.first_name, e.middle_name, e.last_name
            $baseQuery
            ORDER BY $orderBy $orderDir
            LIMIT ? OFFSET ?");
        $stmt->execute([$length, $start]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $recordsFiltered = $totalRecords;
    }

    foreach ($rows as $row) {
        $fullName = trim(implode(' ', array_filter([$row['first_name'] ?? '', $row['middle_name'] ?? '', $row['last_name'] ?? ''])));
        $data[] = [
            'id'                       => $row['id'],
            'declaration_date'         => $row['date_occurred'],
            'nature_of_damage'         => $row['nature_of_damage'],
            'estimated_cost_of_damages'=> $row['estimated_cost_of_damages'],
            'status'                   => $row['status'],
            'remarks'                  => $row['remarks'],
            'entity_no'                => $row['juri_entity_no'],
            'business_name'            => $row['juri_name'],
            'owner_full_name'          => $fullName,
        ];
    }

    echo json_encode([
        'draw' => intval($draw),
        'recordsTotal' => intval($totalRecords),
        'recordsFiltered' => intval($recordsFiltered),
        'data' => $data,
    ]);
    exit;
}

$draw = intval($_GET['draw'] ?? 0);
$start = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? 10);
$search = $_GET['search']['value'] ?? '';
$orderColumn = intval($_GET['order'][0]['column'] ?? 0);
$orderDir = $_GET['order'][0]['dir'] ?? 'asc';

$columns = ['c.name', 'c.calamity_type', 'c.declaration_date', 'c.id'];
$orderBy = $columns[$orderColumn] ?? 'c.name';
$orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';

$baseQuery = "FROM calamities c
    LEFT JOIN calamity_incidents ci ON ci.calamity_id = c.id";

$totalStmt = $con->query("SELECT COUNT(DISTINCT c.id) $baseQuery");
$totalRecords = $totalStmt->fetchColumn();

$data = [];

if (!empty($search)) {
    $like = '%' . $search . '%';

    $stmt = $con->prepare("SELECT
        c.id, c.name, c.calamity_type, c.declaration_date, c.description,
        COUNT(ci.id) AS affected_count
        $baseQuery
        WHERE c.name LIKE ? OR c.calamity_type LIKE ?
        GROUP BY c.id
        ORDER BY $orderBy $orderDir
        LIMIT ? OFFSET ?");
    $stmt->execute([$like, $like, $length, $start]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countStmt = $con->prepare("SELECT COUNT(DISTINCT c.id) $baseQuery
        WHERE c.name LIKE ? OR c.calamity_type LIKE ?");
    $countStmt->execute([$like, $like]);
    $recordsFiltered = $countStmt->fetchColumn();
} else {
    $stmt = $con->prepare("SELECT
        c.id, c.name, c.calamity_type, c.declaration_date, c.description,
        COUNT(ci.id) AS affected_count
        $baseQuery
        GROUP BY c.id
        ORDER BY $orderBy $orderDir
        LIMIT ? OFFSET ?");
    $stmt->execute([$length, $start]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $recordsFiltered = $totalRecords;
}

foreach ($rows as $row) {
    $data[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'calamity_type' => $row['calamity_type'],
        'declaration_date' => $row['declaration_date'],
        'description' => $row['description'],
        'affected_count' => intval($row['affected_count']),
    ];
}

echo json_encode([
    'draw' => intval($draw),
    'recordsTotal' => intval($totalRecords),
    'recordsFiltered' => intval($recordsFiltered),
    'data' => $data,
]);
