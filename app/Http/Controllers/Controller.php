<?php

namespace App\Http\Controllers;

use AurorasLive\SunCalc;
use DateTime;
use Exception;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a datetime (as string) array from request
     *
     * @param Request $request
     * @return string[]
     */
    protected function extractDateTimesFromForm(Request $request): array
    {
        $dateTimes = [];
        $format = 'Y-m-d H:i';

        foreach ($request->keys() as $key) {
            if (Str::is('*Date*', $key)) {
                $timeKey = Str::replaceFirst('Date', 'Time', $key);
                $datetime = "{$request->input($key)} {$request->input($timeKey)}";

                $timeKey = Str::replaceFirst('Date', '', $key);
                $dateTimes[$timeKey] = $datetime;

                $queryKey = "query" . Str::studly($timeKey);
                $dateTimes[$queryKey] = Carbon::createFromFormat($format, $datetime, config('app.timezone'))
                    ->setTimezone('UTC')
                    ->toDateTimeString('minute');
            }
        }

        return $dateTimes;
    }

    /**
     * Return date (as string) array from request
     *
     * @param Request $request
     * @return string[]
     */
    protected function extractDatesFromForm(Request $request): array
    {
        $dates = [];

        foreach ($request->keys() as $key) {
            if (Str::is('*Date*', $key)) {
                $dateKey = Str::replaceFirst('Date', '', $key);
                $dates[$dateKey] = $request->input($key);
            }
        }

        return $dates;
    }

    protected function getDaylightAmount(DateTime $date, string $lat, string $lon): int
    {
        $times = (new SunCalc($date, $lat, $lon))->getSunTimes();

        $daylight = $times['sunrise']->diff($times['sunset']);

        return $daylight->h * 3600 + $daylight->i * 60 + $daylight->s;
    }

    protected function getDaylightAmountsForDateRange(string $startDate, string $endDate, string $lat, string $lon): array
    {
        $result = [];
        try {
            $start = new DateTime($startDate . 'T12:00:00');
            $end = new DateTime($endDate . 'T12:00:00');

            while ($start <= $end) {
                $result[] = $this->getDaylightAmount($start, $lat, $lon);
                $start->modify('+1 day');
            }
        } catch (Exception) {
        }

        return $result;
    }

    protected function formattedStringFromSeconds(float $seconds): string
    {
        $hours = (int)($seconds / 3600);
        $seconds -= 3600 * $hours;
        $minutes = (int)($seconds / 60);
        $seconds -= 60 * $minutes;
        $seconds = round($seconds);
        return "${hours}h ${minutes}m ${seconds}s";
    }
}
