<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'controllers/MSMEController.php';
require_once 'controllers/PriceMonitoringController.php';
require_once 'controllers/CalamityController.php';


/* =========================================================
   REQUEST METHOD
========================================================= */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';


/* =========================================================
   READ REQUEST INPUT
========================================================= */

$input = [];

/*
 * JSON requests
 */
$rawInput = file_get_contents('php://input');

if (!empty($rawInput)) {

    $jsonInput = json_decode($rawInput, true);

    if (is_array($jsonInput)) {
        $input = $jsonInput;
    }
}


/*
 * FormData / application-x-www-form-urlencoded
 *
 * PHP places FormData fields into $_POST.
 */
if (!empty($_POST)) {

    $input = array_merge(
        $input,
        $_POST
    );
}


/* =========================================================
   DETERMINE ROUTE
========================================================= */

$uri = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
) ?? '/';

$scriptPath =
    $_SERVER['SCRIPT_NAME'] ?? '';

$base =
    rtrim(
        dirname($scriptPath),
        '/\\'
    );


$pathInfo = $uri;


/*
 * Remove routes.php from path
 */
if (
    $scriptPath &&
    strpos($pathInfo, $scriptPath) === 0
) {

    $pathInfo =
        substr(
            $pathInfo,
            strlen($scriptPath)
        );

}


/*
 * Otherwise remove base directory
 */
elseif (
    $base &&
    strpos($pathInfo, $base) === 0
) {

    $pathInfo =
        substr(
            $pathInfo,
            strlen($base)
        );
}


/*
 * Split path into segments
 */
$pathInfo =
    trim(
        $pathInfo,
        '/'
    );


$segments =
    array_values(
        array_filter(
            explode('/', $pathInfo),
            function ($segment) {

                return $segment !== '';

            }
        )
    );


/*
 * Resource can come from:
 *
 * routes.php?resource=price
 *
 * OR
 *
 * routes.php/price
 */
$resource =
    $_GET['resource']
    ?? ($segments[0] ?? '');


/* =========================================================
   ROUTES
========================================================= */

switch ($resource) {


    /* =====================================================
       BUSINESS
    ===================================================== */

    case 'business':

        $controller =
            new MSMEController();


        if ($method === 'GET') {

            if (isset($segments[1])) {

                $response =
                    $controller->getBusinessByEntityNo(
                        $segments[1]
                    );

            } else {

                $response =
                    $controller->getBusinesses();

            }


        } elseif ($method === 'POST') {

            $response =
                $controller->addBusiness($input);


        } elseif ($method === 'PUT') {

            $response =
                $controller->updateBusiness($input);


        } else {

            http_response_code(405);

            $response = [

                'status' => 'error',

                'message' =>
                    'Invalid request method for /business. Please use GET, POST, or PUT.'

            ];
        }

        break;



    /* =====================================================
       PRICE
    ===================================================== */

    case 'price':

        $controller =
            new PriceMonitoringController();


        /*
         * GET
         */
        if ($method === 'GET') {


            /*
             * GET agencies
             *
             * ?resource=price&action=agencies
             */
            if (
                isset($_GET['action']) &&
                $_GET['action'] === 'agencies'
            ) {

                $response =
                    $controller->getAgencies();


            }


            /*
             * GET commodities by agency
             *
             * ?resource=price
             * &action=commodities
             * &agency_id=3
             */
            elseif (
                isset($_GET['action']) &&
                $_GET['action'] === 'commodities'
            ) {

                $agencyId =
                    $_GET['agency_id']
                    ?? null;


                $response =
                    $controller->getCommodities(
                        $agencyId
                    );


            }


            /*
             * GET price record
             */
            else {

                $recordId = null;


                /*
                 * /price/123
                 */
                if (
                    isset($segments[1]) &&
                    is_numeric($segments[1])
                ) {

                    $recordId =
                        $segments[1];

                }


                /*
                 * ?id=123
                 */
                elseif (
                    isset($_GET['id'])
                ) {

                    $recordId =
                        $_GET['id'];

                }


                if ($recordId !== null) {

                    $response =
                        $controller->getPriceById(
                            $recordId
                        );

                }


                /*
                 * GET all prices
                 */
                else {

                    $agencyId =
                        $_GET['agency_id']
                        ?? null;


                    $response =
                        $controller->getPrices(
                            $agencyId
                        );
                }
            }


        }


        /*
         * POST PRICE
         */
        elseif ($method === 'POST') {

            $response =
                $controller->addPrice(
                    $input
                );

        }


        /*
         * PUT PRICE
         */
        elseif ($method === 'PUT') {

    /*
     * Get price record ID from:
     *
     * ?id=10
     *
     * or from FormData / JSON:
     *
     * id=10
     */
    $recordId =
        $_GET['id']
        ?? ($input['id'] ?? null);


    /*
     * Make sure the controller receives the ID
     */
    if (
        $recordId !== null &&
        $recordId !== ''
    ) {

        $input['id'] = $recordId;
    }


    $response =
        $controller->updatePrice(
            $input
        );

}


        /*
         * DELETE PRICE
         */
        elseif ($method === 'DELETE') {

            $recordId = null;


            if (
                isset($segments[1]) &&
                is_numeric($segments[1])
            ) {

                $recordId =
                    $segments[1];

            }


            elseif (
                isset($_GET['id'])
            ) {

                $recordId =
                    $_GET['id'];

            }


            if ($recordId !== null) {

                $response =
                    $controller->deletePrice(
                        $recordId
                    );

            } else {

                http_response_code(400);

                $response = [

                    'status' => 'error',

                    'message' =>
                        'Missing ID for delete.'

                ];
            }


        }


        /*
         * Invalid method
         */
        else {

            http_response_code(405);

            $response = [

                'status' => 'error',

                'message' =>
                    'Invalid request method for /price.'

            ];
        }


        break;



    /* =====================================================
       PRICE MONITORING
    ===================================================== */

    case 'price-monitoring':

        $controller =
            new PriceMonitoringController();


        if ($method === 'GET') {

            $action =
                $_GET['action']
                ?? 'commodity_categories';


            switch ($action) {


                case 'commodity_categories':

                    $response =
                        $controller->getCategories();

                    break;


                default:

                    $response = [

                        'status' => 'error',

                        'message' =>
                            'Invalid action.'

                    ];

                    break;
            }


        }


        elseif ($method === 'POST') {

            $action =
                $input['action']
                ?? $_POST['action']
                ?? '';


            switch ($action) {


                case 'add_category':

                    $response =
                        $controller->addCategory(
                            $input
                        );

                    break;


                default:

                    $response = [

                        'status' => 'error',

                        'message' =>
                            'Invalid action for POST request.'

                    ];

                    break;
            }


        }


        elseif ($method === 'PUT') {

            $response =
                $controller->updateCategory(
                    $input
                );


        }


        else {

            http_response_code(405);

            $response = [

                'status' => 'error',

                'message' =>
                    'Invalid request method for /price-monitoring.'

            ];
        }


        break;



    /* =====================================================
       COMMODITY
    ===================================================== */

    case 'commodity':

        $controller =
            new PriceMonitoringController();


        $id =
            $_GET['id']
            ?? ($segments[1] ?? null);


        /*
         * GET
         */
        if ($method === 'GET') {

            if (
                $id &&
                is_numeric($id)
            ) {

                $response =
                    $controller->getCommodityById(
                        $id
                    );

            } else {

                $response =
                    $controller->getCommodityList();

            }


        }


        /*
         * POST
         */
        elseif ($method === 'POST') {

            $response =
                $controller->addCommodity(
                    $input
                );

        }


        /*
         * PUT
         */
        elseif ($method === 'PUT') {

            $response =
                $controller->updateCommodity(
                    $input
                );

        }


        /*
         * DELETE
         */
        elseif ($method === 'DELETE') {

            if (
                $id &&
                is_numeric($id)
            ) {

                $response =
                    $controller->deleteCommodity(
                        $id
                    );

            } else {

                http_response_code(400);

                $response = [

                    'status' => 'error',

                    'message' =>
                        'Missing commodity ID.'

                ];
            }


        }


        else {

            http_response_code(405);

            $response = [

                'status' => 'error',

                'message' =>
                    'Invalid request method.'

            ];
        }


        break;



    /* =====================================================
       CALAMITY
    ===================================================== */

    case 'calamity':

        $controller =
            new CalamityController();


        if ($method === 'GET') {

            $action =
                $_GET['action']
                ?? 'calamities';


            if ($action === 'juridicals') {

                $response =
                    $controller->getJuridicals();

            } else {

                $response =
                    $controller->getCalamities();
            }


        }


        elseif ($method === 'POST') {

            if (
                ($input['type'] ?? '')
                === 'calamity'
            ) {

                $response =
                    $controller->addCalamity(
                        $input
                    );

            } else {

                $response =
                    $controller->addIncident(
                        $input
                    );
            }


        }


        elseif ($method === 'PUT') {

            $response =
                $controller->updateIncident(
                    $input
                );


        }


        else {

            http_response_code(405);

            $response = [

                'status' => 'error',

                'message' =>
                    'Invalid request method for /calamity. Please use GET, POST, or PUT.'

            ];
        }


        break;



    /* =====================================================
       SCIMS
    ===================================================== */

    case 'scims':

        $json =
            json_decode(
                file_get_contents(
                    'https://vamosmobile.app/api/testjuridical/business'
                ),
                true
            );


        $response = [

            'data' => $json

        ];


        break;



    /* =====================================================
       DEFAULT
    ===================================================== */

    default:

        http_response_code(404);

        $response = [

            'status' => 'error',

            'message' =>
                'Resource not found.'

        ];

        break;
}


/* =========================================================
   OUTPUT JSON
========================================================= */

echo json_encode(
    $response,
    JSON_UNESCAPED_UNICODE
);