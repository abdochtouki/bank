<?php
require_once __DIR__ . '/../dao/TypeDao.php';

class TypeService
{
    private TypeDao $dao;

    public function __construct()
    {
        $this->dao = new TypeDao();
    }

    public function addType(Type $type): int
    {
        $typex = $this->dao->findById($type->getId());
        if ($typex) {
            return -1;
        } else {

            if($this->dao->addType($type)){
                return 1;
            }else{
                return -2;
            }
        }
    }

    public function save(Type $type)
    {
        if ($this->dao->save($type)) {
            return 1;
        } else {
            return -1;
        }
    }

    public function findById($id): ?Type
    {
        return $this->dao->findById($id);
    }

    public function update(Type $type): int
    {
        $typex = $this->dao->findById($type->getId());
        if($type){
        if ($this->dao->update($type)) {
            return 1;
        } else {
            return -1;
        }}else{
            return -2;
        }
    }

    public function deleteById($id): int
    {   $typex = $this->dao->findById($id);
        if($typex){
            if ($this->dao->deleteById($id)) {
                return 1;
            } else {
                return -1;
            }
        }else{
            return 2;
        }

    }

    public function findAll(): array
    {
        return $this->dao->findAll();
    }
}