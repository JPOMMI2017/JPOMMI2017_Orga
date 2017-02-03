<?php

require_once ('../classes/class.Suivi.php');

class DaoSuivi {
    public $bean = null;
    public $pdo = null;

    public function DaoSuivi(){
        $this->bean = new Suivi();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM suivi WHERE suivi.ID_STATUT = statut.ID_STATUT AND statut.ID_STATUT = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_STATUT']);
                $this->bean->setDate($donnees['DATE_SUIVI']);
                $this->bean->setCommentaire($donnees['COMMENTAIRE']);
            }
        }else {
            echo "Erreur requete DaoSuivi->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM suivi ORDER BY DATE_SUIVI";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $suivi = new Suivi(
                    $donnees['DATE_SUIVI'],
                    $donnees['COMMENTAIRE']
                );
                $liste[] = $suivi;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO suivi(
        DATE_SUIVI, COMMENTAIRE)
        VALUES(?, ?)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getDate() );
        $requete->bindValue(2, $this->bean->getCommentaire() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE suivi SET COMMENTAIRE = ? WHERE ID_STATUT = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getCommentaire());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "suivi",
            "ID_STATUT",
            $this->bean->getId()
        );
    }

    public function setLesMedias(){
        $query = "SELECT * FROM media  ORDER BY NOM_MEDIA";
        $requete = $this->pdo->prepare($query);

        $listeMedia = array();
        if ($requete->execute()){

            while ($donnees = $requete->fetch()){

                $media = new Media(
                    $donnees['ID_MEDIA'],
                    $donnees['NOM_MEDIA'],
                    $donnees['DESC_MEDIA'],
                    $donnees['URL'],
                    $donnees['DATE_PUB']
                );
            }
        }
        $this->bean->setLesMedias($listeMedia);
    }
}