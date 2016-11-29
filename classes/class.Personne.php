<?php
require_once ('class.Profil.php');
require_once ('class.Media.php');

class Personne {
    private $id = 0;
    private $nom = null;
    private $prenom = null;
    private $mail = null;
    private $user = null;
    private $adresse = null;
    private $photo = null;
    private $statut = 0;

    private $leProfil = null;
    private $lesMedias = array();

}

?>