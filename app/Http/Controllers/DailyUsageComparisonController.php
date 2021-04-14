<?php

namespace App\Http\Controllers;

use App\Models\Stats\MeterUsage;
use AurorasLive\SunCalc;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use MathPHP\Exception\OutOfBoundsException;
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

    private function getDaylightSeconds(DateTime $date, string $lat, string $lon): int
    {
        $times = (new SunCalc($date, $lat, $lon))->getSunTimes();

        $daylight = $times['sunrise']->diff($times['sunset']);

        return $daylight->h * 3600 + $daylight->i * 60 + $daylight->s;
    }

    private function getAllDaylightSeconds(array $dates, string $lat, string $lon): array
    {
        /*
         * Set times to noon to make absolutely sure DST changes
         * don't result in misinterpretations that break things
         * */
        $result = [
            'daylight1' => [],
            'daylight2' => []
        ];

        $start1 = new DateTime($dates['start1'] . 'T12:00:00');
        $end1 = new DateTime($dates['end1'] . 'T12:00:00');
        while ($start1 <= $end1) {
            $result['daylight1'][] = $this->getDaylightSeconds($start1, $lat, $lon);
            $start1->modify('+1 day');
        }

        $start2 = new DateTime($dates['start2'] . 'T12:00:00');
        $end2 = new DateTime($dates['end2'] . 'T12:00:00');
        while ($start2 <= $end2) {
            $result['daylight2'][] = $this->getDaylightSeconds($start2, $lat, $lon);
            $start2->modify('+1 day');
        }

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

        $daylightSeconds = $this->getAllDaylightSeconds($dates, $request->input('lat'), $request->input('lon'));
        $daylightMean1 = Descriptive::describe($daylightSeconds['daylight1'], true)['mean'];
        $daylightMean2 = Descriptive::describe($daylightSeconds['daylight2'], true)['mean'];

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

                $stats1 = Descriptive::describe($data1, true);
                $stats2 = Descriptive::describe($data2, true);
                $zTest = Significance::zTestTwoSample(
                    $stats1['mean'],
                    $stats2['mean'],
                    count($data1),
                    count($data2),
                    $stats1['sd'],
                    $stats2['sd']
                );
            } catch (OutOfBoundsException $e) {
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
