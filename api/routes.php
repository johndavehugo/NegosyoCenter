<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'controllers/MSMEController.php';
require_once 'controllers/PriceMonitoringController.php';
require_once 'controllers/CalamityController.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = [];
}
if (empty($input) && !empty($_POST)) {
    $input = $_POST;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
$base = rtrim(dirname($scriptPath), '/\\');

$pathInfo = $uri;
if ($scriptPath && strpos($pathInfo, $scriptPath) === 0) {
    $pathInfo = substr($pathInfo, strlen($scriptPath));
} elseif ($base && strpos($pathInfo, $base) === 0) {
    $pathInfo = substr($pathInfo, strlen($base));
}
$pathInfo = trim($pathInfo, '/');
$segments = array_values(array_filter(explode('/', $pathInfo), function ($segment) {
    return $segment !== '';
}));

$resource = $_GET['resource'] ?? ($segments[0] ?? '');
switch ($resource) {
    case 'business':
        $controller = new MSMEController();

        if ($method === 'GET') {
            if (isset($segments[1])) {
                $response = $controller->getBusinessByEntityNo($segments[1]);
            } else {
                $response = $controller->getBusinesses();
            }
        } elseif ($method === 'POST') {
            $response = $controller->addBusiness($input);
        } elseif ($method === 'PUT') {
            $response = $controller->updateBusiness($input);
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' => 'Invalid request method for /business. Please use GET, POST, or PUT.'
            ];
        }
        break;

    // Belly's Routes

    case 'price':
        $controller = new PriceMonitoringController();

        if ($method === 'GET') {
            if (isset($_GET['action']) && $_GET['action'] === 'agencies') {
                $response = $controller->getAgencies();
            } elseif (isset($_GET['action']) && $_GET['action'] === 'commodities') {
                $response = $controller->getCommodities();
            } else {
                $recordId = null;

                if (isset($segments[1]) && is_numeric($segments[1])) {
                    $recordId = $segments[1];
                } elseif (isset($_GET['id'])) {
                    $recordId = $_GET['id'];
                }

                if ($recordId !== null) {
                    $response = $controller->getPriceById($recordId);
                } else {
                    $response = $controller->getPrices();
                }
            }
        } elseif ($method === 'POST') {
            $response = $controller->addPrice($input);
        } elseif ($method === 'PUT') {
            $response = $controller->updatePrice($input);
        } elseif ($method === 'DELETE') {
            $recordId = null;
            if (isset($segments[1]) && is_numeric($segments[1])) {
                $recordId = $segments[1];
            }

            if ($recordId !== null) {
                $response = $controller->deletePrice($recordId);
            } else {
                http_response_code(400);
                $response = [
                    'status' => 'error',
                    'message' => 'Missing ID for delete.'
                ];
            }
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' => 'Invalid request method for /price.'
            ];
        }
        break;

    case 'price-monitoring':
    $controller = new PriceMonitoringController();

    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'commodity_categories';

        switch ($action) {
            case 'commodity_categories':
                $response = $controller->getCategories();
                break;
            default:
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action.'
                ];
                break;
        }

    } elseif ($method === 'POST') {
        $action = $input['action'] ?? $_POST['action'] ?? '';

        switch ($action) {
            case 'add_category':
                $response = $controller->addCategory($input ?? $_POST);
                break;
            default:
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action for POST request.'
                ];
                break;
                
        }
        
        } elseif ($method === 'PUT') {
        $response = $controller->updateCategory($input);

    } else {
        http_response_code(405);
        $response = [
            'status' => 'error',
            'message' => 'Invalid request method for /price-monitoring. Please use GET, POST, PUT, or DELETE.'
        ];
    }




    break;



    // End of Belly's routes

    case 'calamity':
        $controller = new CalamityController();

        if ($method === 'GET') {
            $action = $_GET['action'] ?? 'calamities';
            if ($action === 'juridicals') {
                $response = $controller->getJuridicals();
            } else {
                $response = $controller->getCalamities();
            }
        } elseif ($method === 'POST') {
            if (($input['type'] ?? '') === 'calamity') {
                $response = $controller->addCalamity($input);
            } else {
                $response = $controller->addIncident($input);
            }
        } elseif ($method === 'PUT') {
            $response = $controller->updateIncident($input);
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error',
                'message' => 'Invalid request method for /calamity. Please use GET, POST, or PUT.'
            ];
        }
        break;

    case 'scims':
        $json = json_decode(file_get_contents('https://vamosmobile.app/api/testjuridical/business'), true);
        $response = ['data' => $json];
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
