<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'controllers/MSMEController.php';
require_once 'controllers/CalamityController.php';
require_once 'controllers/PriceMonitoringController.php';

$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/NegosyoCenter/api';

$pathInfo = substr($uri, strlen($base));

$pathInfo = trim($pathInfo, '/');

$segments = explode('/', $pathInfo);

$resource = '';

if (!empty($segments[0]) && strpos($segments[0], '.php') === false) {
    $resource = $segments[0];
} elseif (!empty($segments[1])) {
    $resource = $segments[1];
}

switch ($resource) {

    case 'business':
        if ($method === 'GET') {
            $controller = new MSMEController();
            if (isset($segments[2])) {
                $response = $controller->getBusinessByEntityNo($segments[2]);
            } else {
                $response = $controller->getBusinesses();
            }
        } elseif ($method === 'POST') {
            $controller = new MSMEController();
            $response = $controller->addBusiness($input);
        } elseif ($method === 'PUT') {
            $controller = new MSMEController();
            $response = $controller->updateBusiness($input);
        } elseif ($method === 'PATCH') {
            $controller = new MSMEController();
            $response = $controller->patchBusiness($segments[2] ?? '', $input);
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' =>
                    'Invalid request method for /business'
            ];
        }
        break;

    case 'calamity':
        if ($method === 'GET') {
            $controller = new CalamityController();
            $action = $_GET['action'] ?? 'calamities';
            if ($action === 'juridicals') {
                $response = $controller->getJuridicals();
            } elseif ($action === 'juridical_search') {
                $response = $controller->searchJuridicals($_GET);
            } elseif ($action === 'calamity_detail') {
                $calamity_id = intval($_GET['calamity_id'] ?? 0);
                $response = $controller->getCalamityDetail($calamity_id);
            } else {
                $response = $controller->getCalamities();
            }
        } elseif ($method === 'POST') {
            $controller = new CalamityController();
            if (($input['type'] ?? '') === 'calamity') {
                $response = $controller->addCalamity($input);
            } else {
                $response = $controller->addIncident($input);
            }
        } elseif ($method === 'PUT') {
            $controller = new CalamityController();
            if (($input['type'] ?? '') === 'calamity') {
                $response = $controller->updateCalamity($input);
            } else {
                $response = $controller->updateIncident($input);
            }
        } elseif ($method === 'DELETE') {
            $controller = new CalamityController();
            $id = $_GET['id'] ?? ($input['id'] ?? ($segments[2] ?? null));
            if ($id === null || $id === '') {
                http_response_code(400);
                $response = ['status' => 'error', 'message' => 'Missing or invalid affected business ID.'];
            } else {
                $response = $controller->deleteAffectedBusiness($id);
            }
        } else {
            http_response_code(405);
            $response = ['status' => 'error', 'message' => 'Invalid request method for /calamity. Please use GET, POST, or PUT.'];
        }
        break;


    case 'price-monitoring':
        $controller = new PriceMonitoringController();

        if ($method === 'GET') {
            $action = $_GET['action'] ?? 'commodity_categories';
            if ($action === 'commodity_categories') {
                $response = $controller->getCategories();
            } else {
                http_response_code(400);
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action.'
                ];
            }
        } elseif ($method === 'POST') {
            $action = $_GET['action'] ?? ($input['action'] ?? '');
            if ($action === 'add_category') {
                $response = $controller->addCategory($input);
            } else {

                http_response_code(400);

                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action.'
                ];
            }
        } elseif ($method === 'PUT') {
            $response = $controller->updateCategory($input);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? ($segments[2] ?? null);
            if (!$id || !is_numeric($id)) {
                http_response_code(400);
                $response = [
                    'status' => 'error',
                    'message' => 'Missing or invalid category ID.'
                ];
            } else {
                $response = $controller->deleteCategory(
                    (int) $id
                );
            }
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' =>
                    'Invalid request method for /price-monitoring.'
            ];
        }
        break;
    case 'commodity':
        $controller = new PriceMonitoringController();

        if ($method === 'GET') {
            if (($_GET['action'] ?? '') === 'public') {
                $response = $controller->getPublicCommodities();
            } elseif (
                isset($_GET['id']) &&
                $_GET['id'] !== ''
            ) {
                $response = $controller->getCommodityById(
                    $_GET['id']
                );
            } else {
                $response = $controller->getCommodityList();
            }
        } elseif ($method === 'POST') {
            $response = $controller->addCommodity($input);
        } elseif ($method === 'PUT') {
            $response = $controller->updateCommodity($input);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? ($segments[2] ?? null);
            if (
                $id === null ||
                $id === '' ||
                !is_numeric($id)
            ) {
                http_response_code(400);
                $response = [
                    'status' => 'error',
                    'message' => 'Missing or invalid commodity ID.'
                ];
            } else {
                $response = $controller->deleteCommodity(
                    (int) $id
                );
            }
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' =>
                    'Invalid request method for /commodity.'
            ];
        }
        break;

    case 'price':
        $controller = new PriceMonitoringController();

        if ($method === 'GET') {
            $action = $_GET['action'] ?? '';
            if ($action === 'agencies') {
                $response = $controller->getAgencies();
            } elseif ($action === 'commodities') {
                $response = $controller->getCommodities(
                    $_GET['agency_id'] ?? null
                );
            } elseif (
                isset($_GET['id']) &&
                $_GET['id'] !== ''
            ) {
                $response = $controller->getPriceById(
                    $_GET['id']
                );
            } else {
                $agencyId = $_GET['agency_id']
                    ?? $_GET['monitored_by_agency_id']
                    ?? null;
                $response = $controller->getPrices($agencyId);
            }
        } elseif ($method === 'POST') {
            $response = $controller->addPrice($input);
        } elseif ($method === 'PUT') {
            $response = $controller->updatePrice($input);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? ($segments[2] ?? null);
            if (
                $id === null ||
                $id === '' ||
                !is_numeric($id)
            ) {
                http_response_code(400);
                $response = [
                    'status' => 'error',
                    'message' => 'Missing or invalid price ID.'
                ];
            } else {
                $response = $controller->deletePrice(
                    (int) $id
                );
            }
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' =>
                    'Invalid request method for /price.'
            ];
        }

        break;

    default:
        http_response_code(404);
        $response = [
            'status' => 'error',
            'message' => 'Resource not found.'
        ];
        break;
}

echo json_encode($response);