<?php

namespace App\Models\Stats;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stats\DailyPrecipitation
 *
 * @property int $id
 * @property string $station
 * @property string $ts
 * @property string $uom
 * @property float $observed
 * @method static Builder|DailyPrecipitation newModelQuery()
 * @method static Builder|DailyPrecipitation newQuery()
 * @method static Builder|DailyPrecipitation query()
 * @method static Builder|DailyPrecipitation whereId($value)
 * @method static Builder|DailyPrecipitation whereObserved($value)
 * @method static Builder|DailyPrecipitation whereStation($value)
 * @method static Builder|DailyPrecipitation whereTs($value)
 * @method static Builder|DailyPrecipitation whereUom($value)
 * @mixin Eloquent
 */
class DailyPrecipitation extends Model
{
    use HasFactory;

    protected $fillable = ['station', 'ts', 'uom', 'observed'];
    public $timestamps = false;
}
