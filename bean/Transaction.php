<?php
require_once 'CompteBancaire.php';
require_once 'Type.php';
require_once 'Client.php';
class Transaction
{
    private $id;
    private $montant;
    private $date;
    private Type $type;
    private  CompteBancaire $compte;
    public function __construct($id ,$montant ,$date,Type $type,CompteBancaire  $compte){
        $this->id=$id;
        $this->montant=$montant;
        $this->date=$date;
        $this->type=$type;
        $this->compte=$compte;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type $type
     */
    public function setType(Type $type): void
    {
        $this->type = $type;
    }

    /**
     * @return CompteBancaire
     */
    public function getCompte(): CompteBancaire
    {
        return $this->compte;
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @param CompteBancaire $compte
     */
    public function setCompte(CompteBancaire $compte): void
    {
        $this->compte = $compte;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant): void
    {
        $this->montant = $montant;
    }
    public function toArray(): array
    {
    return [
        'id' => $this->id,
        'montant' => $this->montant,
        'date'=>$this->date,
        'type' => $this->type->toArray(),
        'compte' => $this->compte->toArray()
    ];
}


}