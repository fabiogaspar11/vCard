<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    public $timestamps = true;
    use SoftDeletes;

    protected $fillable = [
        'vcard',
        'date',
        'datetime',
        'type',
        'value',
        'old_balance',
        'new_balance',
        'payment_type',
        'payment_reference',
        'pair_transaction',
        'pair_vcard',
        'category_id',
        'description',
        'custom_options',
        'custom_data'
    ];

    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard');
    }
    public function pair_vcard()
    {
        return $this->belongsTo(Vcard::class, 'pair_vcard');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function pair_transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
