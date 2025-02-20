<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEntry extends Model
{
    protected $fillable = ['payload'];

    protected $casts = [
        'payload' => 'array',
    ];
}
