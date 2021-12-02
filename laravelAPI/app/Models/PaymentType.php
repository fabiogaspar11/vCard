<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    protected $primaryKey = 'code';
    public $incrementing = false;
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_type', 'code');
    }
}
