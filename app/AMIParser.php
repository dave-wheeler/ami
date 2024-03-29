<?php

declare(strict_types=1);

namespace App;

use App\Models\Stats\DailyPrecipitation;
use App\Models\Stats\MeterUsage;
use App\Models\Stats\RelativeHumidity;
use App\Models\Stats\Temperature;
use App\Models\Stats\WindSpeed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JsonException;
use StdClass;
use Symfony\Component\Console\Helper\ProgressBar;

class AMIParser
{
    private StdClass $amiData;
    private array $inputParameters;
    private ?ProgressBar $bar;

    public function __construct()
    {
        ini_set('memory_limit', '1G');
    }

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

        $meterUsages = [];
        foreach ($this->amiData->resultsInfo as $i => $resultInfo) {
            $peak = ($resultInfo->datasetTitle == "On-peak");
            $previousTimestamp = null;
            foreach ($this->amiData->resultsTiered[0][$i] as $result) {
                if ($result[1] != 0.0) {
                    if ($this->inputParameters['DatasetType'] != 'Weather') {
                        // Create a timestamp, converted from app.timezone to UTC
                        $timestamp = Carbon::createFromFormat('Y-m-d H:i', $result[0], config('app.timezone'))
                            ->setTimezone('UTC');
                        if ($timestamp == $previousTimestamp) {
                            // I.R.E.A. duplicates timestamps when clocks get set back an hour for the end
                            // of DST, i.e. one for daylight savings time, and a second for standard time.
                            // If the previous timestamp and this one are exactly the same, then advance
                            // the time by one hour, so this one becomes the standard time version.
                            $timestamp->add('hour', 1);
                        }
                        $previousTimestamp = $timestamp;

                        $meterUsages[] = [
                            'meter' => $meterId,
                            'ts'    => $timestamp,
                            'uom'   => $meterUOM,
                            'usage' => $result[1],
                            'peak'  => $peak
                        ];
                        $totalSaved++;
                    }
                    $meterTotal -= $result[1];
                    $subTotals[($resultInfo->datasetTitle)] -= $result[1];
                }
                $this->bar?->advance();
                $totalParsed++;
            }
        }

        foreach (collect($meterUsages)->chunk(1000) as $chunk) {
            MeterUsage::insert($chunk->toArray());
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
            $weather = new $weatherType();
            $tableName = $weather->getTable();
            $this->bar?->start(count($this->amiData->resultsWeather));

            $weatherResults = [];
            foreach ($this->amiData->resultsWeather as $result) {
                $weatherResults[] = [
                    'station'  => $this->inputParameters['WeatherStation'],
                    'ts'       => $result[0],
                    'uom'      => $this->inputParameters['WeatherDataUOM'],
                    'observed' => $result[1]
                ];
                $this->bar?->advance();
                $totalSaved++;
            }

            foreach (collect($weatherResults)->chunk(1000) as $chunk) {
                DB::table($tableName)->insert($chunk->toArray());
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
     * @throws JsonException
     */
    public function parseFile(string $fileContent, ?ProgressBar $output): array
    {
        $this->amiData = json_decode($fileContent, false, 512, JSON_THROW_ON_ERROR);

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
