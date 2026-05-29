<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'type',
        'reference_id',
        'full_name',
        'email',
        'phone_number',
        'message',
        'status',
    ];
}
