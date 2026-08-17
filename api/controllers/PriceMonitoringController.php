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

    private function success($message = '', $data = [])
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }

    private function error($message, $data = [])
    {
        return [
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ];
    }

    private function id($value)
    {
        return $value !== null && $value !== '' && is_numeric($value)
            ? (int)$value
            : null;
    }

    private function input($data, $keys, $default = '')
    {
        foreach ($keys as $key) {
            if (isset($data[$key]) && $data[$key] !== '') {
                return trim((string)$data[$key]);
            }
        }

        return $default;
    }

    private function validatePrice($value, $field = 'Price')
    {
        if ($value === '' || $value === null || !is_numeric($value)) {
            return $this->error("A valid {$field} is required.");
        }

        if ((float)$value < 0) {
            return $this->error("{$field} cannot be negative.");
        }

        return null;
    }

    private function findAgency($id)
    {
        $stmt = $this->con->prepare("
            SELECT id, code, name
            FROM agencies
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function findCategory($id)
    {
        $stmt = $this->con->prepare("
            SELECT
                id,
                name,
                agency_id
            FROM commodity_categories
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function findCommodity($id)
    {
        $stmt = $this->con->prepare("
            SELECT
                c.id,
                c.product_name,
                c.category_id,
                c.agency_id,
                c.brand_name,
                c.unit_of_measure,
                c.srp,
                cc.name AS category_name,
                cc.agency_id AS category_agency_id
            FROM commodities c
            LEFT JOIN commodity_categories cc
                ON c.category_id = cc.id
            WHERE c.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getAgencyFromCategory($categoryId)
    {
        $category = $this->findCategory($categoryId);

        if (!$category) {
            return null;
        }

        if (
            $category['agency_id'] === null ||
            !is_numeric($category['agency_id'])
        ) {
            return null;
        }

        return (int)$category['agency_id'];
    }

    private function calculateStatus($price, $srp)
    {
        if ($price === null || $price === '') {
            return 'NO_PRICE_YET';
        }

        if ($srp === null || $srp <= 0) {
            return 'NO_SRP';
        }

        if ($price > $srp) {
            return 'OVERPRICED';
        }

        if ($price < $srp) {
            return 'BELOW_SRP';
        }

        return 'WITHIN_SRP';
    }

    private function normalizeSrp($value)
    {
        if ($value === null || trim((string)$value) === '') {
            return null;
        }

        return is_numeric($value)
            ? (float)$value
            : null;
    }

    private function getPriceRecord($id)
    {
        $stmt = $this->con->prepare("
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

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function formatPriceRecord($row)
    {
        if (!$row) {
            return null;
        }

        return [
            'id' => (int)$row['id'],
            'commodity_id' => (int)$row['commodity_id'],
            'monitored_by_agency_id' => (int)$row['monitored_by_agency_id'],
            'product_name' => $row['product_name'],
            'category_id' => $row['category_id'] !== null
                ? (int)$row['category_id']
                : null,
            'category_name' => $row['category_name'],
            'brand_name' => $row['brand_name'],
            'unit_of_measure' => $row['unit_of_measure'],
            'srp' => $row['srp'] !== null && $row['srp'] !== ''
                ? (float)$row['srp']
                : null,
            'prevailing_price' => $row['prevailing_price'] !== null
                ? (float)$row['prevailing_price']
                : null,
            'status' => $row['status'],
            'monitored_at' => $row['monitored_at'],
            'agency_id' => $row['agency_id'] !== null
                ? (int)$row['agency_id']
                : null,
            'agency_name' => $row['agency_name'],
            'agency_code' => $row['agency_code']
        ];
    }

    public function getAgencies()
    {
        try {
            $stmt = $this->con->prepare("
                SELECT id, code, name
                FROM agencies
                ORDER BY name ASC
            ");

            $stmt->execute();

            return $this->success('', $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log('getAgencies: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage(),
                []
            );
        }
    }

    public function getPrices($agencyId = null)
    {
        $agencyId = $this->id($agencyId);

        if ($agencyId === null) {
            return $this->error('Agency ID is required.', []);
        }

        try {
            $stmt = $this->con->prepare("
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
                        WHEN p.prevailing_price IS NULL THEN 'NO_PRICE_YET'
                        WHEN c.srp IS NULL OR c.srp <= 0 THEN 'NO_SRP'
                        WHEN p.prevailing_price > c.srp THEN 'OVERPRICED'
                        WHEN p.prevailing_price < c.srp THEN 'BELOW_SRP'
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
            ");

            $stmt->execute([
                $agencyId,
                $agencyId
            ]);

            return $this->success('', $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log('getPrices: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage(),
                []
            );
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

            if ($agencyId !== null && $agencyId !== '') {
                $agencyId = $this->id($agencyId);

                if ($agencyId === null) {
                    return $this->error('Invalid agency ID.', []);
                }

                $sql .= " WHERE cc.agency_id = ?";
                $params[] = $agencyId;
            }

            $sql .= " ORDER BY c.product_name ASC";

            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);

            return $this->success('', $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log('getCommodities: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage(),
                []
            );
        }
    }

    public function getPriceById($id)
    {
        $id = $this->id($id);

        if ($id === null) {
            return $this->error('Missing or invalid record ID.');
        }

        try {
            $record = $this->getPriceRecord($id);

            if (!$record) {
                return $this->error('Price record not found.');
            }

            return $this->success(
                '',
                $this->formatPriceRecord($record)
            );
        } catch (PDOException $e) {
            error_log('getPriceById: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

    public function addPrice(array $data)
    {
        $commodityId = $this->id(
            $this->input($data, ['commodity_id'])
        );

        $agencyId = $this->id(
            $this->input(
                $data,
                ['monitored_by_agency_id', 'agency_id']
            )
        );

        $price = $this->input(
            $data,
            ['prevailing_price']
        );

        if ($commodityId === null || $agencyId === null) {
            return $this->error(
                'Commodity and agency are required.'
            );
        }

        $priceError = $this->validatePrice(
            $price,
            'Prevailing price'
        );

        if ($priceError) {
            return $priceError;
        }

        try {
            $commodity = $this->findCommodity($commodityId);

            if (!$commodity) {
                return $this->error(
                    'Selected commodity not found.'
                );
            }

            if (
                $commodity['category_agency_id'] === null ||
                (int)$commodity['category_agency_id'] !== $agencyId
            ) {
                return $this->error(
                    'The selected commodity does not belong to the selected agency.'
                );
            }

            $price = (float)$price;
            $srp = $this->normalizeSrp($commodity['srp']);
            $status = $this->calculateStatus($price, $srp);

            $stmt = $this->con->prepare("
                INSERT INTO price_logs
                (
                    commodity_id,
                    monitored_by_agency_id,
                    prevailing_price,
                    status,
                    monitored_at
                )
                VALUES (?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $commodityId,
                $agencyId,
                $price,
                $status
            ]);

            $id = (int)$this->con->lastInsertId();

            return $this->success(
                'Price record has been added.',
                [
                    'id' => $id,
                    'commodity_id' => $commodityId,
                    'monitored_by_agency_id' => $agencyId,
                    'srp' => $srp,
                    'prevailing_price' => $price,
                    'status' => $status
                ]
            );
        } catch (PDOException $e) {
            error_log('addPrice: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

    public function updatePrice(array $data)
    {
        $id = $this->id(
            $this->input($data, ['id'])
        );

        $commodityId = $this->id(
            $this->input($data, ['commodity_id'])
        );

        $agencyId = $this->id(
            $this->input(
                $data,
                ['monitored_by_agency_id', 'agency_id']
            )
        );

        $price = $this->input(
            $data,
            ['prevailing_price']
        );

        $srpInput = $this->input(
            $data,
            ['srp', 'srp_price'],
            null
        );

        if ($id === null) {
            return $this->error(
                'Missing or invalid record ID for update.'
            );
        }

        if ($commodityId === null || $agencyId === null) {
            return $this->error(
                'Commodity and agency are required for update.'
            );
        }

        $priceError = $this->validatePrice(
            $price,
            'Prevailing price'
        );

        if ($priceError) {
            return $priceError;
        }

        $srpError = $this->validatePrice(
            $srpInput,
            'SRP'
        );

        if ($srpError) {
            return $srpError;
        }

        try {
            $existing = $this->getPriceRecord($id);

            if (!$existing) {
                return $this->error(
                    'Price record not found.'
                );
            }

            if (
                (int)$existing['monitored_by_agency_id'] !== $agencyId
            ) {
                return $this->error(
                    'You cannot update a price record belonging to another agency.'
                );
            }

            $commodity = $this->findCommodity($commodityId);

            if (!$commodity) {
                return $this->error(
                    'Commodity not found.'
                );
            }

            if (
                $commodity['category_agency_id'] === null ||
                (int)$commodity['category_agency_id'] !== $agencyId
            ) {
                return $this->error(
                    'The selected commodity does not belong to the selected agency.'
                );
            }

            $price = (float)$price;
            $srp = (float)$srpInput;
            $status = $this->calculateStatus($price, $srp);

            $this->con->beginTransaction();

            $stmt = $this->con->prepare("
                UPDATE commodities
                SET srp = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $srp,
                $commodityId
            ]);

            $stmt = $this->con->prepare("
                UPDATE price_logs
                SET
                    commodity_id = ?,
                    monitored_by_agency_id = ?,
                    prevailing_price = ?,
                    status = ?,
                    monitored_at = NOW()
                WHERE id = ?
            ");

            $stmt->execute([
                $commodityId,
                $agencyId,
                $price,
                $status,
                $id
            ]);

            $this->con->commit();

            $updated = $this->getPriceRecord($id);

            if (!$updated) {
                return $this->error(
                    'Price was updated but could not be retrieved.'
                );
            }

            return $this->success(
                'Price record and SRP updated successfully.',
                $this->formatPriceRecord($updated)
            );
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }

            error_log('updatePrice: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

        public function getCategories()
    {
        try {
            $stmt = $this->con->prepare("
                SELECT
                    c.id AS category_id,
                    c.agency_id,
                    c.name AS category_name,
                    a.name AS agency_name
                FROM commodity_categories c
                INNER JOIN agencies a
                    ON c.agency_id = a.id
                ORDER BY c.name ASC
            ");

            $stmt->execute();

            return $this->success(
                '',
                $stmt->fetchAll(PDO::FETCH_ASSOC)
            );
        } catch (PDOException $e) {
            error_log('getCategories: ' . $e->getMessage());

            return $this->error(
                'Unable to retrieve categories.',
                []
            );
        }
    }

    public function addCategory(array $data)
    {
        $name = $this->input(
            $data,
            ['name']
        );

        $agencyId = $this->id(
            $this->input(
                $data,
                ['agency_id']
            )
        );

        if ($name === '' || $agencyId === null) {
            return $this->error(
                'Category name and agency selection are required.'
            );
        }

        try {
            if (!$this->findAgency($agencyId)) {
                return $this->error(
                    'Selected agency was not found.'
                );
            }

            $stmt = $this->con->prepare("
                INSERT INTO commodity_categories
                (
                    name,
                    agency_id
                )
                VALUES (?, ?)
            ");

            $stmt->execute([
                $name,
                $agencyId
            ]);

            return $this->success(
                'Category has been added successfully.'
            );
        } catch (PDOException $e) {
            error_log('addCategory: ' . $e->getMessage());

            return $this->error(
                'Database error: Unable to add category.'
            );
        }
    }

    public function updateCategory(array $data)
    {
        $id = $this->id(
            $this->input(
                $data,
                ['category_id', 'id']
            )
        );

        $name = $this->input(
            $data,
            ['name']
        );

        $agencyId = $this->id(
            $this->input(
                $data,
                ['agency_id']
            )
        );

        if (
            $id === null ||
            $name === '' ||
            $agencyId === null
        ) {
            return $this->error(
                'Category ID, name, and agency are required.'
            );
        }

        try {
            if (!$this->findAgency($agencyId)) {
                return $this->error(
                    'Selected agency was not found.'
                );
            }

            $checkStmt = $this->con->prepare("
                SELECT id
                FROM commodity_categories
                WHERE id = ?
                LIMIT 1
            ");

            $checkStmt->execute([
                $id
            ]);

            if (!$checkStmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->error(
                    'Category not found.'
                );
            }

            $stmt = $this->con->prepare("
                UPDATE commodity_categories
                SET
                    name = ?,
                    agency_id = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $name,
                $agencyId,
                $id
            ]);

            return $this->success(
                'Category updated successfully.'
            );
        } catch (PDOException $e) {
            error_log('updateCategory: ' . $e->getMessage());

            return $this->error(
                'Database error: Unable to update category.'
            );
        }
    }


   

public function deleteCategory($id)
{
    $id = $this->id($id);

    if ($id === null) {
        return $this->error('Invalid category ID.');
    }

    try {
        $stmt = $this->con->prepare("
            SELECT id
            FROM commodity_categories
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            return $this->error('Category not found.');
        }

        $stmt = $this->con->prepare("
            DELETE FROM commodity_categories
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            return $this->error('Category could not be deleted.');
        }

        return $this->success('Category deleted successfully.');

    } catch (PDOException $e) {
        error_log('deleteCategory: ' . $e->getMessage());

        return $this->error(
            'Database error: Unable to delete category.'
        );
    }
}




    public function getCommodityList()
    {
        try {
            $stmt = $this->con->prepare("
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
            ");

            $stmt->execute();

            return $this->success(
                '',
                $stmt->fetchAll(PDO::FETCH_ASSOC)
            );
        } catch (PDOException $e) {
            error_log('getCommodityList: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage(),
                []
            );
        }
    }

    public function getCommodityById($id)
    {
        $id = $this->id($id);

        if ($id === null) {
            return $this->error(
                'Invalid commodity ID.'
            );
        }

        try {
            $stmt = $this->con->prepare("
                SELECT
                    c.id,
                    c.product_name,
                    c.category_id,
                    c.agency_id,
                    c.brand_name,
                    c.unit_of_measure,
                    c.srp,
                    cc.name AS category_name,
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

            $stmt->execute([
                $id
            ]);

            $commodity = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$commodity) {
                return $this->error(
                    'Commodity not found.'
                );
            }

            return $this->success(
                '',
                $commodity
            );
        } catch (PDOException $e) {
            error_log('getCommodityById: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

    public function addCommodity($data)
    {
        $productName = $this->input(
            $data,
            ['product_name']
        );

        $categoryId = $this->id(
            $this->input(
                $data,
                ['category_id']
            )
        );

        $brandName = $this->input(
            $data,
            ['brand_name']
        );

        $unitOfMeasure = $this->input(
            $data,
            ['unit_of_measure']
        );

        $srpInput = $this->input(
            $data,
            ['srp'],
            null
        );

        if (
            $productName === '' ||
            $categoryId === null ||
            $unitOfMeasure === ''
        ) {
            return $this->error(
                'Please complete Product Name, Category, and Unit of Measure.'
            );
        }

        $srp = $this->normalizeSrp($srpInput);

        if (
            $srpInput !== null &&
            trim((string)$srpInput) !== '' &&
            $srp === null
        ) {
            return $this->error(
                'Invalid SRP value.'
            );
        }

        if ($srp !== null && $srp < 0) {
            return $this->error(
                'SRP cannot be negative.'
            );
        }

        try {
            $category = $this->findCategory($categoryId);

            if (!$category) {
                return $this->error(
                    'Selected category was not found.'
                );
            }

            $agencyId = $this->getAgencyFromCategory(
                $categoryId
            );

            if ($agencyId === null) {
                return $this->error(
                    'The selected category has no valid agency.'
                );
            }

            $stmt = $this->con->prepare("
                INSERT INTO commodities
                (
                    product_name,
                    category_id,
                    agency_id,
                    brand_name,
                    unit_of_measure,
                    srp
                )
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $productName,
                $categoryId,
                $agencyId,
                $brandName,
                $unitOfMeasure,
                $srp
            ]);

            $id = (int)$this->con->lastInsertId();

            return $this->success(
                'Commodity added successfully.',
                [
                    'id' => $id
                ]
            );
        } catch (PDOException $e) {
            error_log('addCommodity: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

    public function updateCommodity($data)
    {
        $id = $this->id(
            $this->input(
                $data,
                ['id', 'commodity_id']
            )
        );

        $productName = $this->input(
            $data,
            ['product_name']
        );

        $categoryId = $this->id(
            $this->input(
                $data,
                ['category_id']
            )
        );

        $brandName = $this->input(
            $data,
            ['brand_name']
        );

        $unitOfMeasure = $this->input(
            $data,
            ['unit_of_measure']
        );

        $srpInput = $this->input(
            $data,
            ['srp'],
            null
        );

        if ($id === null) {
            return $this->error(
                'Invalid commodity ID.'
            );
        }

        if ($productName === '') {
            return $this->error(
                'Product name is required.'
            );
        }

        if ($categoryId === null) {
            return $this->error(
                'Category is required.'
            );
        }

        if ($unitOfMeasure === '') {
            return $this->error(
                'Unit of measure is required.'
            );
        }

        try {
            $current = $this->findCommodity($id);

            if (!$current) {
                return $this->error(
                    'Commodity not found.'
                );
            }

            $srp = $current['srp'];

            if (
                $srpInput !== null &&
                trim((string)$srpInput) !== ''
            ) {
                $srp = $this->normalizeSrp($srpInput);

                if ($srp === null) {
                    return $this->error(
                        'Invalid SRP value.'
                    );
                }

                if ($srp < 0) {
                    return $this->error(
                        'SRP cannot be negative.'
                    );
                }
            }

            $category = $this->findCategory($categoryId);

            if (!$category) {
                return $this->error(
                    'Selected category was not found.'
                );
            }

            $agencyId = $this->getAgencyFromCategory(
                $categoryId
            );

            if ($agencyId === null) {
                return $this->error(
                    'The selected category has no valid agency.'
                );
            }

            $stmt = $this->con->prepare("
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
                $productName,
                $categoryId,
                $agencyId,
                $brandName,
                $unitOfMeasure,
                $srp,
                $id
            ]);

            return $this->getCommodityById($id);
        } catch (PDOException $e) {
            error_log('updateCommodity: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

    public function deleteCommodity($id)
    {
        $id = $this->id($id);

        if ($id === null) {
            return $this->error(
                'Invalid commodity ID.'
            );
        }

        try {
            $commodity = $this->findCommodity($id);

            if (!$commodity) {
                return $this->error(
                    'Commodity not found.'
                );
            }

            $this->con->beginTransaction();

            $stmt = $this->con->prepare("
                DELETE FROM price_logs
                WHERE commodity_id = ?
            ");

            $stmt->execute([
                $id
            ]);

            $stmt = $this->con->prepare("
                DELETE FROM commodities
                WHERE id = ?
            ");

            $stmt->execute([
                $id
            ]);

            if ($stmt->rowCount() === 0) {
                $this->con->rollBack();

                return $this->error(
                    'Commodity could not be deleted.'
                );
            }

            $this->con->commit();

            return $this->success(
                'Commodity deleted successfully.'
            );
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }

            error_log('deleteCommodity: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }

    public function deletePrice($id)
    {
        $id = $this->id($id);

        if ($id === null) {
            return $this->error(
                'Invalid price record ID.'
            );
        }

        try {
            $stmt = $this->con->prepare("
                SELECT id
                FROM price_logs
                WHERE id = ?
                LIMIT 1
            ");

            $stmt->execute([
                $id
            ]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->error(
                    'Price record not found.'
                );
            }

            $stmt = $this->con->prepare("
                DELETE FROM price_logs
                WHERE id = ?
            ");

            $stmt->execute([
                $id
            ]);

            if ($stmt->rowCount() === 0) {
                return $this->error(
                    'Price record could not be deleted.'
                );
            }

            return $this->success(
                'Price record deleted successfully.'
            );
        } catch (PDOException $e) {
            error_log('deletePrice: ' . $e->getMessage());

            return $this->error(
                'Database error: ' . $e->getMessage()
            );
        }
    }
}