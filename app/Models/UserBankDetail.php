<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method',
        'bank_name',
        'beneficiary_name',
        'account_number',
        'account_type',
        'ifsc_code',
        'branch',
        'crypto_type',
        'crypto_address',
    ];
}
