<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelativeHumidity extends Model
{
    use HasFactory;

    protected $fillable = ['station', 'ts', 'uom', 'observed'];
    public $timestamps = false;
}
