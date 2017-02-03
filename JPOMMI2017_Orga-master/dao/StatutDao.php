<?php

require_once ('../classes/class.Statut.php');

class DaoStatut {
    public $bean = null;
    public $pdo = null;

    public function DaoStatut(){
        $this->bean = new Statut();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM statut WHERE ID_STATUT = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_STATUT']);
                $this->bean->setLib($donnees['LIB_STATUT']);
                $this->bean->setPublie($donnees['PUBLIE']);
            }
        }else {
            echo "Erreur requete DaoStatut->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM statut ORDER BY LIB_STATUT";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $statut = new Statut(
                    $donnees['ID_STATUT'],
                    $donnees['LIB_STATUT'],
                    $donnees['PUBLIE']
                );
                $liste[] = $statut;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO statut(
        LIB_STATUT, PUBLIE)
        VALUES(?, ?)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getLib() );
        $requete->bindValue(2, $this->bean->getPublie() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE statut SET LIB_STATUT = ? WHERE ID_STATUT = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getLib());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "statut",
            "ID_STATUT",
            $this->bean->getId()
        );
    }
    
}