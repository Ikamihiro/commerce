<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEntry extends Model
{
    protected $fillable = ['payload', 'provider'];

    protected $casts = [
        'payload' => 'array',
    ];
}
