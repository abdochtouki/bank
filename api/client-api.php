<?php

require_once __DIR__ . '/../ws/ClientWs.php';

header('Content-Type: application/json');

$clientWs = new ClientWs();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/')); // Change here
if ($requestUri[3] !== 'clients') {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

$id = isset($requestUri[4]) ? $requestUri[4] : null;

switch ($requestMethod) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $clientWs->createClient($data);
        break;

    case 'GET':
        if ($id) {
            echo $clientWs->getClientById($id);
        } else {
            $clientWs->getAllClients();
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $clientWs->updateClient($id, $data);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Client ID required']);
        }
        break;

    case 'DELETE':
        if ($id) {
            $clientWs->deleteClient($id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Client ID required']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
