<?php

ini_set('memory_limit', '1024M');
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set("allow_url_fopen", 1);
error_reporting(-1);
ini_set('max_execution_time', 500);

include_once "config/vehicles.php";
include_once "config/db.php";

require_once('Autoloader.php');
Autoloader::register();

$date = date('Y-m-d');

$dates = array(
    date('Y-m-d', strtotime($date . ' -1 day')),
    date('Y-m-d', strtotime($date . ' -2 day'))
);

$queries = NEW \App\Queries();

foreach ($dates as $date) {
    foreach ($vehicles as $old => $vehicleId) {

        if (!$queries->delete_data_for_date($date, $vehicleId)) echo 'Nu s-au sters datele pentru '.$date.' - '.$vehicleId.'!<br/>';

        $command = NEW \App\BuildComand($vehicleId, $date);
        $array = $command->getResults();

        if (is_array($array) && count($array) > 0) {

            $lines = array();
            $lastCanFuel = 0;

            foreach ($array as $key=>$values) {

                if ( trim($values['variant'])=='' ) $values['variant'] = 0;
                if ( trim($values['totalFuel'])!='' ) $lastCanFuel = $values['totalFuel'];

                //am zis ca fac mereu diferenta pentru fiecare si la sfarsit adun pe linii
                if (!isset($lines[$values['lineNumber']][$values['variant']])){

                    $line = new \App\Lines();

                    $line->setTrafficDate($date);

                    $time = explode(':', $values['Time']);
                    $time = $time[0]*3600+$time[1]*60+$time[2];

                    $line->setTime($time);
                    $line->setLineNumber($values['lineNumber']);
                    $line->setTrafficDate($date);
                    $line->setVehicleNo($vehicleId);
                    $line->setVariant($values['variant']);
                    $line->setCanDistance($values['distance']);
                    $line->setCanFuel($lastCanFuel);
                    $line->setDistance(0);
                    $line->setTotalFuel(0);

                    $lines[$values['lineNumber']][$values['variant']] = $line;

                }else{

                    $time = explode(':', $values['Time']);
                    $time = $time[0]*3600+$time[1]*60+$time[2];

                    if ( $lines[$values['lineNumber']][$values['variant']]->time == ($time-1) ) {

                        $currentObject = $lines[$values['lineNumber']][$values['variant']];

                        $distanceDifference = $values['distance'] - $currentObject->canDistance;
                        $distance = $currentObject->distance+$distanceDifference;

                        if (trim($currentObject->canFuel) == '') $currentObject->setCanFuel($lastCanFuel);
                        $fuelDifference = $values['totalFuel'] - $currentObject->canFuel;
                        $fuel = $currentObject->totalFuel+$fuelDifference;
                        if (trim($values['totalFuel']) == '') $fuel = $currentObject->totalFuel;

                        $currentObject->setDistance($distance);
                        $currentObject->setTotalFuel(round($fuel, 3));
                        $currentObject->setCanDistance($values['distance']);
                        $currentObject->setTime($time);
                        $currentObject->setCanFuel($lastCanFuel);

                    }else{

                        $currentObject = $lines[$values['lineNumber']][$values['variant']];

                        $currentObject->setCanDistance($values['distance']);
                        $currentObject->setTime($time);
                        $currentObject->setCanFuel($lastCanFuel);
                    }
                }
            }
        }
        if (isset($lines) && is_array($lines)) {
            foreach ($lines as $variants) {
                foreach ($variants as $data) {
                    if (!$queries->insert_data($data)) echo 'Nu s-au inserat datele pentru '.$date.' - '.$vehicleId.'!<br/>';
                }
            }
        }
    }
}




