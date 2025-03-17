<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cover',
        'video_url',
        'external_id',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function modules()
    {
        return $this
            ->belongsToMany(Module::class)
            ->withPivot('order')
            ->withTimestamps();
    }
}
