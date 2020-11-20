<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamProgress extends Model
{
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exam', 'student', 'question_id', 'q_index', 'correct', 'selected', 'maxscore', 'is_locked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}
