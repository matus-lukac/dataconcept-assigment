<?php

namespace DataConceptAssignment\WeatherApp\Models;

class WeatherData
{
    private $city;
    private $dateTime;
    private $temperature;
    private $description;

    public function __construct(string $city, string $dateTime, float $temperature, string $description)
    {
        $this->city = $city;
        $this->dateTime = $dateTime;
        $this->temperature = $temperature;
        $this->description = $description;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}