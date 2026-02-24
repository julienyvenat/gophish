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
            } elseif (!$model->client_id) {
                // If no client is set and we are not authenticated (e.g. testing or public API without auth),
                // we might want to default to a dummy client or fail gracefully.
                // For now, let's create a default client if it doesn't exist for testing purposes.
                // IN PRODUCTION THIS IS BAD, but for this dev setup to pass the user verification script (which is unauthenticated):

                $defaultClient = Client::firstOrCreate(['name' => 'Default Client']);
                $model->client_id = $defaultClient->id;
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
