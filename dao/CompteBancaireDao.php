<?php

require_once __DIR__ . '/../bean/CompteBancaire.php';
require_once __DIR__ . '/../bean/Client.php';
require_once __DIR__ . '/../connect/ConnectDb.php';
require_once  'ClientDao.php';


class CompteBancaireDao {
    private $pdo;
    private $clientDAO;

    public function __construct() {
        $this->pdo = ConnectDb::getInstance()->getpdo();
        $this->clientDAO = new ClientDao();

    }
    public function create(CompteBancaire $compte) {
        $clientId = $compte->getClient()->getId();
        $clientExists = $this->clientDAO->getClientById($clientId);

        if (!$clientExists) {
            $client = $compte->getClient();
            $this->clientDAO->create($client);
        } else {
            $client = $clientExists;
        }

        $sql = "INSERT INTO comptebancaire (rib, solde, ouvert, client_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $compte->getRib(),
            $compte->getSolde(),
            $compte->getOuvert(),
            $client->getId()
        ]);
    }


    public function findById( $id) {
        $sql = "SELECT * FROM comptebancaire WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $client=$this->clientDAO->getClientById($row['client_id']);
            return new CompteBancaire(
                $row['id'],
                $row['rib'],
                $row['solde'],
                (bool)$row['ouvert'],
                $client
            );
        }
        return null;
    }

    public function findByRib($rib): ?CompteBancaire
    {
        $sql = "SELECT * FROM comptebancaire WHERE rib = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ $rib]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $client=$this->clientDAO->getClientById($row['client_id']);
            if($client) {
                return new CompteBancaire(
                    $row['id'],
                    $row['rib'],
                    $row['solde'],
                    $row['ouvert'],
                    $client
                );
            }else{
                return null;
            }
        }
        return null;
    }

    public function update(CompteBancaire $compte): bool
    {
        $client = $compte->getClient();
        $clientId = $client->getId();

        $compteId = $compte->getId();

        $sql = "UPDATE comptebancaire SET rib = ?, solde = ?, ouvert = ?, client_id = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $compte->getRib(),
            $compte->getSolde(),
            $compte->getOuvert(),
            $clientId,
            $compteId
        ]);
    }



    public function save(CompteBancaire $compte) {
        if ($this->findById($compte->getId()) !== null) {
            return $this->update($compte);
        } else {
            return $this->create($compte);
        }
    }
    public function deleteById( $id) {
        $sql = "DELETE FROM comptebancaire WHERE id =?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
    public function deleteByRib( $rib) {
        $sql = "DELETE FROM comptebancaire WHERE rib = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$rib]);
    }


    public function findAll() {
        $sql = "SELECT * FROM comptebancaire";
        $stmt = $this->pdo->query($sql);
        $comptes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $client=$this->clientDAO->getClientById($row['client_id']);
            if (!$client) {
                continue;
            }
            $comptes[] = new CompteBancaire(
                $row['id'],
                $row['rib'],
                $row['solde'],
                $row['ouvert'],
                $client
            );
        }
        return $comptes;
    }
    /*public function findAll() {
        $sql = "SELECT * FROM comptebancaire";
        $stmt = $this->pdo->query($sql);
        $comptes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $client = $this->clientDAO->getClientById($row['client_id']);

            if (!$client) {
                continue;
            }

            $comptes[] = new CompteBancaire(
                $row['id'],
                $row['rib'],
                $row['solde'],
                $row['ouvert'],
                $client
            );
        }

        return $comptes;
    }
    */

}





