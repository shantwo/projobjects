<?php

// Autoload PSR-4
spl_autoload_register();

// Imports
use \Classes\Webforce3\Config\Config;
use Classes\Webforce3\DB\Location;
use Classes\Webforce3\Helpers\SelectHelper;
use Classes\Webforce3\DB\Country;

// Get the config object
$conf = Config::getInstance();
$locationId = isset($_GET['loc_name']) ? intval($_GET['loc_name']) : 0;

if ($locationId > 0) {
    $locationObject = Location::get($locationId);
    $countryObject = $locationObject->getCountry();
}


// Récupère la liste complète des cities en DB
$locationList = Location::getAllForSelect();

// Instancie le générateur de menu déroulant pour la liste des location
$selectLocation = new SelectHelper($locationList, $locationId, array(
    'name' => 'loc_name',
    'id' => 'loc_id',
    'class' => 'form-control',
));

// Récupère la liste complète des countries en DB
$countriesList = Country::getAllForSelect();

// Instancie le générateur de menu déroulant pour la liste des pays
$selectCountries = new SelectHelper($countriesList, $locationObject->getCountry()->getId(), array(
    'name' => 'cou_id',
    'id' => 'cou_id',
    'class' => 'form-control',
));

// Formulaire soumis
if(!empty($_POST)) {
    print_r($_POST);
    $locationObject = new Location($_POST['loc_name']);
}
// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'location.php';
require $conf->getViewsDir().'footer.php';