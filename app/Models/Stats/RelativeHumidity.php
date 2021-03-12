<?php

namespace App\Models\Stats;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stats\RelativeHumidity
 *
 * @property int $id
 * @property string $station
 * @property string $ts
 * @property string $uom
 * @property int $observed
 * @method static Builder|RelativeHumidity newModelQuery()
 * @method static Builder|RelativeHumidity newQuery()
 * @method static Builder|RelativeHumidity query()
 * @method static Builder|RelativeHumidity whereId($value)
 * @method static Builder|RelativeHumidity whereObserved($value)
 * @method static Builder|RelativeHumidity whereStation($value)
 * @method static Builder|RelativeHumidity whereTs($value)
 * @method static Builder|RelativeHumidity whereUom($value)
 * @mixin Eloquent
 */
class RelativeHumidity extends Model
{
    use HasFactory;

    protected $fillable = ['station', 'ts', 'uom', 'observed'];
    public $timestamps = false;
}
