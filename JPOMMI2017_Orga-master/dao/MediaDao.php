<?php

require_once ('../classes/class.Media.php');

class DaoMedia {
    public $bean = null;
    public $pdo = null;

    public function DaoMedia(){
        $this->bean = new Media();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM media WHERE ID_MEDIA = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_MEDIA']);
                $this->bean->setNom($donnees['NOM_MEDIA']);
                $this->bean->setDesc($donnees['DESC_MEDIA']);
                $this->bean->setUrl($donnees['URL']);
                $this->bean->setDatePub($donnees['DATE_PUB']);
            }
        }else {
            echo "Erreur requete DaoMedia->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM media ORDER BY NOM_MEDIA";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $media = new Media(
                    $donnees['ID_MEDIA'],
                    $donnees['NOM_MEDIA'],
                    $donnees['DESC_MEDIA'],
                    $donnees['URL'],
                    $donnees['DATE_PUB']
                );
                $liste[] = $media;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO media(
        NOM_MEDIA, DESC_MEDIA, URL, DATE_PUB)
        VALUES(? , ?, ?, ?)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getNom() );
        $requete->bindValue(2, $this->bean->getDesc() );
        $requete->bindValue(3, $this->bean->getUrl() );
        $requete->bindValue(4, $this->bean->getDatePub() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE media SET NOM_MEDIA = ? WHERE ID_MEDIA = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getNom());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "media",
            "ID_MEDIA",
            $this->bean->getId()
        );
    }

    public function setLeSuiviDuStatut (){
        $query = "SELECT * FROM suivi ORDER BY DATE_SUIVI";
        $requete = $this->pdo->prepare($query);

        if ($requete->execute()){
            $suivi = new Suivi();
            if ($donnees = $requete->fetch()){
                $suivi->setDate($donnees['DATE_SUIVI']);
                $suivi->setCommentaire($donnees['COMMENTAIRE']);
            }
            $this->bean->setLeSuiviDuStatut($suivi);
        }

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

    public function setLesMotsClefs (){
        $query = "SELECT * FROM motclef ORDER BY LIB_MC";
        $requete = $this->pdo->prepare($query);

        $listeMotClef = array();
        if ($requete->execute()){

            while ($donnees = $requete->fetch()){

                $motclef = new Motclef(
                    $donnees['ID_MC'],
                    $donnees['LIB_MC']
                );
            }
        }
        $this->bean->setLesMotsClefs($listeMotClef);
    }

    public function setLesThemes (){
        $query = "SELECT * FROM theme ORDER BY LIB_THEME";
        $requete = $this->pdo->prepare($query);

        $listeTheme = array();
        if ($requete->execute()){

            while ($donnees = $requete->fetch()){

                $theme = new Theme(
                    $donnees['ID_THEME'],
                    $donnees['LIB_THEME'],
                    $donnees['DESC_THEME']
                );
            }
        }
        $this->bean->setLesThemes($listeTheme);
    }
}