<?php

namespace App\Models;

use App\Models\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];

    protected $casts = [
        'launch_date' => 'datetime',
        'send_by_date' => 'datetime',
        'completed_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function sendingProfile()
    {
        return $this->belongsTo(SendingProfile::class);
    }

    public function results()
    {
        return $this->hasMany(CampaignResult::class);
    }

    public function events()
    {
        return $this->hasMany(CampaignEvent::class);
    }
}
