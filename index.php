<?php

include_once('Twig/Autoloader.php');

Twig_Autoloader::register();



$loader = new Twig_Loader_Filesystem('templates');

$twig = new Twig_Environment($loader, array(!'cache' => 'templates_c'));

$uriDemandee = "index";

$routes = parse_ini_file("param/routes.ini", true);

if (isset($_GET["page"])){
    $uriDemandee = $_GET["page"];
}
$page = $routes[$uriDemandee]["page"];
$template = $routes[$uriDemandee]["template"];

$template = $twig->loadTemplate($template);

echo $template->render(array());

?>