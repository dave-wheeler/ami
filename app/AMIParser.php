<?php


namespace App;

use App\Models\DailyPrecipitation;
use App\Models\MeterUsage;
use App\Models\RelativeHumidity;
use App\Models\Temperature;
use App\Models\WindSpeed;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\ArrayShape;

class AMIParser
{
    #[ArrayShape(['type' => "string", 'parsed' => "int", 'saved' => "int", 'discrepancies' => "array"])]
    private function parseMeterUsage($amiData, $inputParameters): array
    {
        $meterId = $amiData->meterId;
        $meterUOM = $amiData->uom;
        $meterTotals = $amiData->meterUsageDataList[0]->members[0];
        $meterTotal = $meterTotals->total;
        $subTotals = [];
        foreach ($meterTotals->subTotals as $subTotal) {
            $subTotals[$subTotal->detail->datasetTitle] = $subTotal->value;
        }

        $totalParsed = $totalSaved = 0;
        foreach ($amiData->resultsInfo as $i => $resultInfo) {
            $peak = ($resultInfo->datasetTitle == "On-peak");
            foreach ($amiData->resultsTiered[0][$i] as $result) {
                if ($result[1] != 0.0) {
                    if ($inputParameters['DatasetType'] != 'Weather') {
                        $meterUsage = new MeterUsage([
                            'meter' => $meterId,
                            //'ts'    => $result[0],
                            'ts'    => Carbon::createFromFormat('Y-m-d H:i', $result[0],
                                config('app.timezone'))->setTimezone('UTC'),
                            'uom'   => $meterUOM,
                            'usage' => $result[1],
                            'peak'  => $peak
                        ]);
                        $meterUsage->save();
                        $totalSaved++;
                    }
                    $meterTotal -= $result[1];
                    $subTotals[($resultInfo->datasetTitle)] -= $result[1];
                }
                $totalParsed++;
            }
        }

        return [
            'type'          => 'Meter Usage',
            'parsed'        => $totalParsed,
            'saved'         => $totalSaved,
            'discrepancies' => [
                'onPeakSubTotal'  => round($subTotals['On-peak'], 4),
                'offPeakSubTotal' => round($subTotals['Off-peak'], 4),
                'total'           => round($meterTotal, 4)
            ],
        ];
    }

    #[ArrayShape(['type' => "mixed", 'parsed' => "int", 'saved' => "int"])]
    private function parseWeatherData($amiData, $inputParameters): array
    {
        $weatherType = match ($inputParameters['WeatherDataType']) {
            "Daily Precipitation" => DailyPrecipitation::class,
            "Relative Humidity"   => RelativeHumidity::class,
            "Temperature"         => Temperature::class,
            "Wind Speed"          => WindSpeed::class,
            default               => false,
        };

        $totalSaved = 0;
        if ($weatherType) {
            foreach ($amiData->resultsWeather as $result) {
                $weather = new $weatherType([
                    'station'  => $inputParameters['WeatherStation'],
                    'ts'       => $result[0],
                    'uom'      => $inputParameters['WeatherDataUOM'],
                    'observed' => $result[1]
                ]);
                $weather->save();
                $totalSaved++;
            }
        }

        return [
            'type'   => $inputParameters['WeatherDataType'],
            'parsed' => $totalSaved,
            'saved'  => $totalSaved
        ];
    }

    /**
     * Parse AMI data from file content
     *
     * @param string $fileContent
     * @return array
     */
    public function parseFile(string $fileContent): array
    {
        $amiData = json_decode($fileContent);

        $inputParameters = [];
        foreach ($amiData->inputParameters as $inputParameter) {
            $inputParameters[$inputParameter->paramId] = $inputParameter->value;
        }

        if ($inputParameters['DatasetType'] == 'Weather') {
            return ($this->parseWeatherData($amiData, $inputParameters));
        } else {
            return ($this->parseMeterUsage($amiData, $inputParameters));
        }
    }
}
