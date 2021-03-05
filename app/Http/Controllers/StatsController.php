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
        $startDateTime = "{$request->input('startDate')} {$request->input('startTime')}";
        $endDateTime = "{$request->input('endDate')} {$request->input('endTime')}";
        $stats = [];

        $classes = [
            MeterUsage::class,
            Temperature::class,
            WindSpeed::class,
            DailyPrecipitation::class,
            RelativeHumidity::class
        ];
        foreach ($classes as $className) {
            $column = ($className == MeterUsage::class) ? 'usage' : 'observed';
            $data = $className::whereBetween('ts', [$startDateTime, $endDateTime])
                ->get($column)
                ->pluck($column)->all();
            $unitOfMeasurement = $className::whereBetween('ts', [$startDateTime, $endDateTime])
                ->get('uom')
                ->first()
                ->uom;

            $label = trim(preg_replace('/([A-Z])/', ' $1', class_basename($className))) . " ($unitOfMeasurement)";
            try {
                $stats[$label] = Descriptive::describe($data, true);
            } catch (BadDataException | OutOfBoundsException $e) {
                $error = $e->getMessage();
                return view('stats.show', compact('startDateTime', 'endDateTime', 'error'));
            }
        }

        $results = compact('startDateTime', 'endDateTime', 'stats');
        //dump($results);
        return view('stats.show', $results);
    }
}
