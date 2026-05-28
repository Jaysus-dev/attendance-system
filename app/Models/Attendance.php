<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    
    protected $fillable = [
        'student_id',
        'teacher_id',
        'class_assignment_id',
        'status',
        'date',
        'marked_at',
    ];


    public function student()
{
    return $this->belongsTo(Student::class);
}

    public function teacher()
{
    return $this->belongsTo(User::class);
}
public function classAssignment()
{
    return $this->belongsTo(ClassAssignment::class);
}
}
