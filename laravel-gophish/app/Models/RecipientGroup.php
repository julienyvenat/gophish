<?php

namespace App\Models;

use App\Models\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientGroup extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];

    public function recipients()
    {
        return $this->hasMany(Recipient::class);
    }
}
