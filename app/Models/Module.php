<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'class_hours',
        'classes_number',
        'details',
    ];

    public function courses()
    {
        return $this
            ->belongsToMany(Course::class)
            ->withPivot('order');
    }

    protected function descriptionCollapsed(): Attribute
    {
        return Attribute::make(
            get: fn() => strlen($this->description) >= 80
                ? substr($this->description, 0, 80) . "..."
                : $this->description
        );
    }
}
