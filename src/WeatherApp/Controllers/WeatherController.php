<?php

namespace DataConceptAssignment\WeatherApp\Controllers;

use DataConceptAssignment\WeatherApp\Services\WeatherService;
use DataConceptAssignment\WeatherApp\Services\ExcelService;
use GuzzleHttp\Client;

class WeatherController
{
    private $weatherService;
    private $excelService;

    public function __construct()
    {
        $client = new Client();
        $this->weatherService = new WeatherService($client, 'fa71460b38c7460e4e2223a3b75bc738'); //tento API kluc samozrejme pre PROD verziu by sa nacitaval od inokade :)
        $this->excelService = new ExcelService();
    }

    public function handleRequest($city, $date)
    {
        if (empty($city) || empty($date)) {
            throw new \Exception("Mesto a dátum sú povinné údaje.");
        }

        $currentDate = new \DateTime();
        $requestDate = new \DateTime($date);
        $dateDiff = $currentDate->diff($requestDate)->days;

        if ($requestDate < $currentDate || $dateDiff > 5) {
            throw new \Exception("Neplatný dátum. Prosím, vyberte dátum v rozmedzí nasledujúcich 5 dní.");
        }

        $weatherData = $this->weatherService->getWeatherData($city, $date);
        $excelFile = $this->excelService->generateExcel($weatherData);

        return [
            //'success' => true,
            'message' => 'Vaše údaje pre počasie sú pripravené',
            'downloadLink' => $excelFile
        ];
    }
}