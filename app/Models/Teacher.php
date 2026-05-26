<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [

        'employee_number',
        'fullname',
        'email',
        'course_id',
        'position',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function user()
    {
    return $this->belongsTo(User::class);
    }
    public function assignments()
{
    return $this->hasMany(ClassAssignment::class);
}
}