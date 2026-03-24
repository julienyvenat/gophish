<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'send_date' => 'datetime',
        'reported' => 'boolean',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
