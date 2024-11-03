<?php
require_once __DIR__ . '/../ws/CompteBancaireWs.php';
require_once __DIR__ . '/../bean/CompteBancaire.php';
require_once __DIR__ . '/../ws/ClientWs.php';
require_once __DIR__ . '/../bean/Client.php';
require_once __DIR__ . '/../ws/TransactionWs.php';
require_once __DIR__ . '/../bean/Transaction.php';
require_once __DIR__ . '/../ws/TypeWs.php';
require_once __DIR__ . '/../bean/Type.php';

header('Content-Type: application/json');

$ws = new TransactionWs();
$comptews = new CompteBancaireWs();
$typeWs=new TypeService();
$method = $_SERVER['REQUEST_METHOD'];
$response = '';
switch ($method) {
    case 'GET':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['action'] === 'findById') {
            if (isset($data['id'])) {
                $response = $ws->getTransactionById($data['id']);
            } else {
                $response = json_encode(['status' => 400, 'message' => 'invalid data']);
            }
        } elseif ($data['action'] === 'findByClient') {
            if (isset($data['client_id'])) {
                $response = $ws->getTransactionsByClientId($data['client_id']);
            } else {
                $response = json_encode(['status' => 400, 'message' => 'invalid data']);
            }
        } elseif ($data['action'] === 'findByCompte') {
            if (isset($data['compte_id'])) {
                $response = $ws->getTransactionsByCompteId($data['compte_id']);
            } else {
                $response = json_encode(['status' => 400, 'message' => 'invalid data']);
            }
        } elseif ($data['action'] === 'findByDate') {
            if (isset($data['startD']) && isset($data['endD'])) {
                $response = $ws->getTransactionByDate($data['startD'], $data['endD']);
            } else {
                $response = json_encode(['status' => 400, 'message' => 'invalid data']);
            }
        } else {
            $response = $ws->getAllTransactions();
        }

        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['id'],$data['montant'],$data['date'],$data['type_id'],$data['rib'])){
            $client = new Client(null, "", "", 0);
            $compte = new CompteBancaire(null, $data['rib'], 0, "", $client);
            $type = new Type($data['type_id'], "", "");
            $transaction = new Transaction($data['id'], $data['montant'], $data['date'], $type, $compte);

            $response = $ws->updateTransaction($transaction);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['montant'],$data['date'],$data['type_id'],$data['rib'])){
            $client = new Client(null, "", "", 0);
            $compte = new CompteBancaire(null, $data['rib'], 0, "", $client);
            $type = new Type($data['type_id'], "", "");
            $transaction = new Transaction(null, $data['montant'], $data['date'], $type, $compte);

            $response = $ws->createTransaction($transaction);
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['id'])){
            $response=$ws->deleteTransactionById($data['id']);
        }
        break;
    default:
        $response = json_encode(['status' => 'error', 'message' => 'Invalid HTTP method']);
}
echo $response;