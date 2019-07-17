<?php


namespace App;

use App\Database;
use \PDO;

class Queries
{
    public $conn;

    public function __construct()
    {
        $instance = Database::getInstance();
        $this->conn = $instance->getConnection();
    }

    public function delete_data_for_date($date, $vehicle)
    {
        $query = ' DELETE FROM `candy_fleet`.`vehicle_data` WHERE traffic_date = "' . $date . '" AND vehicle_no = "' . $vehicle . '" ';
        $stmt = $this->conn->query ($query);
        $stmt->execute ();
        if ($stmt->errorCode () != '00000') {
            echo $query;
            return false;
        }

        return true;
    }


    public function get_last_value($date, $vehicle)
    {

        $query = 'SELECT
                  MAX(`total_km`) as total_km
              FROM
                `candy_fleet`.`vehicle_data`
              WHERE `vehicle_data`.`vehicle_no` = :vehicle
                AND `vehicle_data`.`traffic_date` = :date
              ';

        $stmt = $this->conn->prepare ($query);
        $stmt->bindValue (':vehicle', $vehicle, PDO::PARAM_STR);
        $stmt->bindValue (':date', date ('Y-m-d', strtotime ($date . " -1 day")), PDO::PARAM_STR);
        $stmt->execute ();
        if ($stmt->errorCode () != '00000') {
            echo $query;
            return false;
        }

        $res = $stmt->fetchAll (PDO::FETCH_ASSOC);

        return isset($res[0]['total_km']) ? $res[0]['total_km'] : 0;

    }

    public function insert_data($data)
    {
        $query = 'REPLACE INTO `candy_fleet`.`vehicle_data`
                        ( `vehicle_no`,
                        `line_number`,
                        `variant`,
                        `traffic_date`,
                        `total_fuel`,
                        `total_km`,
                        `distance`)
                        VALUES
                        ( "'.$data->vehicleNo.'",
                        "'.$data->lineNumber.'",
                        "'.$data->variant.'",
                        "'.$data->trafficDate.'",
                        "'.$data->totalFuel.'",
                        "'.$data->canDistance.'",
                        "'.$data->distance.'"); 
                        ';

        $stmt = $this->conn->prepare ($query);
        $stmt->execute();

        if ($stmt->errorCode () != '00000') {
            echo $query;
            return false;
        }
        return true;
    }
}