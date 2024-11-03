<?php
require_once __DIR__ . '/../ws/TypeWs.php';
header('Content-Type: application/json');

$typeWs = new TypeWs();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if (isset($requestUri[3]) && $requestUri[3] !== 'type') {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

$id = isset($requestUri[4]) ? $requestUri[4] : null;

switch ($requestMethod) {
    case 'GET':
        if ($id) {
            echo $typeWs->findById($id);
        } else {
           echo $typeWs->findAll();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['libelle'],$data['code'])){
            $type= new Type(null,$data['libelle'],$data['code']);
            echo $typeWs->addType($type);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['id'],$data['libelle'],$data['code'])){
            $type= new Type($data['id'],$data['libelle'],$data['code']);
            echo $typeWs->update($type);
        }
        break;
    case 'DELETE':
        if($id){
            echo $typeWs->deleteById($id);
        }else{
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
