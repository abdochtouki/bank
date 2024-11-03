<?php
require_once __DIR__ . '/../connect/ConnectDb.php';
require_once __DIR__ . '/../bean/Type.php';

class TypeDao
{
    private $pdo;
    public function __construct(){
        $this->pdo = ConnectDb::getInstance()->getpdo();
    }
    public function addType(Type $type) {
        $sql = "INSERT INTO type (libelle, code) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $type->getLibelle(),
            $type->getCode()
        ]);
    }

    public function save(Type $type){
        $sql="INSERT INTO type (libelle,code) VALUES (?,?)";
        $stmt=$this->pdo->prepare($sql);
       return  $stmt->execute([
           $type->getLibelle(),
           $type->getCode()
               ]
       );
    }
    public function findById($id): ?Type
    {
        $sql="SELECT * FROM type WHERE id = ?";
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row=$stmt->fetch();
        if($row){
            return new Type(
                $row['id'],
                $row['libelle'],
                $row['code']
            );
        }else{
            return null;
        }
    }
    public function update(Type $type){
        $sql="UPDATE  type SET libelle=?,code=? WHERE id=?";
        $stmt=$this->pdo->prepare($sql);
        return $stmt->execute([
            $type->getLibelle(),
            $type->getCode(),
            $type->getId()
                ]
        );
    }
    public  function deleteById($id){
        $sql="DELETE FROM type WHERE id=?";
        $stmt=$this->pdo->prepare($sql);
       return $stmt->execute([$id]);
    }
    public function findAll(): array
    {
        $stmt=$this->pdo->prepare("SELECT * FROM type  ");
        $stmt->execute();
        $types=[];
        while ($row=$stmt->fetch())
        {
            $types[]=new Type(
                $row['id'],
                $row['libelle'],
                $row['code']
            );

        }
        return $types;
    }

}