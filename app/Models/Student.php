<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'name',
        'age',
        'email',
    ];

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'student_teacher');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_subject');
    }
}
