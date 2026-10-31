<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_name',
        'gender',
        'age',
        'dob',
        'enrollment',
        'enrollment_date',
        'dept_id',
        'payment',
        'subject',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'enrollment_date' => 'date',
            'payment' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }
}
