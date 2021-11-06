<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vcard extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = 'phone_number';

    protected $fillable = [
        'phone_number',
        'name',
        'email',
        'photo_url',
        'password',
        'confirmation_code',
        'blocked',
        'balance',
        'max_debit',
        'custom_options',
        'custom_data',
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class,'vcard');
    }



    public function pair_transactions()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'vcard');
    }




}

