<?php
require_once __DIR__ . '/../config/db_connect.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'scims_businesses') {
    $q = trim($_GET['q'] ?? '');
    if ($q === '') {
        echo json_encode(['status' => 'success', 'data' => []]);
        exit;
    }
    $raw = @file_get_contents('https://vamosmobile.app/api/juridical/business/businessname/' . urlencode($q));
    if (!$raw) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot connect to SCIMS API.']);
        exit;
    }
    $results = [];
    foreach (json_decode($raw, true)['data'] ?? [] as $x) {
        $results[] = [

            //Business
            'juri_entity_no' => $x['entity_no'] ?? '',
            'juri_name' => $x['juri_name'] ?? '',
            'juri_category' => $x['juri_category'] ?? '',
            'line_of_industry' => $x['line_of_industry'] ?? '',
            'juri_contact_no' => $x['contact_no'] ?? '',
            'juri_contact_email' => $x['contact_email'] ?? '',
            'juri_region' => $x['juri_region'] ?? '',
            'juri_province' => $x['juri_province'] ?? '',
            'juri_city' => $x['juri_city'] ?? '',
            'juri_barangay' => $x['juri_barangay'] ?? '',
            'juri_street' => $x['juri_street'] ?? '',
            'juri_subdivision' => $x['juri_subdivision'] ?? '',
            'juri_upblb_num' => $x['juri_upblb_num'] ?? '',
            'juri_address_id' => $x['juri_address_id'] ?? '',
            
            //Owner
            
            'emp_id' => $x['juri_employer_id'] ?? '',
            'emp_name' => $x['trade_name'] ?? '',
            'emp_entity_no' => $x['employer_entity_no'] ?? '',
            'emp_region' => $x['emp_region'] ?? '',
            'emp_province' => $x['emp_province'] ?? '',
            'emp_city' => $x['emp_city'] ?? '',
            'emp_barangay' => $x['emp_barangay'] ?? '',
            'emp_street' => $x['emp_street'] ?? '',
            'emp_subdivision' => $x['emp_subdivision'] ?? '',
            'emp_upblb_num' => $x['emp_upblb_num'] ?? '',
            'emp_address_id' => $x['emp_address_id'] ?? '',
            

        ];
    }
    echo json_encode(['status' => 'success', 'data' => $results]);
    exit;
}

// ============================================================
// DASHBOARD ACTIONS
// ============================================================

// ── dashboard_sectors ────────────────────────────────────────
// Returns distinct line_of_industry values for the filter dropdown.
// GET ?action=dashboard_sectors
// ─────────────────────────────────────────────────────────────
if ($action === 'dashboard_sectors') {
    try {
        $stmt = $con->query(
            "SELECT DISTINCT TRIM(line_of_industry) AS sector
             FROM juridicals
             WHERE line_of_industry IS NOT NULL
               AND TRIM(line_of_industry) <> ''
             ORDER BY sector ASC"
        );
        $sectors = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'sector');
        echo json_encode(['status' => 'success', 'sectors' => $sectors]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── dashboard_stats ──────────────────────────────────────────
// Returns filtered aggregates: total, byCategory, active/inactive.
// GET ?action=dashboard_stats[&classification=][&status=][&sector=]
// ─────────────────────────────────────────────────────────────
if ($action === 'dashboard_stats') {
    try {
        $classification = trim($_GET['classification'] ?? '');
        $status         = trim($_GET['status']         ?? '');
        $sector         = trim($_GET['sector']         ?? '');

        $conditions = [];
        $params     = [];

        if ($classification !== '') {
            $conditions[] = 'LOWER(j.category) = LOWER(?)';
            $params[]     = $classification;
        }
        if ($status !== '') {
            $conditions[] = 'LOWER(j.bus_status) = LOWER(?)';
            $params[]     = $status;
        }
        if ($sector !== '') {
            $conditions[] = 'j.line_of_industry = ?';
            $params[]     = $sector;
        }

        $where = count($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';

        // Total
        $stmtTotal = $con->prepare("SELECT COUNT(*) FROM juridicals j" . $where);
        $stmtTotal->execute($params);
        $total = (int) $stmtTotal->fetchColumn();

        // By category
        $stmtCat = $con->prepare(
            "SELECT
                CASE
                    WHEN LOWER(j.category) = 'micro'  THEN 'Micro'
                    WHEN LOWER(j.category) = 'small'  THEN 'Small'
                    WHEN LOWER(j.category) = 'medium' THEN 'Medium'
                    WHEN LOWER(j.category) = 'large'  THEN 'Large'
                    ELSE 'Other'
                END AS label,
                COUNT(*) AS cnt
             FROM juridicals j" . $where . "
             GROUP BY label
             ORDER BY FIELD(label,'Micro','Small','Medium','Large','Other')"
        );
        $stmtCat->execute($params);
        $byCategory = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        // By sector (top 10)
        $stmtSec = $con->prepare(
            "SELECT
                COALESCE(NULLIF(TRIM(j.line_of_industry),''), 'Unspecified') AS label,
                COUNT(*) AS cnt
             FROM juridicals j" . $where . "
             GROUP BY label
             ORDER BY cnt DESC
             LIMIT 10"
        );
        $stmtSec->execute($params);
        $bySector = $stmtSec->fetchAll(PDO::FETCH_ASSOC);

        // Active / Inactive breakdown (always within current filter scope)
        $stmtActive = $con->prepare(
            "SELECT
                SUM(CASE WHEN LOWER(j.bus_status) = 'active'   THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN LOWER(j.bus_status) = 'inactive' THEN 1 ELSE 0 END) AS inactive
             FROM juridicals j" . $where
        );
        $stmtActive->execute($params);
        $statusRow = $stmtActive->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'status'     => 'success',
            'total'      => $total,
            'active'     => (int) ($statusRow['active']   ?? 0),
            'inactive'   => (int) ($statusRow['inactive'] ?? 0),
            'byCategory' => array_values($byCategory),
            'bySector'   => array_values($bySector),
        ]);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// ── export_report ────────────────────────────────────────────
// Streams a downloadable CSV or PDF report of the filtered dataset.
// GET ?action=export_report[&classification=][&status=][&sector=][&format=csv|pdf]
// ─────────────────────────────────────────────────────────────
if ($action === 'export_report') {
    try {
        $classification = trim($_GET['classification'] ?? '');
        $status         = trim($_GET['status']         ?? '');
        $sector         = trim($_GET['sector']         ?? '');
        $format         = strtolower(trim($_GET['format'] ?? 'csv'));

        $conditions = [];
        $params     = [];

        if ($classification !== '') {
            $conditions[] = 'LOWER(j.category) = LOWER(?)';
            $params[]     = $classification;
        }
        if ($status !== '') {
            $conditions[] = 'LOWER(j.bus_status) = LOWER(?)';
            $params[]     = $status;
        }
        if ($sector !== '') {
            $conditions[] = 'j.line_of_industry = ?';
            $params[]     = $sector;
        }

        $where = count($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';

        $stmt = $con->prepare(
            "SELECT
                j.entity_no,
                j.name,
                j.category,
                j.bus_status,
                j.registration_type,
                j.line_of_industry,
                j.capitalization,
                j.contact_no,
                j.contact_email,
                e.full_name AS owner_name
             FROM juridicals j
             LEFT JOIN employers e ON j.employer_id = e.id" .
            $where .
            " ORDER BY j.name ASC"
        );
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filterLabel  = 'Classification: ' . ($classification ?: 'All');
        $filterLabel .= ' | Status: '       . ($status         ?: 'All');
        $filterLabel .= ' | Sector: '       . ($sector         ?: 'All');
        $dateLabel    = date('F d, Y');

        // ── CSV export ────────────────────────────────────────────────
        if ($format === 'csv') {
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="MSME_Report_' . date('Ymd') . '.csv"');
            header('Cache-Control: no-cache, no-store, must-revalidate');

            $out = fopen('php://output', 'w');
            // BOM for Excel UTF-8 compatibility
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['San Carlos City Negosyo Center — MSME Report']);
            fputcsv($out, [$filterLabel]);
            fputcsv($out, ['Generated: ' . $dateLabel]);
            fputcsv($out, []);
            fputcsv($out, ['Entity No.', 'Business Name', 'Classification', 'Status',
                           'Registration Type', 'Sector / Line of Industry',
                           'Capitalization', 'Contact No.', 'Email', 'Owner Name']);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['entity_no'],
                    $r['name'],
                    $r['category'],
                    $r['bus_status'],
                    $r['registration_type'],
                    $r['line_of_industry'],
                    $r['capitalization'],
                    $r['contact_no'],
                    $r['contact_email'],
                    $r['owner_name'],
                ]);
            }
            fclose($out);
            exit;
        }

        // ── PDF export via TCPDF ──────────────────────────────────────
        if ($format === 'pdf') {
            $tcpdfPath = __DIR__ . '/../plugins/TCPDF-6.7.5/tcpdf.php';
            if (!file_exists($tcpdfPath)) {
                echo json_encode(['status' => 'error', 'message' => 'TCPDF not found.']);
                exit;
            }
            require_once $tcpdfPath;

            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('Negosyo Center NCIMS');
            $pdf->SetAuthor('San Carlos City');
            $pdf->SetTitle('MSME Report');
            $pdf->SetSubject($filterLabel);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(true, 10);
            $pdf->AddPage();

            // Title block
            $pdf->SetFont('helvetica', 'B', 13);
            $pdf->Cell(0, 7, 'San Carlos City Negosyo Center — MSME Report', 0, 1, 'C');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(0, 5, $filterLabel, 0, 1, 'C');
            $pdf->Cell(0, 5, 'Generated: ' . $dateLabel, 0, 1, 'C');
            $pdf->Ln(3);

            // Table header
            $pdf->SetFillColor(52, 58, 64);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('helvetica', 'B', 8);
            $colW = [28, 60, 22, 20, 25, 50, 25, 28, 50, 40];
            $headers = ['Entity No.', 'Business Name', 'Category', 'Status',
                        'Reg. Type', 'Sector', 'Capital', 'Contact', 'Email', 'Owner'];
            foreach ($headers as $i => $h) {
                $pdf->Cell($colW[$i], 6, $h, 1, 0, 'C', true);
            }
            $pdf->Ln();

            // Table rows
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 7);
            $fill = false;
            foreach ($rows as $r) {
                $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);
                $cells = [
                    $r['entity_no'], $r['name'], $r['category'], $r['bus_status'],
                    $r['registration_type'], $r['line_of_industry'],
                    number_format((float)($r['capitalization'] ?? 0), 2),
                    $r['contact_no'], $r['contact_email'], $r['owner_name']
                ];
                foreach ($cells as $i => $val) {
                    $pdf->Cell($colW[$i], 5, $val, 1, 0, 'L', true);
                }
                $pdf->Ln();
                $fill = !$fill;
            }

            // Summary footer
            $pdf->Ln(3);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->Cell(0, 5, 'Total Records: ' . count($rows), 0, 1, 'R');

            $pdf->Output('MSME_Report_' . date('Ymd') . '.pdf', 'D');
            exit;
        }

        // Unknown format
        echo json_encode(['status' => 'error', 'message' => 'Unknown format. Use csv or pdf.']);
        exit;

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}
