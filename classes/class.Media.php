<?php
require_once ('class.Personne.php');
require_once ('class.Motclef.php');
require_once ('class.Theme.php');
require_once ('class.Suivi.php');

class Media{
    private $id = 0;
    private $nom = null;
    private $desc = null;
    private $url = null;
    private $date = 0;

    private $laPersonne = null;
    private $lesMotsClefs = array();
    private $lesThemes = array();
    private $lesSuivisDuStatut = array();
}