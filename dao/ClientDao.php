<?php
require_once __DIR__ . '/../bean/Client.php';
require_once __DIR__ . '/../connect/ConnectDb.php';

class ClientDao
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = ConnectDb::getInstance()->getpdo();
    }

    public function create(Client $client)
    {
        $sql = "INSERT INTO client (nom, prenom, sin) VALUES ( ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $client->getNom(),
            $client->getPrenom(),
            $client->getSin()
        ]);
    }

    public function getClientById($id)
    {
        $sql = "SELECT * FROM client WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['sin']
            );
        }
        return null;
    }

    public function update(Client $client)
    {
        $sql = "UPDATE client SET nom = ?, prenom = ?, sin = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $client->getNom(),
            $client->getPrenom(),
            $client->getSin(),
            $client->getId()
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM client WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM client";
        $stmt = $this->pdo->query($sql);
        $clients = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clients[] = new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['sin']
            );
        }
        return $clients;
    }
}
