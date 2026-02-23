<?php

namespace App\Models;

use App\Models\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendingProfile extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];

    protected $casts = [
        'ignore_cert_errors' => 'boolean',
        'headers' => 'array',
    ];

    protected $hidden = [
        'password',
    ];
}
