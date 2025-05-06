<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'color',
        'buy_button_text',
        'students_number',
        'purchase_released',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function modules()
    {
        return $this
            ->belongsToMany(Module::class)
            ->withPivot('order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    protected function descriptionCollapsed(): Attribute
    {
        return Attribute::make(
            get: fn() => strlen($this->description) >= 80
                ? substr($this->description, 0, 80) . "..."
                : $this->description
        );
    }

    protected function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cover
                ? Storage::url($this->cover)
                : null
        );
    }

    protected function totalClassHours(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->modules->sum('class_hours')
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->price, 2, ',', '.')
        );
    }

    protected function totalClassesNumber(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->modules->sum('classes_number')
        );
    }
}
