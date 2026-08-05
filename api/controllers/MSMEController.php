<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';
require_once dirname(__DIR__) . '/../global/name-utils.php';


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
                e.first_name AS emp_first_name,
                e.middle_name AS emp_middle_name,
                e.last_name AS emp_last_name,
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
                        'special_category' => $row['emp_special_category'],
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
            e.first_name AS emp_first_name,
            e.middle_name AS emp_middle_name,
            e.last_name AS emp_last_name,
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

            $fullName = implode(' ', array_filter([
                trim($row['emp_first_name'] ?? ''),
                trim($row['emp_middle_name'] ?? ''),
                trim($row['emp_last_name'] ?? '')
            ]));

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
                        'first_name' => $row['emp_first_name'],
                        'middle_name' => $row['emp_middle_name'],
                        'last_name' => $row['emp_last_name'],
                        'special_category' => $row['emp_special_category'],
                        'upblb_num' => $row['emp_upblb_num'],
                        'street' => $row['emp_street'],
                        'subdivision' => $row['emp_subdivision'],
                        'barangay' => $row['emp_barangay'],
                        'city' => $row['emp_city'],
                        'province' => $row['emp_province'],
                        'region' => $row['emp_region'],
                        'full_name' => $fullName,
                    ],
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function addBusiness(array $data)
    {

        $empID = (int) ($data['employer_id'] ?? 0);
        if ($empID <= 0) {
            return ['status' => 'error', 'message' => 'Owner not found — add the owner first.'];
        }

        //Business Address
        $juri_region = trim($data['juri_region'] ?? '');
        $juri_province = trim($data['juri_province'] ?? '');
        $juri_city = trim($data['juri_city'] ?? '');
        $juri_barangay = trim($data['juri_barangay'] ?? '');
        $juri_street = trim($data['juri_street'] ?? '');
        $juri_subdivision = trim($data['juri_subdivision'] ?? '');
        $juri_upblb_num = trim($data['juri_upblb_num'] ?? '');

        //Business
        $juri_name = trim($data['juri_name'] ?? '');
        $juri_entity_no = trim($data['juri_entity_no'] ?? '');
        $line_of_industry = trim($data['line_of_industry'] ?? '');
        $capitalization = trim($data['capitalization'] ?? '');
        $contact_no = trim($data['contact_no'] ?? '');
        $contact_email = trim($data['contact_email'] ?? '');


        $stmtCheckBusName = $this->con->prepare("SELECT COUNT(*) FROM juridicals WHERE name = ?");
        $stmtCheckBusName->execute([$juri_name]);
        $count = $stmtCheckBusName->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'Business Name already taken.'];
        }
        ;

        $stmtBusCheckEntity = $this->con->prepare("SELECT COUNT(*) FROM juridicals WHERE entity_no = ?");
        $stmtBusCheckEntity->execute([$juri_entity_no]);
        $count = $stmtBusCheckEntity->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => "Business' Entity Number already in use."];
        }
        ;


        $sqlJuriAddress = "INSERT INTO addresses
                   (upblb_num, street, subdivision, barangay, city, province, region)
                VALUES
                   (?, ?, ?, ?, ?, ?, ?)";

        $sqlJuridical = "INSERT INTO juridicals
                   (entity_no, name, registration_type, bus_status, contact_no, contact_email, line_of_industry, capitalization, employer_id, address_id)
                VALUES
                   (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $this->con->beginTransaction();

            $insJuriAddress = $this->con->prepare($sqlJuriAddress);
            $insJuriAddress->execute([$juri_upblb_num, $juri_street, $juri_subdivision, $juri_barangay, $juri_city, $juri_province, $juri_region]);
            $juriAddressID = $this->con->lastInsertId();

            $insJuridical = $this->con->prepare($sqlJuridical);
            $insJuridical->execute([$juri_entity_no, $juri_name, "NEW", "ACTIVE", $contact_no, $contact_email, $line_of_industry, $capitalization, $empID, $juriAddressID]);

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
        $employer_first_name = trim($data['employer_first_name'] ?? '');
        $employer_middle_name = trim($data['employer_middle_name'] ?? '');
        $employer_last_name = trim($data['employer_last_name'] ?? '');
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


        if (empty($employer_first_name) || empty($employer_last_name) || empty($employer_entity_no) || empty($juri_name) || empty($juri_entity_no)) {
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

        $paramsEmployer = [$employer_first_name, $employer_middle_name, $employer_last_name, $special_category];
        $sqlEmployer = "UPDATE employers SET first_name = ?, middle_name = ?, last_name = ?, special_category = ? WHERE entity_no = ?";
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

    //----------------------------- Employers ------------------------------

    public function getEmployers()
    {
        try {
            $stmt = $this->con->prepare("SELECT * FROM employers");
            $stmt->execute();
            $employers = $stmt->fetchAll();
            return ['status' => 'success', 'data' => $employers];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function addEmployer(array $data)
    {
        //Owner Address
        $region = trim($data['region'] ?? '');
        $province = trim($data['province'] ?? '');
        $city = trim($data['city'] ?? '');
        $barangay = trim($data['barangay'] ?? '');
        $street = trim($data['street'] ?? '');
        $subdivision = trim($data['subdivision'] ?? '');
        $upblb_num = trim($data['upblb_num'] ?? '');

        //Owner
        $entity_no = trim($data['entity_no'] ?? '');
        $first_name = trim($data['first_name'] ?? '');
        $middle_name = trim($data['middle_name'] ?? '');
        $last_name = trim($data['last_name'] ?? '');
        $special_category = trim($data['special_category'] ?? '');

        if (empty($entity_no) || empty($first_name) || empty($last_name) || empty($special_category)) {
            return ['status' => 'error', 'message' => "Required fields can't be empty"];
        }

        $sqlcheckEntity = $this->con->prepare("SELECT COUNT(*) FROM employers WHERE entity_no = ?");
        $sqlcheckEntity->execute([$entity_no]);
        $count = $sqlcheckEntity->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'Entity number already in use.'];
        }

        $sqlEmpAddress = "INSERT INTO addresses (upblb_num, street, subdivision, barangay, city, province, region) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $sqlEmployer = "INSERT INTO employers (entity_no, first_name, middle_name, last_name, special_category, address_id) VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $this->con->beginTransaction();

            $insEmpAddress = $this->con->prepare($sqlEmpAddress);
            $insEmpAddress->execute([$upblb_num, $street, $subdivision, $barangay, $city, $province, $region]);
            $empAddressID = $this->con->lastInsertId();

            $insEmployer = $this->con->prepare($sqlEmployer);
            $insEmployer->execute([$entity_no, $first_name, $middle_name, $last_name, $special_category, $empAddressID]);
            $this->con->commit();
            return ['status' => 'success', 'message' => 'Employer has been added.'];
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function searchEmployers(string $q)
    {
        try {
            $like = '%' . $q . '%';
            $stmt = $this->con->prepare(
                "SELECT e.*, a.upblb_num, a.street, a.subdivision, a.barangay, a.city, a.province, a.region
                    FROM employers e
                    LEFT JOIN addresses a ON e.address_id = a.id
                    WHERE e.entity_no LIKE ? OR CONCAT(e.first_name, ' ', e.last_name) LIKE ?
                    ORDER BY e.last_name, e.first_name
                    LIMIT 20"
            );
            $stmt->execute([$like, $like]);
            return ['status' => 'success', 'data' => $stmt->fetchAll()];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function searchScimsEmployers(string $q)
    {
        $scimsRaw = @file_get_contents('https://vamosmobile.app/api/testjuridical/business');
        if (!$scimsRaw) {
            return ['status' => 'error', 'message' => 'Cannot connect to SCIMS API.'];
        }

        $results = [];
        foreach (json_decode($scimsRaw, true)['data'] ?? [] as $item) {
            $en = $item['employer_entity_no'] ?? '';
            if (!$en) continue;
            if (stripos($en, $q) === false && stripos($item['juri_employer'] ?? '', $q) === false) continue;

            $name = splitFullName($item['juri_employer'] ?? '');
            $results[] = [
                'entity_no'   => $en,
                'first_name'  => $name['first_name'],
                'middle_name' => $name['middle_name'],
                'last_name'   => $name['last_name'],
                'region'      => $item['emp_region'] ?? '',
                'province'    => $item['emp_province'] ?? '',
                'city'        => $item['emp_city'] ?? '',
                'barangay'    => $item['emp_barangay'] ?? '',
                'street'      => $item['emp_street'] ?? '',
                'subdivision' => $item['emp_subdivision'] ?? '',
            ];
        }
        return ['status' => 'success', 'data' => array_slice($results, 0, 20)];
    }
}