<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'course_id',
        'section_id',
        'subject_id',
    ];
    
    public function teacher()
{
    return $this->belongsTo(Teacher::class);
}

public function course()
{
    return $this->belongsTo(Course::class);
}

public function section()
{
    return $this->belongsTo(Section::class);
}

public function subject()
{
    return $this->belongsTo(Subject::class);
}
}
