<?php

declare(strict_types=1);

namespace App\Models\Stats;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stats\MeterUsage
 *
 * @property int $id
 * @property string $meter
 * @property string $ts
 * @property string $uom
 * @property float $usage
 * @property int $peak
 * @method static Builder|MeterUsage newModelQuery()
 * @method static Builder|MeterUsage newQuery()
 * @method static Builder|MeterUsage query()
 * @method static Builder|MeterUsage whereId($value)
 * @method static Builder|MeterUsage whereMeter($value)
 * @method static Builder|MeterUsage wherePeak($value)
 * @method static Builder|MeterUsage whereTs($value)
 * @method static Builder|MeterUsage whereUom($value)
 * @method static Builder|MeterUsage whereUsage($value)
 * @mixin Eloquent
 */
class MeterUsage extends Model
{
    use HasFactory;

    protected $fillable = ['meter', 'ts', 'uom', 'usage', 'peak'];
    public $timestamps = false;
}
