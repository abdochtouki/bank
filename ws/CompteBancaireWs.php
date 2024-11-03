<?php

require_once __DIR__ . '/../service/CompteBancaireService.php';
require_once __DIR__ . '/../bean/CompteBancaire.php';

class CompteBancaireWs
{
    private CompteBancaireService $service;

    public function __construct()
    {
        $this->service = new CompteBancaireService();
    }

    public function create(CompteBancaire $compte)
    {
        $result = $this->service->create($compte);
        if ($result === 1) {
            return json_encode(['status' => 'success']);
        } elseif ($result === -1) {
            return json_encode(['status' => 'error', 'message' => 'Account is found or already exists.']);
        }
    }

    public function save($compte)
    {
        $result = $this->service->save($compte);
        if ($result === 1) {
            return json_encode(['status' => 'success']);
        } elseif ($result === -1) {
            return json_encode(['status' => 'error', 'message' => 'Account not found or already exists.']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'An unknown error occurred.']);
        }
    }
    public function debloquerCompte($rib)
    {
        $result=$this->service->debloquerCompte($rib);
        if($result === -1){
            return json_encode(['status'=>400,'message'=>'impossible,account is not found']);
        }elseif($result==-2){
            return json_encode(['status'=>204,'message'=>'impossible!!!!,The account already unlock']);
        }else{
            return json_encode(['status'=>200,'message'=>'enable of account is successful']);
        }
    }
    public function bloquerCompte($rib)
    {
        $result=$this->service->bloquerCompte($rib);
        if($result === -1){
            return json_encode(['status'=>400,'message'=>'impossible,account is not found']);
        }elseif($result==-2){
            return json_encode(['status'=>204,'message'=>'impossible!!!!,The account already blocked']);
        }else{
            return json_encode(['status'=>200,'message'=>'block of account is successful']);
        }
    }
    public function diposer($rib, $montant)
    {
        $result=$this->service->diposer($rib,$montant);
        if($result === -1){
            return json_encode(['status'=>400,'message'=>'account is not found']);
        }elseif($result==-2){
            return json_encode(['status'=>204,'message'=>'impossible!!!!!!!!,The account blocked']);
        }else{
            return json_encode(['status'=>200,'message'=>'He has the money and is successful']);
        }
    }
    public function debiter($rib, $montant) {
        $result = $this->service->debiter($rib, $montant);
        if ($result === 1) {
            return json_encode(['status' => 'success']);
        } elseif ($result === -2) {
            return json_encode(['status' => 'error', 'message' => 'Insufficient funds or invalid balance.']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'An unknown error occurred.']);
        }
    }

    public function deleteByRib($rib) {
        $result = $this->service->deleteByRib($rib);
        if ($result === 1) {
            return json_encode(['status' => 'success']);
        } elseif ($result === -3) {
            return json_encode(['status' => 'error', 'message' => 'Account has non-zero balance or is closed.']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'An unknown error occurred.']);
        }
    }

    public function findByRib($rib) {
        $result = $this->service->findByRib($rib);
        if ($result !== null) {
            return json_encode(['status' => 'success', 'data' => $result->toArray()]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Account not found.']);
        }
    }
    public function findById($rib) {
        $result = $this->service->findById($rib);
        if ($result !== null) {
            return json_encode(['status' => 'success', 'data' => $result->toArray()]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Account not found.']);
        }
    }
    public function findAll() {
        $result = $this->service->findAll();
        $accounts = [];

        if (is_array($result)) {
            foreach ($result as $account) {
                $accounts[] = $account->toArray();
            }
            return json_encode(['status' => 'success', 'data' => $accounts]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'No accounts found.']);
        }
    }

}
