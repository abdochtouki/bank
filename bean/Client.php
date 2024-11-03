<?php

class Client {
    private  $id;
    private  $nom;
    private  $prenom;
    private  $sin;

    public function __construct( $id,  $nom,  $prenom,  $sin) {
        $this->id=$id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->sin = $sin;
    }
    public function getId() {
        return $this->id;
    }

    public function setId( $id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom( $nom) {
        $this->nom = $nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom( $prenom) {
        $this->prenom = $prenom;
    }

    public function getSin() {
        return $this->sin;
    }

    public function setSin( $sin) {
        $this->sin = $sin;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'sin' => $this->sin
        ];
    }
}

