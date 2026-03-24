<?php

namespace App\Models;

use App\Models\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];

    protected $casts = [
        'capture_credentials' => 'boolean',
        'capture_passwords' => 'boolean',
    ];
}
