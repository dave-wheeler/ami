<?php

namespace App\Models\Stats;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelativeHumidity extends Model
{
    use HasFactory;

    protected $fillable = ['station', 'ts', 'uom', 'observed'];
    public $timestamps = false;
}
