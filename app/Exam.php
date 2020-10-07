<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'uploadedby', 'course', 'unit', 'title', 'description', 'maxscore', 'is_deleted', 'is_active',
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
