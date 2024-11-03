<?php

require_once __DIR__ . '/../ws/CompteBancaireWs.php';
require_once __DIR__ . '/../bean/CompteBancaire.php';
require_once __DIR__ . '/../ws/ClientWs.php';
require_once __DIR__ . '/../bean/Client.php';

header('Content-Type: application/json');

$ws = new CompteBancaireWs();
$clientws = new ClientWs();
$method = $_SERVER['REQUEST_METHOD'];
$response = '';

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['rib'], $data['solde'], $data['ouvert'], $data['client_id'])) {
            if (!is_numeric($data['solde'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Le solde doit être un nombre.']);
                break;
            }
            $clientData = json_decode($clientws->getClientById($data['client_id']));

            if ($clientData === null) {
                $client = new Client($data['client_id'], "", "", 0);
                $clientws->createClient($clientData);
            } else {
                $client = new Client($clientData->id, $clientData->nom, $clientData->prenom, $clientData->sin);
            }
            $compte = new CompteBancaire(null, $data['rib'], (float)$data['solde'], (bool)$data['ouvert'], $client);
            $response = $ws->create($compte);

        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes.']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'];
        $response = $ws->findByRib($data['rib']);
        if($response ) {
            switch ($action) {
                case 'debiter':
                    if (isset($data['rib']) && isset($data['montant'])) {
                        $response = $ws->debiter($data['rib'], $data['montant']);
                    }
                    break;
                case 'diposer':
                    if (isset($data['rib']) && isset($data['montant'])) {
                        $response = $ws->diposer($data['rib'], $data['montant']);
                    }
                    break;
                case 'bloquer':
                    if (isset($data['rib'])) {
                        $response = $ws->bloquerCompte($data['rib']);
                    }
                    break;
                case 'debloquer':
                    if (isset($data['rib'])) {
                        $response = $ws->debloquerCompte($data['rib']);
                    }
                    break;
                default:
                    $response = json_encode(['status' => 400, 'message' => 'no action found !!!!']);
            }
        }else{
            $response = json_encode(['status' => 400, 'message' => 'client is not found !!!!']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['rib'])) {
            $response = $ws->deleteByRib($data['rib']);
        }
        break;

    case 'GET':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['rib'])) {
            $response = $ws->findByRib($data['rib']);
        } elseif (isset($data['id'])) {
            $response = $ws->findById($data['id']);
        } else {
            $response = $ws->findAll();
        }
        break;

    default:
        $response = json_encode(['status' => 'error', 'message' => 'Invalid HTTP method']);
}

echo $response;
