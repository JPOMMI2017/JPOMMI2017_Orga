<?php

require_once ('../classes/class.Motclef.php');

class DaoMotClef {
    public $bean = null;
    public $pdo = null;

    public function DaoMotclef(){
        $this->bean = new Motclef();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM motclef WHERE ID_MC = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_THEME']);
                $this->bean->setLib($donnees['LIB_MC']);
            }
        }else {
            echo "Erreur requete DaoMotClef->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM motclef ORDER BY LIB_MC";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $motclef = new MotClef(
                    $donnees['ID_THEME'],
                    $donnees['LIB_MC']
                );
                $liste[] = $motclef;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO motclef(
        LIB_MC)
        VALUES(?)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getLib() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE motclef SET LIB_MC = ? WHERE ID_MC = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getLib());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "motclef",
            "ID_MC",
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