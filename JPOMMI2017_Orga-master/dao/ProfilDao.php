<?php

require_once ('../classes/class.Profil.php');

class DaoProfil {
    public $bean = null;
    public $pdo = null;

    public function DaoProfil(){
        $this->bean = new Profil();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM profil WHERE ID_PROFIL = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_PROFIL']);
                $this->bean->setLib($donnees['LIB_PROFIL']);
                $this->bean->setDroit($donnees['DROIT']);
            }
        }else {
            echo "Erreur requete DaoProfil->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM profil ORDER BY LIB_PROFIL";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $profil = new Profil(
                    $donnees['ID_PROFIL'],
                    $donnees['LIB_PROFIL'],
                    $donnees['DROIT']
                );
                $liste[] = $profil;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO profil(
        LIB_PROFIL, DROIT)
        VALUES(? , ?, ?,)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getLibProfil() );
        $requete->bindValue(2, $this->bean->getDroit() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE profil SET LIB_PROFIL = ? WHERE ID_PROFIL = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getLibProfil());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "profil",
            "ID_PROFIL",
            $this->bean->getId()
        );
    }

    public function setLesPersonnes (){
        $query = "SELECT * FROM personne WHERE personne.ID_PROFIL = 
                  profil.ID_PROFIL and profil.ID_PROFIL = ".$this->bean->getId()."ORDER BY NOM";
        $requete = $this->pdo->prepare($query);

        $listePersonne = array();
        if ($requete->execute()){

            while ($donnees = $requete->fetch()){

                $personne = new Personne(
                    $donnees['ID_PERSONNE'],
                    $donnees['NOM'],
                    $donnees['PRENOM'],
                    $donnees['MAIL'],
                    $donnees['USER'],
                    $donnees['ADRESSE'],
                    $donnees['PHOTO'],
                    $donnees['STATUT']
                );
            }
        }
        $this->bean->setLesPersonnes($listePersonne);
    }
}