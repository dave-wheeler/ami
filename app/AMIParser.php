<?php


namespace App;

use App\Models\DailyPrecipitation;
use App\Models\MeterUsage;
use App\Models\RelativeHumidity;
use App\Models\Temperature;
use App\Models\WindSpeed;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use StdClass;
use Symfony\Component\Console\Helper\ProgressBar;

class AMIParser
{
    private StdClass $amiData;
    private array $inputParameters;
    private ?ProgressBar $bar;

    #[ArrayShape(['type' => "string", 'parsed' => "int", 'saved' => "int", 'discrepancies' => "array"])]
    private function parseMeterUsage(): array
    {
        $meterId = $this->amiData->meterId;
        $meterUOM = $this->amiData->uom;
        $meterTotals = $this->amiData->meterUsageDataList[0]->members[0];
        $meterTotal = $meterTotals->total;
        $subTotals = [];
        foreach ($meterTotals->subTotals as $subTotal) {
            $subTotals[$subTotal->detail->datasetTitle] = $subTotal->value;
        }

        $totalParsed = $totalSaved = $expectedTotal = 0;
        foreach ($this->amiData->resultsInfo as $i => $resultInfo) {
            $expectedTotal += count($this->amiData->resultsTiered[0][$i]);
        }
        $this->bar?->start($expectedTotal);

        foreach ($this->amiData->resultsInfo as $i => $resultInfo) {
            $peak = ($resultInfo->datasetTitle == "On-peak");
            foreach ($this->amiData->resultsTiered[0][$i] as $result) {
                if ($result[1] != 0.0) {
                    if ($this->inputParameters['DatasetType'] != 'Weather') {
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
                $this->bar?->advance();
                $totalParsed++;
            }
        }
        $this->bar?->finish();

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
    private function parseWeatherData(): array
    {
        $weatherType = match ($this->inputParameters['WeatherDataType']) {
            "Daily Precipitation" => DailyPrecipitation::class,
            "Relative Humidity"   => RelativeHumidity::class,
            "Temperature"         => Temperature::class,
            "Wind Speed"          => WindSpeed::class,
            default               => false,
        };

        $totalSaved = 0;
        if ($weatherType) {
            $this->bar?->start(count($this->amiData->resultsWeather));
            foreach ($this->amiData->resultsWeather as $result) {
                $weather = new $weatherType([
                    'station'  => $this->inputParameters['WeatherStation'],
                    'ts'       => $result[0],
                    'uom'      => $this->inputParameters['WeatherDataUOM'],
                    'observed' => $result[1]
                ]);
                $weather->save();
                $this->bar?->advance();
                $totalSaved++;
            }
            $this->bar?->finish();
        }

        return [
            'type'   => $this->inputParameters['WeatherDataType'],
            'parsed' => $totalSaved,
            'saved'  => $totalSaved
        ];
    }

    /**
     * Parse AMI data from file content
     *
     * @param string $fileContent
     * @param ProgressBar|null $output
     * @return array
     */
    public function parseFile(string $fileContent, ?ProgressBar $output): array
    {
        $this->amiData = json_decode($fileContent);

        $this->inputParameters = [];
        foreach ($this->amiData->inputParameters as $inputParameter) {
            $this->inputParameters[$inputParameter->paramId] = $inputParameter->value;
        }

        $this->bar = $output;

        if ($this->inputParameters['DatasetType'] == 'Weather') {
            return ($this->parseWeatherData());
        } else {
            return ($this->parseMeterUsage());
        }
    }
}
