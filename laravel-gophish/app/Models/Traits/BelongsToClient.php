<?php

namespace App\Models\Traits;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToClient
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootBelongsToClient()
    {
        static::addGlobalScope('client', function (Builder $builder) {
            if (Auth::check() && Auth::user()->client_id) {
                $builder->where('client_id', Auth::user()->client_id);
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->client_id) {
                $model->client_id = Auth::user()->client_id;
            }
        });
    }

    /**
     * Get the client that owns the model.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
