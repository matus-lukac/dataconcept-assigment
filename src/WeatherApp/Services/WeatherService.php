<?php

namespace DataConceptAssignment\WeatherApp\Services;

use GuzzleHttp\Client;
use DataConceptAssignment\WeatherApp\Models\WeatherData;

class WeatherService
{
    private $client;
    private $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function getWeatherData(string $city, string $date): array
    {
        $url = "http://api.openweathermap.org/data/2.5/forecast";
        
        try {
            $response = $this->client->request('GET', $url, [
                'query' => [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'sk'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['list'])) {
                throw new \Exception("Nepodarilo sa získať údaje o počasí.");
            }

            $weatherDataArray = [];
            foreach ($data['list'] as $forecast) {
                $forecastDate = substr($forecast['dt_txt'], 0, 10);
                if ($forecastDate === $date) {
                    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $forecast['dt_txt']);
                    $formattedDate = $dateTime->format('d-m-Y H:i');
                    
                    $weatherDataArray[] = new WeatherData(
                        $city,
                        $formattedDate,
                        $forecast['main']['temp'],
                        $forecast['weather'][0]['description']
                    );
                }
            }

            if (empty($weatherDataArray)) {
                throw new \Exception("Pre zadaný dátum nie sú k dispozícii žiadne údaje o počasí.");
            }

            return $weatherDataArray;
        } catch (\Exception $e) {
            throw new \Exception("Chyba pri získavaní údajov o počasí: " . $e->getMessage());
        }
    }
}