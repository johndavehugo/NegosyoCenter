<?php

require_once dirname(__DIR__) . '/../config/db_connect.php';

class PriceMonitoringController
{
    private $con;

    public function __construct()
    {
        global $con;
        $this->con = $con;
    }

    /* ==========================================================
       AGENCIES
       ========================================================== */

    public function getAgencies()
    {
        try {
            $sql = "
                SELECT
                    id,
                    code,
                    name
                FROM agencies
                ORDER BY name ASC
            ";

            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return [
                'status' => 'success',
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];

        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getAgencies() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }


    /* ==========================================================
       PRICE MONITORING
       ========================================================== */

    public function getPrices($agencyId = null)
    {
        if (
            $agencyId === null ||
            $agencyId === '' ||
            !is_numeric($agencyId)
        ) {
            return [
                'status' => 'error',
                'message' => 'Agency ID is required.',
                'data' => []
            ];
        }

        try {

            $sql = "
                SELECT
                    p.id,
                    c.id AS commodity_id,
                    c.product_name,
                    cc.name AS category_name,
                    c.brand_name,
                    c.unit_of_measure,
                    c.srp,
                    p.prevailing_price,

                    CASE
                        WHEN p.prevailing_price IS NULL
                            THEN 'NO_PRICE_YET'

                        WHEN c.srp IS NULL OR c.srp <= 0
                            THEN 'NO_SRP'

                        WHEN p.prevailing_price > c.srp
                            THEN 'OVERPRICED'

                        WHEN p.prevailing_price < c.srp
                            THEN 'BELOW_SRP'

                        ELSE 'WITHIN_SRP'
                    END AS status,

                    p.monitored_at,
                    p.monitored_by_agency_id,

                    a.code AS agency_code,
                    a.name AS agency_name

                FROM commodities c

                INNER JOIN commodity_categories cc
                    ON c.category_id = cc.id

                LEFT JOIN price_logs p
                    ON p.id = (
                        SELECT pl.id
                        FROM price_logs pl
                        WHERE pl.commodity_id = c.id
                        AND pl.monitored_by_agency_id = ?
                        ORDER BY pl.monitored_at DESC, pl.id DESC
                        LIMIT 1
                    )

                LEFT JOIN agencies a
                    ON cc.agency_id = a.id

                WHERE cc.agency_id = ?

                ORDER BY c.product_name ASC
            ";

            $stmt = $this->con->prepare($sql);

            $stmt->execute([
                (int)$agencyId,
                (int)$agencyId
            ]);

            return [
                'status' => 'success',
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
            ];

        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getPrices() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }


    public function getCommodities($agencyId = null)
    {
        try {

            $sql = "
                SELECT
                    c.id,
                    c.product_name,
                    c.brand_name,
                    c.unit_of_measure,
                    c.category_id,
                    cc.name AS category_name,
                    cc.agency_id,
                    c.srp

                FROM commodities c

                INNER JOIN commodity_categories cc
                    ON c.category_id = cc.id
            ";

            $params = [];

            if (
                $agencyId !== null &&
                $agencyId !== ''
            ) {

                if (!is_numeric($agencyId)) {

                    return [
                        'status' => 'error',
                        'message' => 'Invalid agency ID.',
                        'data' => []
                    ];
                }

                $sql .= "
                    WHERE cc.agency_id = ?
                ";

                $params[] = (int)$agencyId;
            }

            $sql .= "
                ORDER BY c.product_name ASC
            ";

            $stmt = $this->con->prepare($sql);

            $stmt->execute($params);

            return [
                'status' => 'success',
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
            ];

        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getCommodities() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }


    public function getPriceById($id)
    {
        if (
            $id === null ||
            $id === '' ||
            !is_numeric($id)
        ) {

            return [
                'status' => 'error',
                'message' => 'Missing or invalid record ID.'
            ];
        }

        try {

            $sql = "
                SELECT
                    p.id,
                    p.commodity_id,
                    p.monitored_by_agency_id,
                    p.prevailing_price,
                    p.status,
                    p.monitored_at,

                    c.product_name,
                    c.category_id,
                    cc.name AS category_name,
                    c.brand_name,
                    c.unit_of_measure,
                    c.srp,

                    a.code AS agency_code,
                    a.name AS agency_name

                FROM price_logs p

                LEFT JOIN commodities c
                    ON p.commodity_id = c.id

                LEFT JOIN commodity_categories cc
                    ON c.category_id = cc.id

                LEFT JOIN agencies a
                    ON cc.agency_id = a.id

                WHERE p.id = ?

                LIMIT 1
            ";

            $stmt = $this->con->prepare($sql);

            $stmt->execute([
                (int)$id
            ]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {

                return [
                    'status' => 'error',
                    'message' => 'Price record not found.'
                ];
            }

            return [
                'status' => 'success',
                'data' => $row
            ];

        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getPriceById() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }


    /* ==========================================================
       ADD PRICE
       ========================================================== */

    public function addPrice(array $data)
    {
        $commodity_id = trim(
            (string)($data['commodity_id'] ?? '')
        );

        $agency_id = trim(
            (string)(
                $data['monitored_by_agency_id']
                ?? $data['agency_id']
                ?? ''
            )
        );

        $prevailing_price = trim(
            (string)($data['prevailing_price'] ?? '')
        );

        $srp = trim(
    (string)(
        $data['srp']
        ?? $data['srp_price']
        ?? ''
    )
);


        if (
            $commodity_id === '' ||
            $agency_id === ''
        ) {

            return [
                'status' => 'error',
                'message' => 'Commodity and agency are required.'
            ];
        }


        if (
            !is_numeric($commodity_id) ||
            !is_numeric($agency_id)
        ) {

            return [
                'status' => 'error',
                'message' => 'Invalid commodity or agency ID.'
            ];
        }


        if (
            $prevailing_price === '' ||
            !is_numeric($prevailing_price)
        ) {

            return [
                'status' => 'error',
                'message' => 'A valid prevailing price is required.'
            ];
        }


        $commodity_id = (int)$commodity_id;
        $agency_id = (int)$agency_id;
        $price = (float)$prevailing_price;


        if ($price < 0) {

            return [
                'status' => 'error',
                'message' => 'Prevailing price cannot be negative.'
            ];
        }


        try {

            /*
             * Get commodity
             */

            $commodityStmt = $this->con->prepare("
                SELECT
                    c.id,
                    c.product_name,
                    c.srp,
                    cc.name AS category_name,
                    cc.agency_id

                FROM commodities c

                LEFT JOIN commodity_categories cc
                    ON c.category_id = cc.id

                WHERE c.id = ?

                LIMIT 1
            ");

            $commodityStmt->execute([
                $commodity_id
            ]);

            $commodity =
                $commodityStmt->fetch(PDO::FETCH_ASSOC);


            if (!$commodity) {

                return [
                    'status' => 'error',
                    'message' => 'Selected commodity not found.'
                ];
            }


            /*
             * Verify agency
             */

            if (
                $commodity['agency_id'] === null ||
                (int)$commodity['agency_id'] !== $agency_id
            ) {

                return [
                    'status' => 'error',
                    'message' =>
                        'The selected commodity does not belong to the selected agency.'
                ];
            }


            /*
             * Get SRP
             */

            $srp = (
                $commodity['srp'] !== null &&
                $commodity['srp'] !== ''
            )
                ? (float)$commodity['srp']
                : null;


            /*
             * Determine status
             */

            if (
                $srp === null ||
                $srp <= 0
            ) {

                $status = 'NO_SRP';

            } elseif ($price > $srp) {

                $status = 'OVERPRICED';

            } elseif ($price < $srp) {

                $status = 'BELOW_SRP';

            } else {

                $status = 'WITHIN_SRP';
            }


            /*
             * Insert
             */

            $sql = "
                INSERT INTO price_logs
                (
                    commodity_id,
                    monitored_by_agency_id,
                    prevailing_price,
                    status,
                    monitored_at
                )

                VALUES
                (?, ?, ?, ?, NOW())
            ";

            $stmt =
                $this->con->prepare($sql);


            $stmt->execute([
                $commodity_id,
                $agency_id,
                $price,
                $status
            ]);


            $insertedId =
                $this->con->lastInsertId();


            return [
                'status' => 'success',

                'message' =>
                    'Price record has been added.',

                'data' => [

                    'id' =>
                        (int)$insertedId,

                    'commodity_id' =>
                        $commodity_id,

                    'monitored_by_agency_id' =>
                        $agency_id,

                    'srp' =>
                        $srp,

                    'prevailing_price' =>
                        $price,

                    'status' =>
                        $status
                ]
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::addPrice() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage()
            ];
        }
    }


    /* ==========================================================
       UPDATE PRICE
       ========================================================== */

    public function updatePrice(array $data)
{
    $id = trim(
        (string)($data['id'] ?? '')
    );

    $commodity_id = trim(
        (string)($data['commodity_id'] ?? '')
    );

    $agency_id = trim(
        (string)(
            $data['monitored_by_agency_id']
            ?? $data['agency_id']
            ?? ''
        )
    );

    $prevailing_price = trim(
        (string)($data['prevailing_price'] ?? '')
    );

    /*
     * IMPORTANT:
     * Accept SRP from either:
     *
     * srp
     * srp_price
     */
    $srp_input = $data['srp']
        ?? $data['srp_price']
        ?? null;


    /*
     * ==========================================================
     * VALIDATE ID
     * ==========================================================
     */

    if (
        $id === '' ||
        !is_numeric($id)
    ) {
        return [
            'status' => 'error',
            'message' =>
                'Missing or invalid record ID for update.'
        ];
    }


    /*
     * ==========================================================
     * VALIDATE COMMODITY / AGENCY
     * ==========================================================
     */

    if (
        $commodity_id === '' ||
        $agency_id === ''
    ) {
        return [
            'status' => 'error',
            'message' =>
                'Commodity and agency are required for update.'
        ];
    }

    if (
        !is_numeric($commodity_id) ||
        !is_numeric($agency_id)
    ) {
        return [
            'status' => 'error',
            'message' =>
                'Invalid commodity or agency ID.'
        ];
    }


    /*
     * ==========================================================
     * VALIDATE PREVAILING PRICE
     * ==========================================================
     */

    if (
        $prevailing_price === '' ||
        !is_numeric($prevailing_price)
    ) {
        return [
            'status' => 'error',
            'message' =>
                'A valid prevailing price is required.'
        ];
    }


    /*
     * ==========================================================
     * VALIDATE SRP
     * ==========================================================
     */

    if (
        $srp_input === null ||
        trim((string)$srp_input) === '' ||
        !is_numeric($srp_input)
    ) {
        return [
            'status' => 'error',
            'message' =>
                'A valid SRP is required.'
        ];
    }


    /*
     * ==========================================================
     * CONVERT VALUES
     * ==========================================================
     */

    $id = (int)$id;

    $commodity_id = (int)$commodity_id;

    $agency_id = (int)$agency_id;

    $price = (float)$prevailing_price;

    $srp = (float)$srp_input;


    /*
     * ==========================================================
     * CHECK NEGATIVE VALUES
     * ==========================================================
     */

    if ($price < 0) {
        return [
            'status' => 'error',
            'message' =>
                'Prevailing price cannot be negative.'
        ];
    }

    if ($srp < 0) {
        return [
            'status' => 'error',
            'message' =>
                'SRP cannot be negative.'
        ];
    }


    try {

        /*
         * ======================================================
         * VERIFY EXISTING PRICE RECORD
         * ======================================================
         */

        $checkStmt = $this->con->prepare("
            SELECT
                id,
                commodity_id,
                monitored_by_agency_id
            FROM price_logs
            WHERE id = ?
            LIMIT 1
        ");

        $checkStmt->execute([
            $id
        ]);

        $existing =
            $checkStmt->fetch(PDO::FETCH_ASSOC);


        if (!$existing) {
            return [
                'status' => 'error',
                'message' =>
                    'Price record not found.'
            ];
        }


        /*
         * ======================================================
         * VERIFY AGENCY OWNERSHIP
         * ======================================================
         */

        if (
            (int)$existing['monitored_by_agency_id']
            !== $agency_id
        ) {
            return [
                'status' => 'error',
                'message' =>
                    'You cannot update a price record belonging to another agency.'
            ];
        }


        /*
         * ======================================================
         * VERIFY COMMODITY
         * ======================================================
         */

        $commodityStmt = $this->con->prepare("
            SELECT
                c.id,
                c.product_name,
                c.category_id,
                c.brand_name,
                c.unit_of_measure,
                c.srp,
                cc.name AS category_name,
                cc.agency_id
            FROM commodities c
            LEFT JOIN commodity_categories cc
                ON c.category_id = cc.id
            WHERE c.id = ?
            LIMIT 1
        ");

        $commodityStmt->execute([
            $commodity_id
        ]);

        $commodity =
            $commodityStmt->fetch(PDO::FETCH_ASSOC);


        if (!$commodity) {
            return [
                'status' => 'error',
                'message' =>
                    'Commodity not found.'
            ];
        }


        /*
         * ======================================================
         * VERIFY COMMODITY AGENCY
         * ======================================================
         */

        if (
            $commodity['agency_id'] === null ||
            (int)$commodity['agency_id'] !== $agency_id
        ) {
            return [
                'status' => 'error',
                'message' =>
                    'The selected commodity does not belong to the selected agency.'
            ];
        }


        /*
         * ======================================================
         * UPDATE SRP
         *
         * THIS IS THE IMPORTANT PART.
         *
         * The new SRP is written into:
         *
         * commodities.srp
         * ======================================================
         */

        $srpStmt = $this->con->prepare("
            UPDATE commodities
            SET srp = ?
            WHERE id = ?
        ");

        $srpStmt->execute([
            $srp,
            $commodity_id
        ]);


        /*
         * ======================================================
         * DETERMINE STATUS USING NEW SRP
         * ======================================================
         */

        if ($srp <= 0) {

            $status = 'NO_SRP';

        } elseif ($price > $srp) {

            $status = 'OVERPRICED';

        } elseif ($price < $srp) {

            $status = 'BELOW_SRP';

        } else {

            $status = 'WITHIN_SRP';
        }


        /*
         * ======================================================
         * UPDATE PRICE LOG
         * ======================================================
         */

        $updateStmt = $this->con->prepare("
            UPDATE price_logs
            SET
                commodity_id = ?,
                monitored_by_agency_id = ?,
                prevailing_price = ?,
                status = ?,
                monitored_at = NOW()
            WHERE id = ?
        ");

        $updateStmt->execute([
            $commodity_id,
            $agency_id,
            $price,
            $status,
            $id
        ]);


        /*
         * ======================================================
         * READ UPDATED RECORD
         * ======================================================
         */

        $resultStmt = $this->con->prepare("
            SELECT
                p.id,
                p.commodity_id,
                p.monitored_by_agency_id,
                p.prevailing_price,
                p.status,
                p.monitored_at,

                c.product_name,
                c.category_id,
                c.brand_name,
                c.unit_of_measure,
                c.srp,

                cc.name AS category_name,
                cc.agency_id,

                a.name AS agency_name,
                a.code AS agency_code

            FROM price_logs p

            LEFT JOIN commodities c
                ON p.commodity_id = c.id

            LEFT JOIN commodity_categories cc
                ON c.category_id = cc.id

            LEFT JOIN agencies a
                ON cc.agency_id = a.id

            WHERE p.id = ?

            LIMIT 1
        ");

        $resultStmt->execute([
            $id
        ]);

        $updated =
            $resultStmt->fetch(PDO::FETCH_ASSOC);


        if (!$updated) {
            return [
                'status' => 'error',
                'message' =>
                    'Price was updated but the updated record could not be retrieved.'
            ];
        }


        /*
         * ======================================================
         * RETURN UPDATED DATA
         * ======================================================
         */

        return [
            'status' => 'success',

            'message' =>
                'Price record and SRP updated successfully.',

            'data' => [

                'id' =>
                    (int)$updated['id'],

                'commodity_id' =>
                    (int)$updated['commodity_id'],

                'monitored_by_agency_id' =>
                    (int)$updated['monitored_by_agency_id'],

                'product_name' =>
                    $updated['product_name'],

                'category_id' =>
                    $updated['category_id'] !== null
                        ? (int)$updated['category_id']
                        : null,

                'category_name' =>
                    $updated['category_name'],

                'brand_name' =>
                    $updated['brand_name'],

                'unit_of_measure' =>
                    $updated['unit_of_measure'],

                /*
                 * THIS MUST NOW BE THE NEW SRP
                 */
                'srp' =>
                    $updated['srp'] !== null &&
                    $updated['srp'] !== ''
                        ? (float)$updated['srp']
                        : null,

                'prevailing_price' =>
                    (float)$updated['prevailing_price'],

                'status' =>
                    $updated['status'],

                'monitored_at' =>
                    $updated['monitored_at'],

                'agency_id' =>
                    $updated['agency_id'] !== null
                        ? (int)$updated['agency_id']
                        : null,

                'agency_name' =>
                    $updated['agency_name'],

                'agency_code' =>
                    $updated['agency_code']
            ]
        ];


    } catch (PDOException $e) {

        error_log(
            'PriceMonitoringController::updatePrice() - ' .
            $e->getMessage()
        );

        return [
            'status' => 'error',
            'message' =>
                'Database error: ' .
                $e->getMessage()
        ];
    }
}


    /* ==========================================================
       CATEGORIES
       ========================================================== */

    public function getCategories()
    {
        try {

            $sql = "
                SELECT
                    c.id AS category_id,
                    c.agency_id,
                    c.name AS category_name,
                    a.name AS agency_name

                FROM commodity_categories c

                INNER JOIN agencies a
                    ON c.agency_id = a.id

                ORDER BY c.name ASC
            ";

            $stmt =
                $this->con->prepare($sql);

            $stmt->execute();

            return [
                'status' => 'success',
                'data' =>
                    $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getCategories() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Unable to retrieve categories.',
                'data' => []
            ];
        }
    }


    public function addCategory(array $data)
    {
        $name = trim(
            (string)($data['name'] ?? '')
        );

        $agency_id = trim(
            (string)($data['agency_id'] ?? '')
        );


        if (
            $name === '' ||
            $agency_id === ''
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Category name and agency selection are required.'
            ];
        }


        if (!is_numeric($agency_id)) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid agency ID.'
            ];
        }


        try {

            /*
             * Verify agency
             */

            $agencyStmt =
                $this->con->prepare("
                    SELECT id
                    FROM agencies
                    WHERE id = ?
                    LIMIT 1
                ");

            $agencyStmt->execute([
                (int)$agency_id
            ]);


            if (!$agencyStmt->fetch(PDO::FETCH_ASSOC)) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Selected agency was not found.'
                ];
            }


            /*
             * Insert category
             */

            $sql = "
                INSERT INTO commodity_categories
                (
                    name,
                    agency_id
                )

                VALUES
                (?, ?)
            ";

            $stmt =
                $this->con->prepare($sql);


            $stmt->execute([
                $name,
                (int)$agency_id
            ]);


            return [
                'status' => 'success',
                'message' =>
                    'Category has been added successfully.'
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::addCategory() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: Unable to add category.'
            ];
        }
    }


    public function updateCategory(array $data)
    {
        $id = trim(
            (string)($data['category_id'] ?? '')
        );

        $name = trim(
            (string)($data['name'] ?? '')
        );

        $agency_id = trim(
            (string)($data['agency_id'] ?? '')
        );


        if (
            $id === '' ||
            $name === '' ||
            $agency_id === ''
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Category ID, name, and agency are required.'
            ];
        }


        if (
            !is_numeric($id) ||
            !is_numeric($agency_id)
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid category or agency ID.'
            ];
        }


        try {

            /*
             * Verify agency
             */

            $agencyStmt =
                $this->con->prepare("
                    SELECT id
                    FROM agencies
                    WHERE id = ?
                    LIMIT 1
                ");

            $agencyStmt->execute([
                (int)$agency_id
            ]);


            if (!$agencyStmt->fetch(PDO::FETCH_ASSOC)) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Selected agency was not found.'
                ];
            }


            /*
             * Update category
             */

            $stmt =
                $this->con->prepare("
                    UPDATE commodity_categories

                    SET
                        name = ?,
                        agency_id = ?

                    WHERE id = ?
                ");


            $stmt->execute([
                $name,
                (int)$agency_id,
                (int)$id
            ]);


            /*
             * Check if category exists
             */

            if ($stmt->rowCount() === 0) {

                $checkStmt =
                    $this->con->prepare("
                        SELECT id
                        FROM commodity_categories
                        WHERE id = ?
                        LIMIT 1
                    ");

                $checkStmt->execute([
                    (int)$id
                ]);


                if (!$checkStmt->fetch(PDO::FETCH_ASSOC)) {

                    return [
                        'status' => 'error',
                        'message' =>
                            'Category not found.'
                    ];
                }
            }


            return [
                'status' => 'success',
                'message' =>
                    'Category updated successfully.'
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::updateCategory() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: Unable to update category.'
            ];
        }
    }


    /* ==========================================================
       COMMODITIES
       ========================================================== */

    public function getCommodityList()
    {
        try {

            $sql = "
                SELECT
                    c.id,
                    c.product_name,
                    c.category_id,
                    cc.name AS category_name,
                    c.brand_name,
                    c.unit_of_measure,
                    c.srp,
                    c.agency_id,
                    a.name AS agency_name,
                    a.code AS agency_code

                FROM commodities c

                INNER JOIN commodity_categories cc
                    ON c.category_id = cc.id

                INNER JOIN agencies a
                    ON cc.agency_id = a.id

                ORDER BY c.product_name ASC
            ";

            $stmt =
                $this->con->prepare($sql);

            $stmt->execute();


            return [
                'status' => 'success',
                'data' =>
                    $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getCommodityList() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage(),
                'data' => []
            ];
        }
    }


    public function getCommodityById($id)
    {
        if (
            $id === null ||
            $id === '' ||
            !is_numeric($id)
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid commodity ID.'
            ];
        }


        try {

            $sql = "
                SELECT
                    c.id,
                    c.product_name,
                    c.category_id,
                    c.agency_id,
                    c.brand_name,
                    c.unit_of_measure,
                    c.srp

                FROM commodities c

                WHERE c.id = ?

                LIMIT 1
            ";


            $stmt =
                $this->con->prepare($sql);

            $stmt->execute([
                (int)$id
            ]);


            $commodity =
                $stmt->fetch(PDO::FETCH_ASSOC);


            if (!$commodity) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Commodity not found.'
                ];
            }


            return [
                'status' => 'success',
                'data' => $commodity
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::getCommodityById() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage()
            ];
        }
    }


    public function addCommodity($data)
    {
        error_log(
            'PriceMonitoringController::addCommodity() DATA: ' .
            print_r($data, true)
        );


        $product_name = trim(
            (string)($data['product_name'] ?? '')
        );

        $category_id = trim(
            (string)($data['category_id'] ?? '')
        );

        $brand_name = trim(
            (string)($data['brand_name'] ?? '')
        );

        $unit_of_measure = trim(
            (string)($data['unit_of_measure'] ?? '')
        );


        /*
         * SRP is optional when adding.
         */

        $srpInput =
            $data['srp'] ?? null;

        $srp = null;


        if (
            $product_name === '' ||
            $category_id === '' ||
            $unit_of_measure === ''
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Please complete Product Name, Category, and Unit of Measure.'
            ];
        }


        if (!is_numeric($category_id)) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid category ID.'
            ];
        }


        /*
         * Validate optional SRP
         */

        if (
            $srpInput !== null &&
            trim((string)$srpInput) !== ''
        ) {

            $srpValue =
                trim((string)$srpInput);


            if (!is_numeric($srpValue)) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Invalid SRP value.'
                ];
            }


            $srp =
                (float)$srpValue;


            if ($srp < 0) {

                return [
                    'status' => 'error',
                    'message' =>
                        'SRP cannot be negative.'
                ];
            }
        }


        try {

            /*
             * Get agency from category
             */

            $categoryStmt =
                $this->con->prepare("
                    SELECT
                        id,
                        agency_id

                    FROM commodity_categories

                    WHERE id = ?

                    LIMIT 1
                ");


            $categoryStmt->execute([
                (int)$category_id
            ]);


            $category =
                $categoryStmt->fetch(PDO::FETCH_ASSOC);


            if (!$category) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Selected category was not found.'
                ];
            }


            if (
                $category['agency_id'] === null ||
                !is_numeric($category['agency_id'])
            ) {

                return [
                    'status' => 'error',
                    'message' =>
                        'The selected category has no valid agency.'
                ];
            }


            $agency_id =
                (int)$category['agency_id'];


            /*
             * Insert commodity
             */

            $stmt =
                $this->con->prepare("
                    INSERT INTO commodities
                    (
                        product_name,
                        category_id,
                        agency_id,
                        brand_name,
                        unit_of_measure,
                        srp
                    )

                    VALUES
                    (?, ?, ?, ?, ?, ?)
                ");


            $stmt->execute([

                $product_name,

                (int)$category_id,

                $agency_id,

                $brand_name,

                $unit_of_measure,

                $srp
            ]);


            return [
                'status' => 'success',

                'message' =>
                    'Commodity added successfully.',

                'data' => [
                    'id' =>
                        (int)$this->con->lastInsertId()
                ]
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::addCommodity() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage()
            ];
        }
    }


    public function updateCommodity($data)
    {
        error_log(
            'PriceMonitoringController::updateCommodity() DATA: ' .
            print_r($data, true)
        );


        $id = trim(
            (string)($data['id'] ?? '')
        );

        $product_name = trim(
            (string)($data['product_name'] ?? '')
        );

        $category_id = trim(
            (string)($data['category_id'] ?? '')
        );

        $brand_name = trim(
            (string)($data['brand_name'] ?? '')
        );

        $unit_of_measure = trim(
            (string)($data['unit_of_measure'] ?? '')
        );

        $srpInput =
            $data['srp'] ?? null;


        /*
         * Validation
         */

        if (
            $id === '' ||
            !is_numeric($id)
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid commodity ID.'
            ];
        }


        if ($product_name === '') {

            return [
                'status' => 'error',
                'message' =>
                    'Product name is required.'
            ];
        }


        if (
            $category_id === '' ||
            !is_numeric($category_id)
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Category is required.'
            ];
        }


        if ($unit_of_measure === '') {

            return [
                'status' => 'error',
                'message' =>
                    'Unit of measure is required.'
            ];
        }


        try {

            /*
             * Get current commodity
             */

            $currentStmt =
                $this->con->prepare("
                    SELECT
                        id,
                        product_name,
                        category_id,
                        agency_id,
                        brand_name,
                        unit_of_measure,
                        srp

                    FROM commodities

                    WHERE id = ?

                    LIMIT 1
                ");


            $currentStmt->execute([
                (int)$id
            ]);


            $current =
                $currentStmt->fetch(PDO::FETCH_ASSOC);


            if (!$current) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Commodity not found.'
                ];
            }


            /*
             * Keep existing SRP if no SRP
             * was supplied by JavaScript.
             */

            $srp =
                $current['srp'];


            if (
                $srpInput !== null &&
                trim((string)$srpInput) !== ''
            ) {

                $srpValue =
                    trim((string)$srpInput);


                if (!is_numeric($srpValue)) {

                    return [
                        'status' => 'error',
                        'message' =>
                            'Invalid SRP value.'
                    ];
                }


                $srp =
                    (float)$srpValue;


                if ($srp < 0) {

                    return [
                        'status' => 'error',
                        'message' =>
                            'SRP cannot be negative.'
                    ];
                }
            }


            /*
             * Get agency from category
             */

            $categoryStmt =
                $this->con->prepare("
                    SELECT
                        id,
                        agency_id

                    FROM commodity_categories

                    WHERE id = ?

                    LIMIT 1
                ");


            $categoryStmt->execute([
                (int)$category_id
            ]);


            $category =
                $categoryStmt->fetch(PDO::FETCH_ASSOC);


            if (!$category) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Selected category was not found.'
                ];
            }


            if (
                $category['agency_id'] === null ||
                !is_numeric($category['agency_id'])
            ) {

                return [
                    'status' => 'error',
                    'message' =>
                        'The selected category has no valid agency.'
                ];
            }


            $agency_id =
                (int)$category['agency_id'];


            /*
             * Update commodity
             */

            $stmt =
                $this->con->prepare("
                    UPDATE commodities

                    SET
                        product_name = ?,
                        category_id = ?,
                        agency_id = ?,
                        brand_name = ?,
                        unit_of_measure = ?,
                        srp = ?

                    WHERE id = ?
                ");


            $stmt->execute([

                $product_name,

                (int)$category_id,

                $agency_id,

                $brand_name,

                $unit_of_measure,

                $srp,

                (int)$id
            ]);


            /*
             * Get updated commodity
             */

            $resultStmt =
                $this->con->prepare("
                    SELECT
                        c.id,
                        c.product_name,
                        c.category_id,
                        cc.name AS category_name,
                        c.brand_name,
                        c.unit_of_measure,
                        c.srp,
                        c.agency_id,
                        a.name AS agency_name,
                        a.code AS agency_code

                    FROM commodities c

                    LEFT JOIN commodity_categories cc
                        ON c.category_id = cc.id

                    LEFT JOIN agencies a
                        ON c.agency_id = a.id

                    WHERE c.id = ?

                    LIMIT 1
                ");


            $resultStmt->execute([
                (int)$id
            ]);


            $updated =
                $resultStmt->fetch(PDO::FETCH_ASSOC);


            if (!$updated) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Commodity was updated but could not be retrieved.'
                ];
            }


            return [
                'status' => 'success',

                'message' =>
                    'Commodity updated successfully.',

                'data' => [

                    'id' =>
                        (int)$updated['id'],

                    'product_name' =>
                        $updated['product_name'],

                    'category_id' =>
                        (int)$updated['category_id'],

                    'category_name' =>
                        $updated['category_name'],

                    'brand_name' =>
                        $updated['brand_name'],

                    'unit_of_measure' =>
                        $updated['unit_of_measure'],

                    'srp' =>
                        $updated['srp'] !== null &&
                        $updated['srp'] !== ''
                            ? (float)$updated['srp']
                            : null,

                    'agency_id' =>
                        $updated['agency_id'] !== null
                            ? (int)$updated['agency_id']
                            : null,

                    'agency_name' =>
                        $updated['agency_name'],

                    'agency_code' =>
                        $updated['agency_code']
                ]
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::updateCommodity() - ' .
                $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage()
            ];
        }
    }


    /* ==========================================================
       DELETE COMMODITY
       ========================================================== */

    public function deleteCommodity($id)
    {
        $id =
            trim((string)($id ?? ''));


        if (
            $id === '' ||
            !is_numeric($id)
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid commodity ID.'
            ];
        }


        $id =
            (int)$id;


        try {

            /*
             * Check commodity exists
             */

            $checkStmt =
                $this->con->prepare("
                    SELECT
                        id,
                        product_name

                    FROM commodities

                    WHERE id = ?

                    LIMIT 1
                ");


            $checkStmt->execute([
                $id
            ]);


            $commodity =
                $checkStmt->fetch(PDO::FETCH_ASSOC);


            if (!$commodity) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Commodity not found.'
                ];
            }


            /*
             * Start transaction
             */

            $this->con->beginTransaction();


            /*
             * Delete related price logs first
             */

            $deletePricesStmt =
                $this->con->prepare("
                    DELETE FROM price_logs
                    WHERE commodity_id = ?
                ");


            $deletePricesStmt->execute([
                $id
            ]);


            /*
             * Delete commodity
             */

            $deleteCommodityStmt =
                $this->con->prepare("
                    DELETE FROM commodities
                    WHERE id = ?
                ");


            $deleteCommodityStmt->execute([
                $id
            ]);


            /*
             * Verify delete
             */

            if (
                $deleteCommodityStmt->rowCount() === 0
            ) {

                $this->con->rollBack();

                return [
                    'status' => 'error',
                    'message' =>
                        'Commodity could not be deleted.'
                ];
            }


            /*
             * Commit
             */

            $this->con->commit();


            return [
                'status' => 'success',
                'message' =>
                    'Commodity deleted successfully.'
            ];


        } catch (PDOException $e) {

            if (
                $this->con->inTransaction()
            ) {

                $this->con->rollBack();
            }


            error_log(
                'PriceMonitoringController::deleteCommodity() - ' .
                $e->getMessage()
            );


            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage()
            ];
        }
    }


    /* ==========================================================
       DELETE PRICE
       ========================================================== */

    public function deletePrice($id)
    {
        $id =
            trim((string)($id ?? ''));


        if (
            $id === '' ||
            !is_numeric($id)
        ) {

            return [
                'status' => 'error',
                'message' =>
                    'Invalid price record ID.'
            ];
        }


        $id =
            (int)$id;


        try {

            /*
             * Check price record
             */

            $checkStmt =
                $this->con->prepare("
                    SELECT
                        id

                    FROM price_logs

                    WHERE id = ?

                    LIMIT 1
                ");


            $checkStmt->execute([
                $id
            ]);


            $record =
                $checkStmt->fetch(PDO::FETCH_ASSOC);


            if (!$record) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Price record not found.'
                ];
            }


            /*
             * Delete
             */

            $deleteStmt =
                $this->con->prepare("
                    DELETE FROM price_logs
                    WHERE id = ?
                ");


            $deleteStmt->execute([
                $id
            ]);


            /*
             * Verify delete
             */

            if (
                $deleteStmt->rowCount() === 0
            ) {

                return [
                    'status' => 'error',
                    'message' =>
                        'Price record could not be deleted.'
                ];
            }


            return [
                'status' => 'success',
                'message' =>
                    'Price record deleted successfully.'
            ];


        } catch (PDOException $e) {

            error_log(
                'PriceMonitoringController::deletePrice() - ' .
                $e->getMessage()
            );


            return [
                'status' => 'error',
                'message' =>
                    'Database error: ' .
                    $e->getMessage()
            ];
        }
    }
}