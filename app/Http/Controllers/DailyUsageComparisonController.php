<?php

namespace App\Http\Controllers;

use App\Models\Stats\MeterUsage;
use AurorasLive\SunCalc;
use DateTime;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use MathPHP\Exception\BadDataException;
use MathPHP\Exception\MathException;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\Descriptive;
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

    private function getDaylightAmount(DateTime $date, string $lat, string $lon): int
    {
        $times = (new SunCalc($date, $lat, $lon))->getSunTimes();

        $daylight = $times['sunrise']->diff($times['sunset']);

        return $daylight->h * 3600 + $daylight->i * 60 + $daylight->s;
    }

    private function getAllDaylightAmounts(array $dates, string $lat, string $lon): array
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
        } catch (Exception) {}

        try {
            $start2 = new DateTime($dates['start2'] . 'T12:00:00');
            $end2 = new DateTime($dates['end2'] . 'T12:00:00');

            while ($start2 <= $end2) {
                $result['daylight2'][] = $this->getDaylightAmount($start2, $lat, $lon);
                $start2->modify('+1 day');
            }
        } catch (Exception) {}

        return $result;
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

        $daylightSeconds = $this->getAllDaylightAmounts($dates, $request->input('lat'), $request->input('lon'));

        try {
            $daylightMean1 = Average::mean($daylightSeconds['daylight1']);
            $daylightMean2 = Average::mean($daylightSeconds['daylight2']);
        } catch (BadDataException $e) {
            $daylightMean1 = "?";
            $daylightMean2 = "?";
            $errors[] = "Exception thrown for median daylight: " . $e->getMessage();
        }

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

                $zTest = Significance::zTestTwoSample(
                    Average::mean($data1),
                    Average::mean($data2),
                    count($data1),
                    count($data2),
                    Descriptive::standardDeviation($data1, true),
                    Descriptive::standardDeviation($data2, true)
                );
            } catch (MathException $e) {
                $errors[] = "Exception thrown for Student's t-test: " . $e->getMessage();
            }
        }

        return view('compare.show', compact('dates', 'daylightMean1', 'daylightMean2', 'tTest', 'zTest', 'errors'));
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
