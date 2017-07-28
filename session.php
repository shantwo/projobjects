<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use Classes\Webforce3\DB\Session;
use Classes\Webforce3\DB\Training;
use Classes\Webforce3\DB\Location;
use Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$sessionId = isset($_GET['ses_name']) ? intval($_GET['ses_name']) : 0;
$sessionObject = new Session();
//var_dump($sessionObject);
$trainingObject = new Training();
$locationObject = new Location();

// Récupère la liste complète des cities en DB
$sessionList = Session::getAllForSelect();

// Récupère la liste complète des cities en DB
$locationList = Location::getAllForSelect();

// Récupère la liste complète des cities en DB
$trainingList = Training::getAllForSelect();

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    if (Session::deleteById(intval($_GET['delete']))) {
        header('Location: session.php?success='.urlencode('Suppression effectuée'));
        exit;
    }
}


if ($sessionId > 0) {
    $sessionObject = Session::get($sessionId);
    $trainingObject = $sessionObject->getTraining();
    $locationObject = $sessionObject->getLocation();
}

// Formulaire soumis
if(!empty($_POST)) {
    $sessionId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $sessionStartDate = isset($_POST['ses_start_date']) ? $_POST['ses_start_date'] : '';
    $sessionEndDate = isset($_POST['ses_end_date']) ? $_POST['ses_end_date'] : '';
    $locationId = isset($_POST['loc_name']) ? intval($_POST['loc_name']) : 0;
    $sessionNumber = isset($_POST['ses_number']) ? intval($_POST['ses_number']) : 0;
    $trainingId = isset($_POST['tra_name']) ? intval($_POST['tra_name']) : 0;

    if (strlen($sessionStartDate) < 10) {
        $conf->addError('Start Date non correcte');
    }
    if (strlen($sessionEndDate) < 10) {
        $conf->addError('End Date non correcte');
    }
    if (!array_key_exists($locationId, $locationList)) {
        $conf->addError('location non valide');
    }
    if (!array_key_exists($trainingId, $trainingList)) {
        $conf->addError('training non valide');
    }
    if (empty($sessionStartDate)) {
        $conf->addError('Veuillez renseigner la start date');
    }
    if (empty($sessionEndDate)) {
        $conf->addError('Veuillez renseigner la end date');
    }
    if (empty($sessionNumber)) {
        $conf->addError('Veuillez renseigner le numero de session');
    }

    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
    $sessionObject = new Session(
        $sessionId,
        new Location($locationId),
        new Training($trainingId),
        $sessionStartDate,
        $sessionEndDate,
        $sessionNumber
    );

    // Si tout est ok
    if ($conf->haveError() === false) {
        if ($sessionObject->saveDB()) {
            header('Location: session.php?success='.urlencode('Ajout/Modification effectuée').'&ses_id='.$sessionObject->getId());
            exit;
        }
        else {
            $conf->addError('Erreur dans l\'ajout ou la modification');
        }
    }
}
// Instancie le générateur de menu déroulant pour la liste des villes
$selectTrainings = new SelectHelper($trainingList, $trainingObject->getId(), array(
    'name' => 'tra_name',
    'id' => 'tra_id',
    'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour la liste des villes
$selectSessions = new SelectHelper($sessionList, $sessionId, array(
    'name' => 'ses_name',
    'id' => 'ses_id',
    'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour les location
$selectLocations = new SelectHelper($locationList, $locationObject->getId(), array(
    'name' => 'loc_name',
    'id' => 'loc_id',
    'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'session.php';
require $conf->getViewsDir().'footer.php';