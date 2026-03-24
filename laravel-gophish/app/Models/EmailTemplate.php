<?php

namespace App\Models;

use App\Models\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];
}
