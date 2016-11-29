<?php
require_once ('class.Media.php');
require_once ('class.Statut.php');

class Suivi extends Statut {
    private $date = 0;
    private $commentaire = null;
    
    private $lesMedias = array();
}