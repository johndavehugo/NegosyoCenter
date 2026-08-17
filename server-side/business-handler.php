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