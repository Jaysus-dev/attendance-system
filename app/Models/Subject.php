<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Subject extends Model
{
    protected $fillable = [
        'subject_code',
        'subject_name',
        'teacher_id',
        'course_id',
    ];

    public function teacher()
    {
    return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function students()

{
    return $this->belongsToMany(Student::class, 'student_subject');
}

public function classes()
{
    return $this->hasMany(ClassAssignment::class);
}
}
