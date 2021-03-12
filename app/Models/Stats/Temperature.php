<?php

namespace App\Models\Stats;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stats\Temperature
 *
 * @property int $id
 * @property string $station
 * @property string $ts
 * @property string $uom
 * @property float $observed
 * @method static Builder|Temperature newModelQuery()
 * @method static Builder|Temperature newQuery()
 * @method static Builder|Temperature query()
 * @method static Builder|Temperature whereId($value)
 * @method static Builder|Temperature whereObserved($value)
 * @method static Builder|Temperature whereStation($value)
 * @method static Builder|Temperature whereTs($value)
 * @method static Builder|Temperature whereUom($value)
 * @mixin Eloquent
 */
class Temperature extends Model
{
    use HasFactory;

    protected $fillable = ['station', 'ts', 'uom', 'observed'];
    public $timestamps = false;
}
