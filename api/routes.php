<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

header('Content-Type: application/json');


require_once 'controllers/MSMEController.php';
require_once 'controllers/CalamityController.php';



$method = $_SERVER['REQUEST_METHOD'];

$input  = json_decode(file_get_contents('php://input'), true) ?? [];

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
            $response = $controller->getBusinesses();          
        } elseif ($method === 'POST') {
            $controller = new MSMEController();
            $response = $controller->addBusiness($input);
        } else {
            http_response_code(405);
            $response = ['status' => 'error', 'message' => 
            'Invalid request method for /business. Please use GET, POST, or PUT.'];
        }
    break;

    case 'scims':
        $json = json_decode(file_get_contents('https://vamosmobile.app/api/testjuridical/business'), true);
        $response = ['data' => $json];
        break;
    case 'calamity':
    if ($method === 'GET') {
        $controller = new CalamityController();
        $action = $_GET['action'] ?? 'calamities';
        if ($action === 'juridicals') {
            $response = $controller->getJuridicals();
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
        $response = $controller->updateIncident($input);


    } else {
        http_response_code(405);
        $response = ['status' => 'error', 'message' => 'Invalid request method for /calamity. Please use GET, POST, or PUT.'];
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