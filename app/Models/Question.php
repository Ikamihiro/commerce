<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title',
        'answer',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    protected function titleCollapsed(): Attribute
    {
        return Attribute::make(
            get: fn() => strlen($this->title) >= 40
                ? substr($this->title, 0, 40) . "..."
                : $this->title,
        );
    }

    protected function answerCollapsed(): Attribute
    {
        return Attribute::make(
            get: fn() => strlen($this->answer) >= 80
                ? substr($this->answer, 0, 80) . "..."
                : $this->answer,
        );
    }
}
