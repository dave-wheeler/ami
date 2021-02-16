<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterUsage extends Model
{
    use HasFactory;

    protected $fillable = ['meter', 'ts', 'uom', 'usage', 'peak'];
    public $timestamps = false;
}
