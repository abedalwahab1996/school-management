<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_teacher');
    }
}
