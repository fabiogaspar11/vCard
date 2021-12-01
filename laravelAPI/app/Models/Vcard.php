<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vcard extends Model
{
    use HasApiTokens, Notifiable;
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
        return $this->hasMany(Transaction::class,'vcard','phone_number');
    }

    public function pair_transactions()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard', 'phone_number');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'vcard', 'phone_number');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthIdentifierName()
    {
        return $this->name;
    }
}

