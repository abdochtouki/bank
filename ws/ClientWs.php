<?php
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../bean/Client.php';

class ClientWs
{
    private $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    public function createClient($data)
    {
        if (!isset( $data['nom'], $data['prenom'], $data['sin'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            return;
        }
        $success = $this->clientService->createClient(
            $data['nom'],
            $data['prenom'],
            $data['sin']
        );
        if ($success) {
            http_response_code(201);
            echo json_encode(['message' => 'Client created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error creating client']);
        }
    }

    public function getClientById($id)
    {
        $client = $this->clientService->getClientById($id);
        if ($client) {
            http_response_code(200);
            return json_encode($client->toArray());
        } else {
            http_response_code(404);
            return json_encode(['error' => 'Client not found']);
        }
    }

    public function updateClient($id, $data)
    {
        if (!isset($data['nom'], $data['prenom'], $data['sin'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            return;
        }

        $success = $this->clientService->updateClient(
            $id,
            $data['nom'],
            $data['prenom'],
            $data['sin']
        );

        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Client updated successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Client not found']);
        }
    }

    public function deleteClient($id)
    {
        $success = $this->clientService->deleteClient($id);
        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Client deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Client not found']);
        }
    }

    public function getAllClients()
    {
        $clients1 = $this->clientService->getAllClients();
        $clients = [];
        foreach ($clients1 as $client) {
            $clients[] = $client->toArray();
        }
        http_response_code(200);
        echo json_encode($clients);
    }

}
