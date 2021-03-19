<?php

namespace App\Http\Controllers;

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
}
