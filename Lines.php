<?php


namespace App;


class Lines
{

    private $trafficDate;
    private $lineNumber;
    private $variant;
    private $distance;
    private $totalFuel;
    private $canDistance;
    private $canFuel;

    public function __construct ()
    {
    }

    public function setTrafficDate ($trafficDate)
    {
        $this->trafficDate = $trafficDate;
    }

    public function getTrafficDate ()
    {
        return $this->trafficDate;
    }

    public function setLineNumber ($lineNumber)
    {
        $this->lineNumber = $lineNumber;
    }

    public function getLineNumber ()
    {
        return $this->lineNumber;
    }

    public function setVariant ($variant)
    {
        $this->variant = $variant;
    }

    public function getVariant ()
    {
        return $this->variant;
    }

    public function setDistance ($distance)
    {
        $this->distance = $distance;
    }

    public function getDistance ()
    {
        return $this->distance;
    }

    public function setTotalFuel ($totalFuel)
    {
        $this->totalFuel = $totalFuel;
    }

    public function getTotalFuel ()
    {
        return $this->totalFuel;
    }

    public function setCanDistance ($canDistance)
    {
        $this->canDistance = $canDistance;
    }

    public function getCanDistance ()
    {
        return $this->canDistance;
    }

    public function setCanFuel ($canFuel)
    {
        $this->canFuel = $canFuel;
    }

    public function getCanFuel ()
    {
        return $this->canFuel;
    }

}