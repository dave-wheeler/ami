<?php

namespace App\Http\Controllers;

use App\Models\Stats\DailyPrecipitation;
use App\Models\Stats\MeterUsage;
use App\Models\Stats\RelativeHumidity;
use App\Models\Stats\Temperature;
use App\Models\Stats\WindSpeed;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use MathPHP\Exception\BadDataException;
use MathPHP\Exception\OutOfBoundsException;
use MathPHP\Statistics\Descriptive;

class StatsController extends Controller
{
    protected static array $classes = [
        MeterUsage::class,
        Temperature::class,
        WindSpeed::class,
        DailyPrecipitation::class,
        RelativeHumidity::class
    ];

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('stats.form');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $dateTimes = $this->extractDateTimesFromForm($request);
        $stats = $errors = [];

        foreach (self::$classes as $className) {
            $column = ($className == MeterUsage::class) ? 'usage' : 'observed';
            $label = trim(preg_replace('/([A-Z])/', ' $1', class_basename($className)));

            $data = $className::whereBetween('ts', [$dateTimes['queryStart'], $dateTimes['queryEnd']])
                ->get($column)
                ->pluck($column)->all();
            if (empty($data)) {
                $errors[] = "No data found for $label";
                continue;
            }

            $unitOfMeasurement = $className::whereBetween('ts', [$dateTimes['queryStart'], $dateTimes['queryEnd']])
                ->get('uom')
                ->first()
                ->uom;
            $label .= " ($unitOfMeasurement)";

            try {
                $stats[$label] = Descriptive::describe($data, true);
            } catch (BadDataException | OutOfBoundsException $e) {
                $errors[] = "Exception thrown for $label: " . $e->getMessage();
                continue;
            }
        }

        $results = compact('dateTimes', 'stats', 'errors');
        //dump($results);
        return view('stats.show', $results);
    }
}
