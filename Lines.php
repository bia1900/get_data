<?php


namespace App;


class Lines
{

    public $trafficDate;
    public $lineNumber;
    public $variant;
    public $distance;
    public $totalFuel;
    public $canDistance;
    public $canFuel;
    public $time;
    public $vehicleNo;

    public function setTrafficDate ($trafficDate)
    {
        $this->trafficDate = $trafficDate;
    }

    public function setVehicleNo ($vehicleNo)
    {
        $this->vehicleNo = $vehicleNo;
    }

    public function setLineNumber ($lineNumber)
    {
        $this->lineNumber = $lineNumber;
    }

    public function setVariant ($variant)
    {
        $this->variant = $variant;
    }

    public function setDistance ($distance)
    {
        $this->distance = $distance;
    }

    public function setTotalFuel ($totalFuel)
    {
        $this->totalFuel = $totalFuel;
    }

    public function setCanDistance ($canDistance)
    {
        $this->canDistance = $canDistance;
    }

    public function setCanFuel ($canFuel)
    {
        $this->canFuel = $canFuel;
    }

    public function setTime ($time)
    {
        $this->time = $time;
    }

}