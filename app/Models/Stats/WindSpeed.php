<?php

namespace App\Models\Stats;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stats\WindSpeed
 *
 * @property int $id
 * @property string $station
 * @property string $ts
 * @property string $uom
 * @property float $observed
 * @method static Builder|WindSpeed newModelQuery()
 * @method static Builder|WindSpeed newQuery()
 * @method static Builder|WindSpeed query()
 * @method static Builder|WindSpeed whereId($value)
 * @method static Builder|WindSpeed whereObserved($value)
 * @method static Builder|WindSpeed whereStation($value)
 * @method static Builder|WindSpeed whereTs($value)
 * @method static Builder|WindSpeed whereUom($value)
 * @mixin Eloquent
 */
class WindSpeed extends Model
{
    use HasFactory;

    protected $fillable = ['station', 'ts', 'uom', 'observed'];
    public $timestamps = false;
}
