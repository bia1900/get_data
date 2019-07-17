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
    //date('Y-m-d', strtotime($date . ' -2 day'))
);

$queries = NEW \App\Queries();

foreach ($dates as $date) {
    foreach ($vehicles as $old => $vehicleId) {

        $queries->delete_data_for_date($date, $vehicleId);

        $command = NEW \App\BuildComand($vehicleId, $date);
        $array = $command->getResults();

        if (is_array($array) && count($array) > 0) {

            $lines = array();

            foreach ($array as $key=>$values) {
                //am zis ca fac mereu diferenta pentru fiecare si la sfarsit adun pe linii
                if (!isset($lines[$values['lineNumber']])){

                    $line = new \App\Lines();
                    $line->setTrafficDate($date);
                    $line->setLineNumber($values['lineNumber']);
                    $line->setVariant($values['variant']);
                    $line->setCanDistance($values['distance']);
                    $line->setCanFuel($values['totalFuel']);
                    $line->setDistance(0);
                    $line->setTotalFuel(0);

                    $lines[$values['lineNumber']][] = $line;

                }else{



                }

            }
        }
        print_r($lines);exit;
    }
}




