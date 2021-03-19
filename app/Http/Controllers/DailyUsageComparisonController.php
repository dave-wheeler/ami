<?php

namespace App\Http\Controllers;

use App\Models\Stats\MeterUsage;
//use AurorasLive\SunCalc;
//use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use MathPHP\Exception\OutOfBoundsException;
use MathPHP\Statistics\Significance;

class DailyUsageComparisonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('compare.form');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $dates = $this->extractDatesFromForm($request);
        $tTest = $zTest = $errors = [];

        /*
        $lat = $request->input('lat');
        $lon = $request->input('lon');

        $times = (new SunCalc(new DateTime(), $request->input('lat'), $request->input('lon')))
            ->getSunTimes();
        $daylight = $times['sunrise']->diff($times['sunset']);
        $seconds['day'] = $daylight->h * 3600 + $daylight->i * 60 + $daylight->s;
        $seconds['night'] = 86400 - $seconds['day'];
        */

        $data1 = $this->getDailyUsage($dates['start1'], $dates['end1']);
        $data2 = $this->getDailyUsage($dates['start2'], $dates['end2']);

        if (empty($data1) || empty($data2)) {
            if (empty($data1)) {
                $errors[] = "No data found for {$dates['start1']} to {$dates['end1']}";
            }
            if (empty($data2)) {
                $errors[] = "No data found for {$dates['end1']} to {$dates['end2']}";
            }
        } else {
            try {
                $tTest = Significance::tTestTwoSample($data1, $data2);
                $zTest = Significance::zTestTwoSample($tTest['mean1'], $tTest['mean2'],
                    count($data1), count($data2),
                    $tTest['sd1'], $tTest['sd2']);
            } catch (OutOfBoundsException $e) {
                $errors[] = "Exception thrown for Student's t-test: " . $e->getMessage();
            }
        }

        return view('compare.show', compact('dates', 'tTest', 'zTest', 'errors'));
    }

    protected function getDailyUsage(string $startDate, string $endDate): array
    {
        $tz = config('app.timezone');
        return MeterUsage::selectRaw("DATE(CONVERT_TZ(`ts`, 'UTC', '$tz')) AS `date`, SUM(`usage`) AS `usage`")
            ->groupBy('date')
            ->havingBetween('date', [$startDate, $endDate])
            //->dump()
            ->get()
            ->pluck('usage')
            ->all();
    }
}
