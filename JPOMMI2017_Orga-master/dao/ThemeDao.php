<?php

require_once ('../classes/class.Theme.php');

class DaoTheme {
    public $bean = null;
    public $pdo = null;

    public function DaoTheme(){
        $this->bean = new Theme();
        $parametres = parse_ini_file("../param/param.ini");

        $this->pdo = new PDO(
            $parametres['dsn'],
            $parametres['user'],
            $parametres['psw']
        );
    }

    public function find($id){
        $query = "SELECT * FROM theme WHERE ID_THEME = ".$id;
        $requete = $this->pdo->prepare($query);

        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $this->bean->setId($donnees['ID_THEME']);
                $this->bean->setLib($donnees['LIB_THEME']);
                $this->bean->setDesc($donnees['DESC_THEME']);
            }
        }else {
            echo "Erreur requete DaoTheme->find()<br/>";
        }
    }

    public function getListe(){
        $query = "SELECT * FROM theme ORDER BY LIB_THEME";
        $requete = $this->pdo->prepare($query);

        $liste = array();
        if($requete->execute()){
            while($donnees = $requete->fetch()){
                $theme = new Theme(
                    $donnees['ID_THEME'],
                    $donnees['LIB_THEME'],
                    $donnees['DESC_THEME']
                );
                $liste[] = $theme;
            }
        }
        return $liste;
    }

    public function create(){
        $sql ="
        INSERT INTO theme(
        LIB_THEME, DESC_THEME)
        VALUES(? , ?)";

        $requete = $this->pdo->prepare($sql);

        $requete->bindValue(1, $this->bean->getLib() );
        $requete->bindValue(2, $this->bean->getDesc() );

        $requete->execute();
    }

    public function update(){
        $sql = "UPDATE theme SET LIB_THEME = ? WHERE ID_THEME = ?";

        $requete = $this->pdo->prepare($sql);
        $requete->bindValue(1, $this->bean->getLib());
        $requete->bindValue(2, $this->bean->getId());
        $requete->execute();
    }

    public function delete(){
        $this->deleteById(
            "theme",
            "ID_THEME",
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