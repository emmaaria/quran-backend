<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sura extends Model
{
    use HasFactory;

    protected $fillable = ['arabic_name', 'bangla_name', 'serial_no'];
}
