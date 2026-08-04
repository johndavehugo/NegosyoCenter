<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';

class PriceMonitoringController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    public function getPrices() {
        try {
            $sql = "SELECT
                p.id,
                p.commodity_id,
                p.prevailing_price,
                p.status,
                p.monitored_at,
                p.monitored_by_agency_id,
                c.product_name,
                cc.name AS category_name,
                c.brand_name,
                c.unit_of_measure,
                c.srp,
                a.code AS agency_code
            FROM price_logs p
            LEFT JOIN commodities c ON p.commodity_id = c.id
            LEFT JOIN commodity_categories cc ON c.category_id = cc.id
            LEFT JOIN agencies a ON p.monitored_by_agency_id = a.id
            ORDER BY p.monitored_at DESC, p.id DESC";

            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return [
                'status' => 'success',
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function getPriceById($id) {
        if (empty($id)) {
            return ['status' => 'error', 'message' => 'Missing record ID.'];
        }

        try {
            $sql = "SELECT
                p.id,
                p.commodity_id,
                p.prevailing_price,
                p.status,
                p.monitored_at,
                p.monitored_by_agency_id,
                c.product_name,
                cc.name AS category_name,
                c.brand_name,
                c.unit_of_measure,
                c.srp,
                a.code AS agency_code
            FROM price_logs p
            LEFT JOIN commodities c ON p.commodity_id = c.id
            LEFT JOIN commodity_categories cc ON c.category_id = cc.id
            LEFT JOIN agencies a ON p.monitored_by_agency_id = a.id
            WHERE p.id = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
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

    public function addPrice(array $data) {
        $commodity_id = trim($data['commodity_id'] ?? '');
        $monitored_by_agency_id = trim($data['monitored_by_agency_id'] ?? '');
        $prevailing_price = trim($data['prevailing_price'] ?? 0);
        $status = trim($data['status'] ?? 'WITHIN_SRP');

        if (empty($commodity_id) || empty($monitored_by_agency_id)) {
            return ['status' => 'error', 'message' => 'Commodity and agency are required.'];
        }

        try {
            $commodityStmt = $this->con->prepare("SELECT
                c.product_name,
                cc.name AS category_name,
                c.brand_name,
                c.unit_of_measure,
                c.srp,
                c.category_id
            FROM commodities c
            LEFT JOIN commodity_categories cc ON c.category_id = cc.id
            WHERE c.id = ?");
            $commodityStmt->execute([$commodity_id]);
            $commodity = $commodityStmt->fetch(PDO::FETCH_ASSOC);

            if (!$commodity) {
                return ['status' => 'error', 'message' => 'Selected commodity not found.'];
            }

            $sql = "INSERT INTO price_logs (commodity_id, monitored_by_agency_id, prevailing_price, status, monitored_at)
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$commodity_id, $monitored_by_agency_id, $prevailing_price, $status]);

            return ['status' => 'success', 'message' => 'Price record has been added.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function updatePrice(array $data) {
        $id = trim($data['id'] ?? '');
        $commodity_id = trim($data['commodity_id'] ?? '');
        $monitored_by_agency_id = trim($data['monitored_by_agency_id'] ?? '');
        $prevailing_price = trim($data['prevailing_price'] ?? 0);
        $status = trim($data['status'] ?? 'WITHIN_SRP');

        if (empty($id)) {
            return ['status' => 'error', 'message' => 'Missing record ID for update.'];
        }

        if (empty($commodity_id) || empty($monitored_by_agency_id)) {
            return ['status' => 'error', 'message' => 'Commodity and agency are required for update.'];
        }

        try {
            $sql = "UPDATE price_logs
                    SET commodity_id = ?, monitored_by_agency_id = ?, prevailing_price = ?, status = ?, monitored_at = NOW()
                    WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$commodity_id, $monitored_by_agency_id, $prevailing_price, $status, $id]);

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
            $sql = "SELECT
                c.id,
                c.product_name,
                c.brand_name,
                c.unit_of_measure,
                c.category_id,
                cc.name AS category_name,
                c.srp
            FROM commodities c
            LEFT JOIN commodity_categories cc ON c.category_id = cc.id
            ORDER BY c.product_name ASC";

            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return [
                'status' => 'success',
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
