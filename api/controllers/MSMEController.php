<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';


class MSMEController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * @param array
     * @return array 
     */

    public function getBusinesses() {
        try {
            $stmt = $this->con->prepare("SELECT 
                j.entity_no AS juri_entity_no,
                j.name AS juri_name,
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
                a.upblb_num AS juri_upblb_num,
                a.street AS juri_street,
                a.subdivision AS juri_subdivision,
                a.barangay AS juri_barangay,
                a.city AS juri_city,
                a.province AS juri_province,
                a.region AS juri_region,
                ea.upblb_num AS emp_upblb_num,
                ea.street AS emp_street,
                ea.subdivision AS emp_subdivision,
                ea.barangay AS emp_barangay,
                ea.city AS emp_city,
                ea.province AS emp_province,
                ea.region AS emp_region
                FROM juridicals j
                LEFT JOIN employers e ON j.employer_id = e.id
                LEFT JOIN addresses a ON j.address_id = a.id
                LEFT JOIN addresses ea ON e.address_id = ea.id");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $businesses = [];
            foreach ($rows as $row) {

                $fullName = implode(' ', array_filter([
                    trim($row['emp_first_name'] ?? ''),
                    trim($row['emp_middle_name'] ?? ''),
                    trim($row['emp_last_name'] ?? '')
                ]));

                $businesses[] = [
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
                    'upblb_num' => $row['juri_upblb_num'],
                    'street' => $row['juri_street'],
                    'subdivision' => $row['juri_subdivision'],
                    'barangay' => $row['juri_barangay'],
                    'city' => $row['juri_city'],
                    'province' => $row['juri_province'],
                    'region' => $row['juri_region'],
                ],
                'employer' => [
                    'entity_no' => $row['emp_entity_no'],
                    'first_name' => $row['emp_first_name'],
                    'middle_name' => $row['emp_middle_name'],
                    'last_name' => $row['emp_last_name'],
                    'upblb_num' => $row['emp_upblb_num'],
                    'street' => $row['emp_street'],
                    'subdivision' => $row['emp_subdivision'],
                    'barangay' => $row['emp_barangay'],
                    'city' => $row['emp_city'],
                    'province' => $row['emp_province'],
                    'region' => $row['emp_region'],
                    'full_name' => $fullName,
                ],
            ];
        }
            return ['status' => 'success', 'data' => $businesses];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}