<?php
require_once 'Client.php';

class CompteBancaire {
    private $id;
    private $rib;
    private $solde;
    private $ouvert;
    private Client $client;

    public function __construct($id, $rib, $solde, $ouvert) {
        $this->id = $id;
        $this->rib = $rib;
        $this->solde = $solde;
        $this->ouvert = $ouvert;
    }


    public function setClient(Client $client) {
        $this->client = $client;
    }
    public function getClient(): Client
    {
        return $this->client;
    }
    public function getId() {
        return $this->id;
    }

    public function getRib() {
        return $this->rib;
    }
    public function getSolde()  {
        return $this->solde;
    }
    public function getOuvert() {
        return $this->ouvert;
    }
    public function setId( $id) {
        $this->id = $id;
    }
    public function setRib( $rib) {
        $this->rib = $rib;
    }
    public function setSolde( $solde) {
        $this->solde = $solde;
    }
    public function setOuvert( $ouvert) {
        $this->ouvert = $ouvert;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rib' => $this->rib,
            'solde' => $this->solde,
            'ouvert' => $this->ouvert,
            'client' => $this->client->toArray()
        ];
    }
}

