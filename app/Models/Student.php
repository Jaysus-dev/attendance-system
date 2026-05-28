<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [

        'user_id',

        'student_number',

        'fullname',

        'email',

        'parent_email',

        'course_id',

        'section_id',

        'year_level',
    ];

    public function course()
{
    return $this->belongsTo(Course::class);
}

    public function section()
{
    return $this->belongsTo(Section::class);
}

    public function attendance()
{
    return $this->hasMany(Attendance::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
