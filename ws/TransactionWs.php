<?php
require_once __DIR__ . '/../service/TransactionService.php';
require_once __DIR__ . '/../bean/Transaction.php';
require_once __DIR__ . '/../bean/CompteBancaire.php';
require_once __DIR__ . '/../bean/Type.php';
class TransactionWs
{
    private TransactionService $service;

    public function __construct()
    {
        $this->service = new TransactionService();
    }

    public function getAllTransactions()
    {
        $res[] = $this->service->getAllTransactions();
        if (!empty($res)) {
            $response = json_encode(['status' => 'success', 'data' => $res]);
        } else {
            $response = json_encode(['status' => 'error', 'message' => 'No transactions found.']);
        }

        return $response;
    }

    public function getTransactionsByClientId(int $clientId)
    {
        $res[] = $this->service->getTransactionsByClientId($clientId);
        print_r($res);
        if (!empty($res)) {
            return json_encode(['status' => 'success', 'data' => $res]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'No transaction found.']);
        }
    }

    public function getTransactionsByCompteId(int $compteId)
    {
        $res = $this->service->getTransactionsByCompteId($compteId);

        if (!empty($res)) {
            $response = json_encode(['status' => 'success', 'data' => $res]);
        } else {
            $response = json_encode(['status' => 'error', 'message' => 'No transactions found.']);
        }

        return $response;
    }

    public function getTransactionByDate( $startD,  $endD)
    {
        $res = $this->service->getTransactionByDate($startD, $endD);
        if (!empty($res)) {

            $response = json_encode(['status' => 'success', 'data' => $res]);
        } elseif($res==-5){
            $response = json_encode(['status' => 'error', 'message' => 'Invalid date format for start or end date']);
        }else{
            $response = json_encode(['status' => 'error', 'message' => 'No transactions found .']);
        }

        return $response;
    }

    public function createTransaction(Transaction $transaction)
    {
        $res = $this->service->createTransaction($transaction);
        if ($res == -1) {
            $response = json_encode(['status' => 'success', 'data' => 'Account is not found ']);
        } elseif ($res == -2) {
            $response = json_encode(['status' => 'success', 'data' => 'Type is not found ']);
        } elseif ($res == -3) {
            $response = json_encode(['status' => 'success', 'data' => 'error of creation']);
        } else {
            $response = json_encode(['status' => 'success', 'data' => 'creation is successful']);
        }
        return $response;
    }

    public function getTransactionById(int $id)
    {
        $res = $this->service->getTransactionById($id);
        if ($res) {
            $response = json_encode(['status' => 'success', 'data' => $res->toArray()]);
        } else {
            $response = json_encode(['status' => 'success', 'data' => 'Transaction is not found ']);
        }
        return $response;
    }

    public function updateTransaction(Transaction $transaction)
    {
        $res = $this->service->updateTransaction($transaction);
        if ($res == -1) {
            $response = json_encode(['status' => 'success', 'data' => 'Account is not found ']);
        } elseif ($res == -2) {
            $response = json_encode(['status' => 'success', 'data' => 'Type is not found ']);
        } elseif ($res == -4) {
            $response = json_encode(['status' => 'success', 'data' => 'Transaction is not found ']);
        } elseif ($res == -3) {
            $response = json_encode(['status' => 'success', 'data' => 'error of creation']);
        } else {
            $response = json_encode(['status' => 'success', 'data' => 'update is successful']);
        }
        return $response;
    }

    public function deleteTransactionById(int $id)
    {
        $res = $this->service->deleteTransactionById($id);
        if ($res == -1) {
            $response = json_encode(['status' => 'success', 'data' => 'Transaction is not found ']);
        } elseif ($res == -2) {
            $response = json_encode(['status' => 'success', 'data' => 'error of deleted']);
        } else {
            $response = json_encode(['status' => 'success', 'data' => 'deleted is successful']);
        }
        return $response;
    }

    public function cancelTransaction(int $id){
        $res = $this->service->cancelTransaction($id);
        if ($res == -1) {
            $response = json_encode(['status' => 'success', 'data' => 'Transaction is not found ']);
        } elseif ($res == -2) {
            $response = json_encode(['status' => 'success', 'data' => 'error of cancel']);
        } else {
            $response = json_encode(['status' => 'success', 'data' => 'cancel is successful']);
        }
        return $response;
    }

}