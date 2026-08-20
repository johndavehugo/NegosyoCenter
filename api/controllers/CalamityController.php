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
                e.full_name
                FROM juridicals j
                LEFT JOIN employers e ON j.employer_id = e.id
                ORDER BY j.name");
            $stmt->execute();
            return ['status' => 'success', 'data' => $this->mapJuridicals($stmt->fetchAll())];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function searchJuridicals(array $data) {
        $q = trim($data['q'] ?? '');

        try {
            if ($q === '') {
                $stmt = $this->con->prepare("SELECT
                    j.id, j.entity_no, j.name,
                    e.full_name
                    FROM juridicals j
                    LEFT JOIN employers e ON j.employer_id = e.id
                    ORDER BY j.name
                    LIMIT 50");
                $stmt->execute();
            } else {
                $like = '%' . $q . '%';
                $stmt = $this->con->prepare("SELECT
                    j.id, j.entity_no, j.name,
                    e.full_name
                    FROM juridicals j
                    LEFT JOIN employers e ON j.employer_id = e.id
                    WHERE j.name LIKE ?
                       OR j.entity_no LIKE ?
                       OR e.full_name LIKE ?
                    ORDER BY j.name
                    LIMIT 50");
                $stmt->execute([$like, $like, $like]);
            }
            return ['status' => 'success', 'data' => $this->mapJuridicals($stmt->fetchAll())];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    private function mapJuridicals(array $rows) {
        $list = [];
        foreach ($rows as $row) {
            $list[] = [
                'id' => $row['id'],
                'entity_no' => $row['entity_no'],
                'name' => $row['name'],
                'owner_full_name' => trim($row['full_name'] ?? ''),
            ];
        }
        return $list;
    }

    public function addIncident(array $data) {
        $calamity_id = trim($data['calamity_id'] ?? '');

        $juridical_ids = $data['juridical_ids'] ?? [];
        if (!is_array($juridical_ids)) {
            $juridical_ids = [$juridical_ids];
        }
        $juridical_ids = array_values(array_filter(array_map('trim', $juridical_ids), 'strlen'));

        $asArray = function ($value) {
            if (is_array($value)) {
                return array_values($value);
            }
            return $value === null || $value === '' ? [] : [$value];
        };

        $dates   = $asArray($data['date_occurred'] ?? []);
        $natures = $asArray($data['nature_of_damage'] ?? []);
        $statuses = $asArray($data['status'] ?? []);
        $costs   = $asArray($data['estimated_cost_of_damages'] ?? []);
        $remarks = $asArray($data['remarks'] ?? []);

        if (empty($juridical_ids) || empty($calamity_id)) {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }

        $perBusiness = [];
        $total = 0.0;
        foreach ($juridical_ids as $i => $jid) {
            $date = trim($dates[$i] ?? '');
            $nature = trim($natures[$i] ?? '');
            $status = trim($statuses[$i] ?? '') ?: 'VERIFIED';
            $remark = trim($remarks[$i] ?? '');
            $cost = isset($costs[$i]) && is_numeric($costs[$i]) && $costs[$i] >= 0 ? (float) $costs[$i] : null;

            if (empty($date) || empty($nature) || $cost === null) {
                return ['status' => 'error', 'message' => 'Please provide the date of occurrence, nature of damage, and estimated damage for every affected business.'];
            }

            $perBusiness[] = [
                'juridical_id' => $jid,
                'date_occurred' => $date,
                'nature_of_damage' => $nature,
                'status' => $status,
                'remarks' => $remark,
                'cost' => $cost,
            ];
            $total += $cost;
        }

        $first = $perBusiness[0];

        $this->con->beginTransaction();

        try {
            $stmt = $this->con->prepare("INSERT INTO calamity_incidents
                (juridical_id, calamity_id, date_occurred, nature_of_damage, estimated_cost_of_damages, remarks, status)
                VALUES
                (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $first['juridical_id'],
                $calamity_id,
                $first['date_occurred'],
                $first['nature_of_damage'],
                $total,
                $first['remarks'],
                $first['status']
            ]);
            $incidentId = $this->con->lastInsertId();

            $stmt = $this->con->prepare("INSERT INTO calamity_incident_businesses
                (incident_id, juridical_id, date_occurred, nature_of_damage, status, estimated_cost_of_damages, remarks)
                VALUES
                (?, ?, ?, ?, ?, ?, ?)");
            foreach ($perBusiness as $pb) {
                $stmt->execute([
                    $incidentId,
                    $pb['juridical_id'],
                    $pb['date_occurred'],
                    $pb['nature_of_damage'],
                    $pb['status'],
                    $pb['cost'],
                    $pb['remarks']
                ]);
            }

            $this->con->commit();
            return ['status' => 'success', 'message' => 'Calamity incident has been added.'];
        } catch (PDOException $e) {
            $this->con->rollBack();
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
  
    public function getCalamityDetail(int $id) {
        try {
            $stmt = $this->con->prepare("SELECT id, name, calamity_type, declaration_date, description FROM calamities WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if (!$row) {
                return ['status' => 'error', 'message' => 'Calamity not found.'];
            }
            return ['status' => 'success', 'data' => $row];
        } catch (PDOException $e) {
            error_log('CalamityController::getCalamityDetail - ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Unable to retrieve calamity. Please try again later.'];
        }
    }

    public function updateCalamity(array $data) {
        $id               = intval($data['id'] ?? 0);
        $name             = trim($data['name'] ?? '');
        $calamity_type    = trim($data['calamity_type'] ?? '');
        $declaration_date = trim($data['declaration_date'] ?? '');
        $description      = trim($data['description'] ?? '');

        if (!$id) {
            return ['status' => 'error', 'message' => 'Calamity ID is required.'];
        }
        if (empty($name) || empty($calamity_type) || empty($declaration_date)) {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }

        try {
            $stmt = $this->con->prepare("UPDATE calamities SET name = ?, calamity_type = ?, declaration_date = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $calamity_type, $declaration_date, $description, $id]);
            return ['status' => 'success', 'message' => 'Calamity has been updated.'];
        } catch (PDOException $e) {
            error_log('CalamityController::updateCalamity - ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Unable to update calamity. Please try again later.'];
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

    public function deleteAffectedBusiness($id) {
        $id = intval($id ?? 0);
        if (!$id) {
            return ['status' => 'error', 'message' => 'Affected business ID is required.'];
        }

        try {
            $stmt = $this->con->prepare("SELECT incident_id, juridical_id FROM calamity_incident_businesses WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if (!$row) {
                return ['status' => 'error', 'message' => 'Affected business record not found.'];
            }
            $incidentId = $row['incident_id'];

            $this->con->beginTransaction();

            $stmt = $this->con->prepare("DELETE FROM calamity_incident_businesses WHERE id = ?");
            $stmt->execute([$id]);

            $stmt = $this->con->prepare("SELECT id, juridical_id, date_occurred, nature_of_damage, status, estimated_cost_of_damages, remarks
                FROM calamity_incident_businesses
                WHERE incident_id = ? ORDER BY id ASC");
            $stmt->execute([$incidentId]);
            $remaining = $stmt->fetchAll();

            if (empty($remaining)) {
                $stmt = $this->con->prepare("DELETE FROM calamity_incidents WHERE id = ?");
                $stmt->execute([$incidentId]);
                $this->con->commit();
                return ['status' => 'success', 'message' => 'Affected business removed. Incident deleted since no affected businesses remain.'];
            }

            $total = 0.0;
            foreach ($remaining as $r) {
                $total += (float) $r['estimated_cost_of_damages'];
            }

            $first = $remaining[0];
            $stmt = $this->con->prepare("UPDATE calamity_incidents SET
                juridical_id = ?, date_occurred = ?, nature_of_damage = ?, status = ?, remarks = ?, estimated_cost_of_damages = ?
                WHERE id = ?");
            $stmt->execute([
                $first['juridical_id'],
                $first['date_occurred'],
                $first['nature_of_damage'],
                $first['status'],
                $first['remarks'],
                $total,
                $incidentId
            ]);

            $this->con->commit();
            return ['status' => 'success', 'message' => 'Affected business removed from the incident.'];
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            error_log('CalamityController::deleteAffectedBusiness - ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Unable to delete affected business. Please try again later.'];
        }
    }
}
