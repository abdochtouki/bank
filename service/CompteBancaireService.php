<?php

require_once __DIR__ . '/../dao/CompteBancaireDao.php';

class CompteBancaireService
{
    private CompteBancaireDao $dao;

    public function __construct()
    {
        $this->dao = new CompteBancaireDao();
    }

    public function save(CompteBancaire $compte): int
    {
        if ($this->findByRib($compte->getRib()) !== null) {
            return -1;
        } elseif ($compte->getSolde() <= 0) {
            return -2;
        } else {
            $compte->setOuvert(true);
            $this->dao->save($compte);
            return 1;
        }
    }

    public function bloquerCompte($rib): int
    {
        $compte = $this->dao->findByRib($rib);
        if ($compte) {
            if ($compte->getOuvert() === 1) {
                $compte->setOuvert(0);
                $this->dao->update($compte);
                return 1;
            } else {
                return -2;
            }
        } else {
            return -1;
        }
    }

    public function debloquerCompte($rib): int
    {
        $compte = $this->dao->findByRib($rib);
        if ($compte) {
            if ($compte->getOuvert() === 0) {
                $compte->setOuvert(1);
                $this->dao->update($compte);
                return 1;
            } else {
                return -2;
            }
        } else {
            return -1;
        }
    }

    public function create(CompteBancaire $compte): int
    {
        if ($this->findByRib($compte->getRib()) !== null) {
            return -1;
        } else {
            $this->dao->create($compte);
            return 1;
        }
    }

    public function diposer($rib, $montant)
    {
        $compte = $this->findByRib($rib);
        if ($compte === null) {
            return -1;
        }
        if ($compte->getOuvert() === 1) {
            $nvsolde = $compte->getSolde() + $montant;
            $compte->setSolde($nvsolde);
            $this->dao->update($compte);
            return 1;
        } else {
            return -2;
        }
    }

    public function debiter($rib, $montant): int
    {
        $compte = $this->findByRib($rib);
        if($compte!= null) {
            $nvsolde = $compte->getSolde() - $montant;
            if ($nvsolde < 0) {
                return -2;
            } else {
                $compte->setSolde($nvsolde);
                $this->dao->update($compte);
                return 1;
            }
        }else{
            return -1;
        }
    }

    public function deleteByRib($rib): int
    {
        $compte = $this->findByRib($rib);
        if ($compte === null) {
            return -1;
        } elseif ($compte->getOuvert()==1) {
            return -2;
        } elseif ($compte->getSolde() != 0) {
            return -3;
        } else {
            $this->dao->deleteByRib($rib);
            return 1;
        }
    }

    public function findByRib($rib): ?CompteBancaire
    {
        return $this->dao->findByRib($rib);
    }

    public function findById($id): ?CompteBancaire
    {
        return $this->dao->findById($id);
    }

    public function deleteById($id): bool
    {
        return $this->dao->deleteById($id);
    }

    public function findAll(): array
    {
        return $this->dao->findAll();
    }

    public function update(CompteBancaire $compte): bool
    {
        return $this->dao->update($compte);
    }

    public function getTransactionHistory($rib)
    {

    }
}

