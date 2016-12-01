<?php

require_once ('../classes/class.Personne.php');

class DaoPersonne {
    public $bean = null;
    public $pdo = null;

    public function DaoPersonne(){
        $this->bean = new Profil();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM personne WHERE ID_PERSONNE = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_PERSONNE']);
                $this->bean->setNom($donnees['NOM']);
                $this->bean->setPrenom($donnees['PRENOM']);
                $this->bean->setMail($donnees['MAIL']);
                $this->bean->setUser($donnees['USER']);
                $this->bean->setAdresse($donnees['ADRESSE']);
                $this->bean->setPhoto($donnees['PHOTO']);
                $this->bean->setStatut($donnees['STATUT']);
            }
        }else {
            echo "Erreur requete DaoPersonne->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM personne ORDER BY NOM";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
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
                $liste[] = $personne;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO personne(
        NOM, PRENOM, MAIL, USER, ADRESSE, PHOTO, STATUT)
        VALUES(? , ?, ?, ?, ?, ?, ?)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getNom() );
        $requete->bindValue(2, $this->bean->getPrenom() );
        $requete->bindValue(3, $this->bean->getMail() );
        $requete->bindValue(4, $this->bean->getUser() );
        $requete->bindValue(5, $this->bean->getAdresse() );
        $requete->bindValue(6, $this->bean->getPhoto() );
        $requete->bindValue(7, $this->bean->getStatut() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE personne SET NOM = ? WHERE ID_PERSONNE = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getNom());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "personne",
            "ID_PERSONNE",
            $this->bean->getId()
        );
    }

    public function setLeProfil (){
        $query = "SELECT * FROM profil WHERE profil.ID_PROFIL = 
                  personne.ID_PROFIL AND personne.ID_PERSONNE = ".$this->bean->getId()." ORDER BY LIB_PROFIL";
        $requete = $this->pdo->prepare($query);

        if ($requete->execute()){
            $profil = new Profil();
            if ($donnees = $requete->fetch()){
                $profil->setId($donnees['ID_PROFIL']);
                $profil->setLibProfil($donnees['LIB_PROFIL']);
                $profil->setDroit($donnees['DROIT']);
            }
            $this->bean->setLeProfil($profil);
        }

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