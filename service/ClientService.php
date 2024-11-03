<?php
require_once __DIR__ . '/../dao/ClientDao.php';
require_once __DIR__ . '/../bean/Client.php';

class ClientService
{
    private $clientDao;

    public function __construct()
    {
        $this->clientDao = new ClientDao();
    }

    public function createClient($nom, $prenom, $sin)
    {
        $client = new Client(null,$nom, $prenom, $sin);
        return $this->clientDao->create($client);
    }

    public function getClientById($id)
    {
        return $this->clientDao->getClientById($id);
    }

    public function updateClient($id, $nom, $prenom, $sin)
    {
        $client = $this->clientDao->getClientById($id);
        if ($client) {
            $client->setNom($nom);
            $client->setPrenom($prenom);
            $client->setSin($sin);
            return $this->clientDao->update($client);
        }
        return false;
    }

    public function deleteClient($id)
    {
        return $this->clientDao->delete($id);
    }

    public function getAllClients()
    {
        return $this->clientDao->getAll();
    }
}
