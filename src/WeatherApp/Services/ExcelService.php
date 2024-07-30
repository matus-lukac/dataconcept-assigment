<?php

namespace DataConceptAssignment\WeatherApp\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DataConceptAssignment\WeatherApp\Models\WeatherData;

class ExcelService
{
    public function generateExcel(array $weatherDataArray): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Mesto');
        $sheet->setCellValue('B1', 'Dátum a čas');
        $sheet->setCellValue('C1', 'Teplota (°C)');
        $sheet->setCellValue('D1', 'Popis počasia');

        $row = 2;
        foreach ($weatherDataArray as $weatherData) {
            $sheet->setCellValue('A' . $row, $weatherData->getCity());
            $sheet->setCellValue('B' . $row, $weatherData->getDateTime());
            $sheet->setCellValue('C' . $row, $weatherData->getTemperature() . '°C');
            $sheet->setCellValue('D' . $row, $weatherData->getDescription());
            $row++; 
        }

        $writer = new Xlsx($spreadsheet);
        
        $filename = 'udaje_o_pocasi_' . time() . '.xlsx';
        $publicPath = realpath(__DIR__ . '/../../../public');
        if ($publicPath === false) {
            throw new \Exception("Nepodarilo sa nájsť cestu k public adresáru.");
        }
        $downloadsPath = $publicPath . '/downloads';
        if (!is_dir($downloadsPath)) {
            if (!mkdir($downloadsPath, 0777, true)) {
                throw new \Exception("Nepodarilo sa vytvoriť adresár pre stiahnutie.");
            }
        }
        $filepath = $downloadsPath . '/' . $filename;
        
        $writer->save($filepath);

        return '/public/downloads/' . $filename;
    }
}