<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'controllers/MSMEController.php';
require_once 'controllers/PriceMonitoringController.php';

$method = $_SERVER['REQUEST_METHOD'];

// Parse JSON payload or fallback to standard POST
$input = json_decode(file_get_contents('php://input'), true) ?? [];
if (empty($input) && !empty($_POST)) {
    $input = $_POST;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$scriptPath = $_SERVER['SCRIPT_NAME'];
$base = rtrim(dirname($scriptPath), '/\\');

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
        } else {
            http_response_code(405);
            $response = ['status' => 'error', 'message' => 
            'Invalid request method for /business. Please use GET, POST, or PUT.'];
        }
        break;

    // "Belly's ROUTES"

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
                } elseif (isset($_GET['id']) && !empty($_GET['id'])) {
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
                $response = ['status' => 'error', 'message' => 'Missing ID for delete.'];
            }
        } else {
            http_response_code(405);
            $response = [
                'status' => 'error', 
                'message' => 'Invalid request method for /price.'
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