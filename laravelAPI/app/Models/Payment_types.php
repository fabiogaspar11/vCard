<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment_types extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    protected $primaryKey = 'code';

    protected $fillable = [
        'name',
        'description',
        'validation_rules',
        'custom_options',
        'custom_data',
    ];

}
