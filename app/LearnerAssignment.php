<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearnerAssignment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'learner', 'assignment', 'submission_file', 'markedby', 'score', 'is_marked', 'is_deleted',
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
