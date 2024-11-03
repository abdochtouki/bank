<?php

class Type
{
    private $id;
    private $libelle;
    private $code;
    public function __construct($id,$libelle,$code){
        $this->id=$id;
        $this->libelle=$libelle;
        $this->code=$code;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @param mixed $lebilll
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }
    public  function toArray(){
        return [
            'id'=>$this->id,
            'libelle'=>$this->libelle,
            'code'=>$this->code
        ];
    }

}