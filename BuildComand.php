<?php


namespace App;

use App\Queries;

class BuildComand
{
    public $atributes = array(
        'lineNumber' => 'LineNumber',
        'distance' =>   'HighResolutionTotalVehicleDistance',
        'totalFuel' => 'HighResolutionEngineTotalFuelUsed',
        'variant' => 'Variant',
    );

    public $command = '';
    public $vehicleId = '';
    public $date = '';

    public function __construct($vehicleId, $date)
    {
        $command = '(java -jar /var/www/candy_fleet/com.thoreb.candy-decoder.jar --host=ro-ratb.thoreb.com csv-dump ' . $vehicleId . '_' . date("Ymd",
                strtotime($date));

        foreach ($this->atributes as $name => $atribute) {
            $command .= ' ' . $atribute;
        }
        $command .= ')2>&1';
        $this->command = $command;
        $this->vehicleId = $vehicleId;
        $this->date = $date;
    }


    public function getResults ()
    {

        $process = popen($this->command, "r");

        $queries = NEW \App\Queries();
        $kmLast = $queries->get_last_value($this->date, $this->vehicleId);

        $results = array();
        $variant = '';

        while (!feof($process)) {

            $line = str_replace(PHP_EOL, '', fgets($process));
            $lineArray = explode("\t", $line);

            if ($lineArray[0] != 'Timestamp' && trim($lineArray[0]) != 'Invalid base file identifier' && count($lineArray) > 1 ) {

                if (trim($lineArray[1])!= '' && trim($lineArray[2])!= '' && $kmLast <= (int) $lineArray[2]) {

                    $kmLast = (int) $lineArray[2];
                    $array = array();
                    $array['Time'] = $lineArray[0];
                    $i = 1;
                    foreach ($this->atributes as $key => $value) {
                        $array[$key] = $lineArray[$i];
                        $i++;
                    }
                    if ($lineArray[4] != ''){
                        $variant = $lineArray[4];
                    }

                    $array['variant'] = $variant;
                    $results[] = $array;

                } elseif (trim($lineArray[2])!= '' &&  $kmLast < (int) $lineArray[2]) {

                    $kmLast = (int) $lineArray[2];
                    $array = array();
                    $array['Time'] = $lineArray[0];
                    $i = 1;
                    foreach ( $this->atributes as $key => $value ){
                        $array[$key] = $lineArray[$i];
                        $i++;
                    }
                    $array['lineNumber'] = (isset($results[count($results)-1]['lineNumber']) && trim($results[count($results)-1]['lineNumber'])!='' ? $results[count($results)-1]['lineNumber'] : 0) ;
                    if ($lineArray[4] != ''){
                        $variant = $lineArray[4];
                    }

                    $array['variant'] = $variant;
                    $results[] = $array;
                }
            }
        }

        return $results;
    }

}