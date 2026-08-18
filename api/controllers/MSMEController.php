<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';
require_once dirname(__DIR__) . '/../global/uuid.php';


class MSMEController
{
    private $con;

    public function __construct()
    {
        global $con;
        $this->con = $con;
    }

    /**
     * @param array
     * @return array 
     */

    public function getBusinesses()
    {
        try {

            $draw = intval($_GET['draw'] ?? 0);
            $start = intval($_GET['start'] ?? 0);
            $length = intval($_GET['length'] ?? 10);
            if ($length < 0)
                $length = 1000000;
            $search = $_GET['search']['value'] ?? '';
            $orderColumn = intval($_GET['order'][0]['column'] ?? 0);
            $orderDir = strtoupper($_GET['order'][0]['dir'] ?? 'asc') === 'DESC' ? 'DESC' : 'ASC';

            $columns = ['j.entity_no', 'j.name', 'j.category', 'e.full_name'];
            $orderBy = $columns[$orderColumn] ?? 'j.entity_no';

            $totalStmt = $this->con->query("SELECT COUNT(*) FROM juridicals j LEFT JOIN employers e ON j.employer_id = e.id");
            $totalRecords = $totalStmt->fetchColumn();

            $where = '';
            $params = [];
            if (!empty($search)) {
                $like = '%' . $search . '%';
                $where = " WHERE j.entity_no LIKE ? OR j.name LIKE ? OR j.category LIKE ? OR e.full_name LIKE ?";
                $params = [$like, $like, $like, $like];
            }

            $countStmt = $this->con->prepare("SELECT COUNT(*) FROM juridicals j LEFT JOIN employers e ON j.employer_id = e.id" . $where);
            $countStmt->execute($params);
            $recordsFiltered = $countStmt->fetchColumn();

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
                e.entity_no AS emp_entity_no,
                e.full_name AS emp_full_name,
                e.special_category AS emp_special_category,
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
                LEFT JOIN addresses ea ON e.address_id = ea.id" .
                $where .
                " ORDER BY $orderBy $orderDir
            LIMIT ? OFFSET ?");
            $stmt->execute(array_merge($params, [$length, $start]));
            $rows = $stmt->fetchAll();
            $businesses = [];
            foreach ($rows as $row) {

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
                        'full_name' => $row['emp_full_name'],
                        'special_category' => $row['emp_special_category'],
                        'upblb_num' => $row['emp_upblb_num'],
                        'street' => $row['emp_street'],
                        'subdivision' => $row['emp_subdivision'],
                        'barangay' => $row['emp_barangay'],
                        'city' => $row['emp_city'],
                        'province' => $row['emp_province'],
                        'region' => $row['emp_region'],
                    ],
                ];
            }
            return [
                'status' => 'success',
                'draw' => $draw,
                'recordsTotal' => intval($totalRecords),
                'recordsFiltered' => intval($recordsFiltered),
                'data' => $businesses
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }


    public function getBusinessByEntityNo($entityNo)
    {
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
            e.entity_no AS emp_entity_no,
            e.full_name AS emp_full_name,
            e.special_category AS emp_special_category,
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
            LEFT JOIN addresses ea ON e.address_id = ea.id
            WHERE j.entity_no = ?");
            $stmt->execute([$entityNo]);
            $row = $stmt->fetch();

            if (!$row) {
                return ['status' => 'error', 'message' => 'Business not found.'];
            }

            return [
                'status' => 'success',
                'data' => [
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
                        'special_category' => $row['emp_special_category'],
                        'upblb_num' => $row['emp_upblb_num'],
                        'street' => $row['emp_street'],
                        'subdivision' => $row['emp_subdivision'],
                        'barangay' => $row['emp_barangay'],
                        'city' => $row['emp_city'],
                        'province' => $row['emp_province'],
                        'region' => $row['emp_region'],
                        'full_name' => $row['emp_full_name'],
                    ],
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function addBusiness(array $data)
    {

        //employer address
        $emp_region = trim($data['emp_region'] ?? '');
        $emp_province = trim($data['emp_province'] ?? '');
        $emp_city = trim($data['emp_city'] ?? '');
        $emp_barangay = trim($data['emp_barangay'] ?? '');
        $emp_street = trim($data['emp_street'] ?? '');
        $emp_subdivision = trim($data['emp_subdivision'] ?? '');
        $emp_upblb_num = trim($data['emp_upblb_num'] ?? '');
        $emp_address_id = trim($data['emp_address_id'] ?? '');
        if ($emp_address_id === '') {
            $emp_address_id = uuidV7('addr-');
        }


        //employer
        $emp_full_name = trim($data['emp_name'] ?? '');
        $emp_entity_no = trim($data['emp_entity_no'] ?? '');
        $emp_special_category = trim($data['emp_special_category'] ?? '');
        $emp_id = trim($data['emp_id'] ?? '');

        //Business Address
        $juri_region = trim($data['juri_region'] ?? '');
        $juri_province = trim($data['juri_province'] ?? '');
        $juri_city = trim($data['juri_city'] ?? '');
        $juri_barangay = trim($data['juri_barangay'] ?? '');
        $juri_street = trim($data['juri_street'] ?? '');
        $juri_subdivision = trim($data['juri_subdivision'] ?? '');
        $juri_upblb_num = trim($data['juri_upblb_num'] ?? '');
        $juri_address_id = trim($data['juri_address_id'] ?? '');
        if ($juri_address_id === '') {
            $juri_address_id = uuidV7('addr-');
        }

        //Business
        $juri_name = trim($data['juri_name'] ?? '');
        $juri_entity_no = trim($data['juri_entity_no'] ?? '');
        $juri_line_of_industry = trim($data['juri_line_of_industry'] ?? '');
        $juri_capitalization = trim($data['juri_capitalization'] ?? '');
        $juri_contact_no = trim($data['juri_contact_no'] ?? '');
        $juri_contact_email = trim($data['juri_contact_email'] ?? '');
        $juri_id = uuidV7('neg-');


        $stmtEmpCheckEntity = $this->con->prepare("SELECT COUNT(*) FROM employers WHERE entity_no = ?");
        $stmtEmpCheckEntity->execute([$emp_entity_no]);
        $count = $stmtEmpCheckEntity->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => "Business' Entity Number already in use."];
        }
        ;

        $stmtBusCheckEntity = $this->con->prepare("SELECT COUNT(*) FROM juridicals WHERE entity_no = ?");
        $stmtBusCheckEntity->execute([$juri_entity_no]);
        $count = $stmtBusCheckEntity->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => "Business' Entity Number already in use."];
        }
        ;

        $stmtCheckBusName = $this->con->prepare("SELECT COUNT(*) FROM juridicals WHERE name = ?");
        $stmtCheckBusName->execute([$juri_name]);
        $count = $stmtCheckBusName->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'Business Name already taken.'];
        }
        ;

        if (empty($emp_full_name) || empty($juri_name) || empty($juri_entity_no) || empty($emp_entity_no)) {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }


        $sqlEmpAddress = "INSERT INTO addresses
                   (id, upblb_num, street, subdivision, barangay, city, province, region)
                VALUES
                   (?, ?, ?, ?, ?, ?, ?, ?)";

        $sqlEmployer = "INSERT INTO employers (id, entity_no, full_name, address_id, special_category)
                VALUES
                    (?, ?, ?, ?, ?)";

        $sqlJuriAddress = "INSERT INTO addresses
                   (id, upblb_num, street, subdivision, barangay, city, province, region)
                VALUES
                   (?, ?, ?, ?, ?, ?, ?, ?)";

        $sqlJuridical = "INSERT INTO juridicals
                   (id, entity_no, name, registration_type, bus_status, contact_no, contact_email, line_of_industry, capitalization, employer_id, address_id)
                VALUES
                   (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $this->con->beginTransaction();

            $insEmpAddress = $this->con->prepare($sqlEmpAddress);
            $insEmpAddress->execute([$emp_address_id, $emp_upblb_num, $emp_street, $emp_subdivision, $emp_barangay, $emp_city, $emp_province, $emp_region]);

            $insEmployer = $this->con->prepare($sqlEmployer);
            $insEmployer->execute([$emp_id, $emp_entity_no, $emp_full_name, $emp_address_id, $emp_special_category]);

            $insJuriAddress = $this->con->prepare($sqlJuriAddress);
            $insJuriAddress->execute([$juri_address_id, $juri_upblb_num, $juri_street, $juri_subdivision, $juri_barangay, $juri_city, $juri_province, $juri_region]);

            $insJuridical = $this->con->prepare($sqlJuridical);
            $insJuridical->execute([$juri_id, $juri_entity_no, $juri_name, "NEW", "ACTIVE", $juri_contact_no, $juri_contact_email, $juri_line_of_industry, $juri_capitalization, $emp_id, $juri_address_id]);

            $this->con->commit();
            return ['status' => 'success', 'message' => 'Business has been added.'];
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function updateBusiness(array $data)
    {
        //Owner Address
        $employer_region = trim($data['employer_region'] ?? '');
        $employer_province = trim($data['employer_province'] ?? '');
        $employer_city = trim($data['employer_city'] ?? '');
        $employer_barangay = trim($data['employer_barangay'] ?? '');
        $employer_street = trim($data['employer_street'] ?? '');
        $employer_subdivision = trim($data['employer_subdivision'] ?? '');
        $employer_upblb_num = trim($data['employer_upblb_num'] ?? '');

        //Owner
        $employer_entity_no = trim($data['employer_entity_no'] ?? '');
        $employer_full_name = trim($data['employer_full_name'] ?? '');
        $special_category = trim($data['special_category'] ?? '');

        //Business Address
        $juri_region = trim($data['juri_region'] ?? '');
        $juri_province = trim($data['juri_province'] ?? '');
        $juri_city = trim($data['juri_city'] ?? '');
        $juri_barangay = trim($data['juri_barangay'] ?? '');
        $juri_street = trim($data['juri_street'] ?? '');
        $juri_subdivision = trim($data['juri_subdivision'] ?? '');
        $juri_upblb_num = trim($data['juri_upblb_num'] ?? '');

        //Business
        $juri_entity_no = trim($data['juri_entity_no'] ?? '');
        $juri_name = trim($data['juri_name'] ?? '');
        $line_of_industry = trim($data['line_of_industry'] ?? '');
        $capitalization = trim($data['capitalization'] ?? '');
        $contact_no = trim($data['contact_no'] ?? '');
        $contact_email = trim($data['contact_email'] ?? '');


        if (empty($employer_full_name) || empty($juri_name) || empty($juri_entity_no) || empty($employer_entity_no)) {
            return ['status' => 'error', 'message' => "Required fields can't be empty."];
        }

        try {
            $stmt = $this->con->prepare("SELECT j.address_id AS juri_address_id,
                                                e.address_id AS emp_address_id
                                            FROM juridicals j
                                            LEFT JOIN employers e ON j.employer_id = e.id
                                            WHERE j.entity_no = ?");
            $stmt->execute([$juri_entity_no]);
            $addressID = $stmt->fetch();

            if (!$addressID) {
                return ['status' => 'error', 'message' => 'Business not found.'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }

        $emp_address_id = $addressID['emp_address_id'];
        $juri_address_id = $addressID['juri_address_id'];


        $paramsEmpAddress = [$employer_upblb_num, $employer_street, $employer_subdivision, $employer_barangay, $employer_city, $employer_province, $employer_region];
        $sqlEmpAddress = "UPDATE addresses SET upblb_num= ?, street= ?, subdivision= ?, barangay= ?, city= ?, province= ?, region= ? WHERE id = ?";
        $paramsEmpAddress[] = $emp_address_id;

        $paramsEmployer = [$employer_full_name, $special_category];
        $sqlEmployer = "UPDATE employers SET full_name = ?, special_category = ? WHERE entity_no = ?";
        $paramsEmployer[] = $employer_entity_no;

        $paramsJuriAddress = [$juri_upblb_num, $juri_street, $juri_subdivision, $juri_barangay, $juri_city, $juri_province, $juri_region];
        $sqlJuriAddress = "UPDATE addresses SET upblb_num= ?, street= ?, subdivision= ?, barangay= ?, city= ?, province= ?, region= ? WHERE id = ?";
        $paramsJuriAddress[] = $juri_address_id;

        $paramsJuridical = [$juri_name, $contact_no, $contact_email, $line_of_industry, $capitalization];
        $sqlJuridical = "UPDATE juridicals SET name = ?, contact_no = ?, contact_email = ?, line_of_industry = ?, capitalization = ? WHERE entity_no = ?";
        $paramsJuridical[] = $juri_entity_no;

        try {
            $this->con->beginTransaction();

            $updEmpAddress = $this->con->prepare($sqlEmpAddress);
            $updEmpAddress->execute($paramsEmpAddress);

            $updEmployer = $this->con->prepare($sqlEmployer);
            $updEmployer->execute($paramsEmployer);

            $updJuriAddress = $this->con->prepare($sqlJuriAddress);
            $updJuriAddress->execute($paramsJuriAddress);

            $updJuridical = $this->con->prepare($sqlJuridical);
            $updJuridical->execute($paramsJuridical);


            $this->con->commit();

            $affected = $updEmpAddress->rowCount() + $updEmployer->rowCount() + $updJuriAddress->rowCount() + $updJuridical->rowCount();

            if ($affected > 0) {
                return ['status' => 'success', 'message' => 'Business details updated successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'No changes were made or business not found.'];
            }
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            ;
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    //------------------------------- BUSINESS PATCH -----------------------------------

    public function patchBusiness(string $action, array $data)
    {
        return match ($action) {
            'renew' => $this->renewBusiness($data),
            'status' => $this->changeBusinessStatus($data),
            default => ['status' => 'error', 'message' => "Unknown PATCH action for /business."],
        };
    }


    private function renewBusiness(array $data)
    {
        $juri_entity_no = trim($data['juri_entity_no'] ?? '');

        $sqlRenew = "UPDATE juridicals SET registration_type = 'RENEWAL' WHERE entity_no = ?";

        try {
            $renew = $this->con->prepare($sqlRenew);
            $renew->execute([$juri_entity_no]);

            if ($renew->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Business renewed successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'No changes were made or business not found.'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    private function changeBusinessStatus(array $data)
    {
        $juri_entity_no = trim($data['juri_entity_no'] ?? '');
        $juri_bus_status = trim($data['juri_bus_status'] ?? '');

        $sqlChangeStatus = "UPDATE juridicals SET bus_status = ? WHERE entity_no = ?";

        if (empty($juri_bus_status)) {
            return ['status' => 'error', 'message' => "Please select a status."];
        }

        try {
            $changeStatus = $this->con->prepare($sqlChangeStatus);
            $changeStatus->execute([$juri_bus_status, $juri_entity_no]);

            if ($changeStatus->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Business status changed successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'No changes were made or business not found.'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}