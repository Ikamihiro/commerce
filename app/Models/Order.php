<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'student_id',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected function statusTranslated(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => match ($attributes['status']) {
                'authorized' => 'Autorizado',
                'approved' => 'Aprovado',
                'cancelled' => 'Cancelado',
                'pending' => 'Pendente',
                default => 'Desconhecido',
            },
        );
    }
}
