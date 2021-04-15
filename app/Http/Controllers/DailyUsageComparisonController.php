<?php

namespace App\Http\Controllers;

use App\Models\Stats\MeterUsage;
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

        $daylightSeconds1 = $this->getDaylightAmountsForDateRange($dates['start1'], $dates['end1'],
            $request->input('lat'), $request->input('lon'));
        $daylightSeconds2 = $this->getDaylightAmountsForDateRange($dates['start2'], $dates['end2'],
            $request->input('lat'), $request->input('lon'));
        try {
            $daylightMean1 = $this->formattedStringFromSeconds(Average::mean($daylightSeconds1));
        } catch (BadDataException $e) {
            $daylightMean1 = "?";
            $errors[] = "Exception thrown for median daylight: " . $e->getMessage();
        }
        try {
            $daylightMean2 = $this->formattedStringFromSeconds(Average::mean($daylightSeconds2));
        } catch (BadDataException $e) {
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
