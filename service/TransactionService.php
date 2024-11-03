<?php
require_once __DIR__ . '/../dao/TransactionDao.php';
require_once 'TypeService.php';
require_once 'CompteBancaireService.php';
require_once __DIR__ . '/../bean/CompteBancaire.php';
require_once __DIR__ . '/../bean/Client.php';

require_once __DIR__ . '/../bean/Type.php';

class TransactionService
{
    private TransactionDao $transactionDao;
    private CompteBancaireService $compteService;
    private TypeService $typeService;

    public function __construct()
    {
        $this->transactionDao = new TransactionDao();
        $this->typeService = new TypeService();
        $this->compteService = new CompteBancaireService();

    }

    public function getAllTransactions(): array
    {
        $res = $this->transactionDao->getAllTransactions();
        $transactions = [];
        if (!empty($res)) {
            foreach ($res as $account) {
                $transactions[] = $account->toArray();
            }
        }
        return $transactions;
    }

    public function getTransactionsByClientId(int $clientId): array
    {
        $res = $this->transactionDao->getTransactionsByClientId($clientId);
        $transactions = [];
        if (!empty($res)) {
            foreach ($res as $account) {
                $transactions[] = $account->toArray();
            }
        }
        return $transactions;
    }

    public function getTransactionsByCompteId(int $compteId): array
    {
        $res = $this->transactionDao->getTransactionsByCompteId($compteId);
        $transactions = [];
        if (!empty($res)) {
            foreach ($res as $account) {
                $transactions[] = $account->toArray();
            }
        }
        return $transactions;
    }


    public function getTransactionByDate($startD, $endD)
    {
        if (is_string($startD)) {
            $startD = DateTime::createFromFormat('Y-m-d', $startD);
        }
        if (is_string($endD)) {
            $endD = DateTime::createFromFormat('Y-m-d', $endD);
        }

        if (!$startD || !$endD) {
            return -5;
        }
        $res = $this->transactionDao->getTransactionBydate($startD, $endD);
        $transactions = [];
        if (!empty($res)) {
            foreach ($res as $account) {
                $transactions[] = $account->toArray();
            }
        }
        return $transactions;
    }

    public function createTransaction(Transaction $transaction): int
    {
        if (is_string($transaction->getDate())) {
            $transaction->setDate(DateTime::createFromFormat('Y-m-d', $transaction->getDate()));
        }
        $res = $this->compteService->findByRib($transaction->getCompte()->getRib());
        if ($res == null) {
            return -1;
        } else {
            $transaction->setCompte($res);
        }
        $res1 = $this->typeService->findById($transaction->getType()->getId());
        if ($res1 == null) {
            return -2;
        } else {
            $transaction->setType($res1);

        }
        if ($this->transactionDao->create($transaction)) {
            return 1;
        } else {
            return -3;
        }
    }


    public function getTransactionById(int $id): ?Transaction
    {
        return $this->transactionDao->getTransactionById($id);
    }

    public function updateTransaction(Transaction $transaction): int
    {
        $res2 = $this->transactionDao->getTransactionById($transaction->getId());
        if ($res2 == null) {
            return -4;
        } else {
            $transaction1 = $res2;
        }
        if ($transaction->getDate() != "") {
            $transaction1->setDate(DateTime::createFromFormat('Y-m-d', $transaction->getDate()));
        }
        if ($transaction->getMontant() != "") {

                $transaction1->setMontant($transaction->getMontant());

        }
        if ($transaction->getCompte()->getRib() != "") {
            $res = $this->compteService->findByRib($transaction->getCompte()->getRib());
            if ($res == null) {
                return -1;
            } else {
                $transaction1->setCompte($res);
            }
        }
        if ($transaction->getType()->getId() != "") {
            $res1 = $this->typeService->findById($transaction->getType()->getId());
            if ($res1 == null) {
                return -2;
            } else {
                $transaction1->setType($res1);
            }
        }
        if ($this->transactionDao->update($transaction1)) {
            return 1;
        } else {
            return -3;
        }
    }

    public function deleteTransactionById(int $id): int
    {
        $res = $this->transactionDao->deleteById($id);
        if ($res) {
            if ($this->transactionDao->deleteById($id)) {
                return 1;
            } else {
                return -2;
            }
        } else {
            return -1;
        }
    }

    public function cancelTransaction(int $id): int
    {
        $res = $this->transactionDao->getTransactionById($id);
        if ($res == null) {
            return -1;
        } else {
            $transaction = new Transaction(null, -$res->getMontant(), $res->getDate(), $res->getType(), $res->getCompte());
            if ($this->createTransaction($transaction)) {
                return 1;
            } else {
                return -2;
            }
        }
    }
}
