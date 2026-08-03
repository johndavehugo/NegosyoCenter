<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';


class CalamityController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    public function getCalamities() {
        try {
            $stmt = $this->con->prepare("SELECT id, name, calamity_type, declaration_date FROM calamities ORDER BY declaration_date DESC LIMIT 10");
            $stmt->execute();
            return ['status' => 'success', 'data' => $stmt->fetchAll()];
        } catch (PDOException $e) {
            error_log('CalamityController::getCalamities - ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Unable to retrieve calamities. Please try again later.'];
        }
    }

    public function addCalamity(array $data) {
        $name             = trim($data['name'] ?? '');
        $calamity_type    = trim($data['calamity_type'] ?? '');
        $declaration_date = trim($data['declaration_date'] ?? '');
        $description      = trim($data['description'] ?? '');

        if (empty($name) || empty($calamity_type) || empty($declaration_date)) {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }

        try {
            $stmt = $this->con->prepare("INSERT INTO calamities
                (name, calamity_type, declaration_date, description)
                VALUES
                (?, ?, ?, ?)");
            $stmt->execute([$name, $calamity_type, $declaration_date, $description]);
            return ['status' => 'success', 'message' => 'Calamity has been added.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function getJuridicals() {
        try {
            $stmt = $this->con->prepare("SELECT
                j.id, j.entity_no, j.name,
                e.first_name, e.middle_name, e.last_name
                FROM juridicals j
                LEFT JOIN employers e ON j.employer_id = e.id
                ORDER BY j.name");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $list = [];
            foreach ($rows as $row) {
                $fullName = implode(' ', array_filter([
                    trim($row['first_name'] ?? ''),
                    trim($row['middle_name'] ?? ''),
                    trim($row['last_name'] ?? '')
                ]));
                $list[] = [
                    'id' => $row['id'],
                    'entity_no' => $row['entity_no'],
                    'name' => $row['name'],
                    'owner_full_name' => $fullName,
                ];
            }
            return ['status' => 'success', 'data' => $list];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function addIncident(array $data) {
        $juridical_id              = trim($data['juridical_id'] ?? '');
        $calamity_id               = trim($data['calamity_id'] ?? '');
        $date_occurred             = trim($data['date_occurred'] ?? '');
        $nature_of_damage          = trim($data['nature_of_damage'] ?? '');
        $estimated_cost_of_damages = trim($data['estimated_cost_of_damages'] ?? '');
        $remarks                   = trim($data['remarks'] ?? '');
        $status                    = trim($data['status'] ?? 'VERIFIED');

        if (empty($juridical_id) || empty($calamity_id) || empty($date_occurred) || empty($nature_of_damage) || $estimated_cost_of_damages === '') {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }

        try {
            $stmt = $this->con->prepare("INSERT INTO calamity_incidents
                (juridical_id, calamity_id, date_occurred, nature_of_damage, estimated_cost_of_damages, remarks, status)
                VALUES
                (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$juridical_id, $calamity_id, $date_occurred, $nature_of_damage, $estimated_cost_of_damages, $remarks, $status]);
            return ['status' => 'success', 'message' => 'Calamity incident has been added.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
  
    public function updateIncident(array $data) {
        $id                        = trim($data['id'] ?? '');
        $juridical_id              = trim($data['juridical_id'] ?? '');
        $calamity_id               = trim($data['calamity_id'] ?? '');
        $date_occurred             = trim($data['date_occurred'] ?? '');
        $nature_of_damage          = trim($data['nature_of_damage'] ?? '');
        $estimated_cost_of_damages = trim($data['estimated_cost_of_damages'] ?? '');
        $remarks                   = trim($data['remarks'] ?? '');
        $status                    = trim($data['status'] ?? 'VERIFIED');

        if (empty($id)) {
            return ['status' => 'error', 'message' => 'Incident ID is required.'];
        }
        if (empty($juridical_id) || empty($calamity_id) || empty($date_occurred) || empty($nature_of_damage) || $estimated_cost_of_damages === '') {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }

        try {
            $stmt = $this->con->prepare("UPDATE calamity_incidents SET
                juridical_id = ?, calamity_id = ?, date_occurred = ?,
                nature_of_damage = ?, estimated_cost_of_damages = ?,
                remarks = ?, status = ?
                WHERE id = ?");
            $stmt->execute([$juridical_id, $calamity_id, $date_occurred, $nature_of_damage, $estimated_cost_of_damages, $remarks, $status, $id]);
            return ['status' => 'success', 'message' => 'Calamity incident has been updated.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
