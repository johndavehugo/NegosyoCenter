<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';

class PriceMonitoringController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Get all monitored prices with linked business information
     * @return array 
     */
    public function getPrices() {
    try {
        $sql = "SELECT
    p.id,
    p.juridical_id,
    p.commodity_id,
    p.prevailing_price,
    c.srp AS srp,
    p.status,
    p.monitored_by_agency_id,
    p.monitored_at,

    c.product_name,
    cc.name AS category_name,
    c.brand_name,
    c.unit_of_measure,

    j.entity_no AS juri_entity_no,
    j.name AS juri_name,
    a.code AS agency_code

FROM price_logs p
LEFT JOIN commodities c ON p.commodity_id = c.id
LEFT JOIN commodity_categories cc ON c.category_id = cc.id
LEFT JOIN juridicals j ON p.juridical_id = j.id
LEFT JOIN agencies a ON p.monitored_by_agency_id = a.id";

        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'status' => 'success',
            'data' => $rows ?: []
        ];

    } catch (PDOException $e) {
        return [
            'status' => 'error', 
            'message' => 'Database error: ' . $e->getMessage(),
            'data' => []
        ];
    }
}

    /**
     * Get price monitoring details for a single record by ID or Entity Number
     * @param string|int $id
     * @return array
     */
    public function getPriceById($id) {
        try {
            $stmt = $this->con->prepare("SELECT 
                        p.*, 
                        c.product_name,
                        COALESCE(p.category_name, c.category_name) AS category_name,
                        COALESCE(p.brand_name, c.brand_name) AS brand_name,
                        COALESCE(p.unit_of_measure, c.unit_of_measure) AS unit_of_measure,
                        c.srp AS commodity_srp,
                        j.name AS juri_name,
                        a.code AS agency_code
                    FROM price_logs p 
                    LEFT JOIN commodities c ON p.commodity_id = c.id
                    LEFT JOIN juridicals j ON p.juridical_id = j.id 
                    LEFT JOIN agencies a ON p.monitored_by_agency_id = a.id 
                    WHERE p.id = ? OR j.entity_no = ?");
            $stmt->execute([$id, $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return ['status' => 'error', 'message' => 'Price record not found.'];
            }

            return [
                'status' => 'success',
                'data' => $row
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Add new price monitoring record
     * @param array $data
     * @return array
     */
    public function addPrice(array $data) {
        $commodity_id         = trim($data['commodity_id'] ?? '');
        $monitored_by_agency_id = trim($data['monitored_by_agency_id'] ?? '');
        $prevailing_price     = trim($data['prevailing_price'] ?? 0);
        $status               = trim($data['status'] ?? 'WITHIN_SRP');

        if (empty($commodity_id) || empty($monitored_by_agency_id)) {
            return ['status' => 'error', 'message' => 'Commodity and agency are required.'];
        }

        try {
            $commodityStmt = $this->con->prepare("SELECT product_name, category_name, brand_name, unit_of_measure, srp, category_id FROM commodities WHERE id = ?");
            $commodityStmt->execute([$commodity_id]);
            $commodity = $commodityStmt->fetch(PDO::FETCH_ASSOC);

            if (!$commodity) {
                return ['status' => 'error', 'message' => 'Selected commodity not found.'];
            }

            $agencyStmt = $this->con->prepare("SELECT code FROM agencies WHERE id = ?");
            $agencyStmt->execute([$monitored_by_agency_id]);
            $agency = $agencyStmt->fetch(PDO::FETCH_ASSOC);

            $agency_code = $agency['code'] ?? '';

            $sql = "INSERT INTO price_logs (commodity_id, monitored_by_agency_id, product_name, category_name, brand_name, unit_of_measure, srp, agency_code, prevailing_price, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([
                $commodity_id,
                $monitored_by_agency_id,
                $commodity['product_name'],
                $commodity['category_name'],
                $commodity['brand_name'],
                $commodity['unit_of_measure'],
                $commodity['srp'],
                $agency_code,
                $prevailing_price,
                $status
            ]);

            return ['status' => 'success', 'message' => 'Price record has been added.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update existing price monitoring record
     * @param array $data
     * @return array
     */
    public function updatePrice(array $data) {
        $id                   = trim($data['id'] ?? '');
        $commodity_id         = trim($data['commodity_id'] ?? '');
        $monitored_by_agency_id = trim($data['monitored_by_agency_id'] ?? '');
        $prevailing_price     = trim($data['prevailing_price'] ?? 0);
        $status               = trim($data['status'] ?? 'WITHIN_SRP');

        if (empty($id)) {
            return ['status' => 'error', 'message' => 'Missing record ID for update.'];
        }

        if (empty($commodity_id) || empty($monitored_by_agency_id)) {
            return ['status' => 'error', 'message' => 'Commodity and agency are required for update.'];
        }

        try {
            $commodityStmt = $this->con->prepare("SELECT product_name, category_name, brand_name, unit_of_measure, srp FROM commodities WHERE id = ?");
            $commodityStmt->execute([$commodity_id]);
            $commodity = $commodityStmt->fetch(PDO::FETCH_ASSOC);

            if (!$commodity) {
                return ['status' => 'error', 'message' => 'Selected commodity not found.'];
            }

            $agencyStmt = $this->con->prepare("SELECT code FROM agencies WHERE id = ?");
            $agencyStmt->execute([$monitored_by_agency_id]);
            $agency = $agencyStmt->fetch(PDO::FETCH_ASSOC);
            $agency_code = $agency['code'] ?? '';

            $sql = "UPDATE price_logs 
                    SET commodity_id = ?, monitored_by_agency_id = ?, product_name = ?, category_name = ?, brand_name = ?, unit_of_measure = ?, srp = ?, agency_code = ?, prevailing_price = ?, status = ? 
                    WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([
                $commodity_id,
                $monitored_by_agency_id,
                $commodity['product_name'],
                $commodity['category_name'],
                $commodity['brand_name'],
                $commodity['unit_of_measure'],
                $commodity['srp'],
                $agency_code,
                $prevailing_price,
                $status,
                $id
            ]);

            return ['status' => 'success', 'message' => 'Price record updated successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function deletePrice($id) {
        if (empty($id)) {
            return ['status' => 'error', 'message' => 'Missing record ID for delete.'];
        }

        try {
            $stmt = $this->con->prepare("DELETE FROM price_logs WHERE id = ?");
            $stmt->execute([$id]);

            return ['status' => 'success', 'message' => 'Price record deleted successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function getAgencies() {
        try {
            $stmt = $this->con->prepare("SELECT id, code, name FROM agencies ORDER BY code ASC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $rows ?: []];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function getCommodities() {
        try {
            $stmt = $this->con->prepare("SELECT id, product_name, brand_name, unit_of_measure, category_id, category_name, srp FROM commodities ORDER BY product_name ASC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $rows ?: []];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}