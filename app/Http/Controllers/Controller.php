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

    protected function getAllDaylightAmounts(array $dates, string $lat, string $lon): array
    {
        $result = [
            'daylight1' => [],
            'daylight2' => []
        ];

        /*
         * Set times to noon to make absolutely sure DST changes
         * don't result in misinterpretations that break things
         * */
        try {
            $start1 = new DateTime($dates['start1'] . 'T12:00:00');
            $end1 = new DateTime($dates['end1'] . 'T12:00:00');

            while ($start1 <= $end1) {
                $result['daylight1'][] = $this->getDaylightAmount($start1, $lat, $lon);
                $start1->modify('+1 day');
            }
        } catch (Exception) {
        }

        try {
            $start2 = new DateTime($dates['start2'] . 'T12:00:00');
            $end2 = new DateTime($dates['end2'] . 'T12:00:00');

            while ($start2 <= $end2) {
                $result['daylight2'][] = $this->getDaylightAmount($start2, $lat, $lon);
                $start2->modify('+1 day');
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
