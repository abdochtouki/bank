<?php
require_once __DIR__ . '/../bean/CompteBancaire.php';
require_once __DIR__ . '/../bean/Transaction.php';
require_once __DIR__ . '/../bean/Type.php';
require_once __DIR__ . '/../connect/ConnectDb.php';

class TransactionDao
{
    private PDO $pdotran;
    private TypeDao $daotype;
    private CompteBancaireDao $daocompte;

    public function __construct()
    {
        $this->pdotran = ConnectDb::getInstance()->getpdo();
        $this->daotype = new TypeDao();
        $this->daocompte = new CompteBancaireDao();
    }

    public function getAllTransactions(): array {
        $sql = "SELECT * FROM transaction";
        $stmt = $this->pdotran->prepare($sql);
        $stmt->execute();
        $transactions = [];
        while ($result = $stmt->fetch()) {
            $compte = $this->daocompte->findById($result['compteBancaire_id']);
            if (!$compte) {
                continue;
            }
            $type = $this->daotype->findById($result['type_id']);
            if (!$type) {
                continue;
            }
            $transactions[] = new Transaction(
                $result['id'],
                $result['montant'],
                $result['date'],
                $type,
                $compte
            );
        }
        return $transactions;
    }

    public function getTransactionsByClientId(int $clientId) {
        $sql = "SELECT * FROM transaction t JOIN comptebancaire c ON t.compteBancaire_id = c.id WHERE c.client_id = ?";
        $stmt = $this->pdotran->prepare($sql);
        $stmt->execute([$clientId]);
        $transactions = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $compte = $this->daocompte->findById($result['compteBancaire_id']);
            $type = $this->daotype->findById($result['type_id']);
            if ($compte !== null && $type !== null) {
                $transactions[] = new Transaction(
                    $result['id'],
                    $result['montant'],
                    $result['date'],
                    $type,
                    $compte
                );
            }
        }

        return $transactions;
    }


    public function getTransactionsByCompteId(int $compteId): array {
        $sql = "SELECT * FROM transaction WHERE compteBancaire_id = ?";
        $stmt = $this->pdotran->prepare($sql);
        $stmt->execute([$compteId]);
        $transactions = [];
        while ($result = $stmt->fetch()) {
            $type = $this->daotype->findById($result['type_id']);
            $compte=$this->daocompte->findById($compteId);
            $transactions[] = new Transaction(
                $result['id'],
                $result['montant'],
                $result['date'],
                $type,
                $compte
            );
        }
        return $transactions;
    }


    public function getTransactionBydate($startD, $endD): array {
        $sql = "SELECT * FROM transaction WHERE date BETWEEN ? AND ?";
        $stmt = $this->pdotran->prepare($sql);
        $stmt->execute([$startD->format('Y-m-d'), $endD->format('Y-m-d')]);

        $transactions = [];
        while ($result = $stmt->fetch()) {
            $compte = $this->daocompte->findById($result['compteBancaire_id']);
            $type = $this->daotype->findById($result['type_id']);
            $transactions[] = new Transaction(
                $result['id'],
                $result['montant'],
                $result['date'],
                $type,
                $compte
            );
        }
        return $transactions;
    }


    public function create(Transaction $transaction): bool {
        $compteId = $transaction->getCompte()->getId();
        $typeId = $transaction->getType()->getId();

        $sql = "INSERT INTO transaction (id, montant, date, type_id, compteBancaire_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdotran->prepare($sql);
        return $stmt->execute([
            $transaction->getId(),
            $transaction->getMontant(),
            $transaction->getDate()->format('Y-m-d'),
            $typeId,
            $compteId

        ]);
    }

    public function getTransactionById($id) {
        $sql = "SELECT * FROM transaction WHERE id = ?";
        $stmt = $this->pdotran->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        if ($result) {
            return new Transaction(
                $result['id'],
                $result['montant'],
                $result['date'],
                $this->daotype->findById($result['type_id']),
                $this->daocompte->findById($result['compteBancaire_id'])
            );
        } else {
            return null;
        }
    }

    public function update(Transaction $transaction): bool {
        $compteId = $transaction->getCompte()->getId();
        $typeId = $transaction->getType()->getId();

        $sql = "UPDATE transaction SET montant=?, date=?, type_id=?, compteBancaire_id=? WHERE id=?";
        $stmt = $this->pdotran->prepare($sql);

        return $stmt->execute([
            $transaction->getMontant(),
            $transaction->getDate()->format('Y-m-d'),
            $typeId,
            $compteId,
            $transaction->getId(),
        ]);
    }

    public function deleteById($id): bool
    {
        $sql = "DELETE FROM transaction WHERE id = ?";
        $stmt = $this->pdotran->prepare($sql);
        return $stmt->execute([$id]);
    }
}
