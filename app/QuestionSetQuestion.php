<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;
class QuestionSetQuestion extends Model
{
    protected $fillable = [
        'question_id','question_set_id','status'
    ];
    public function questions(){
        return $this->belongsTo('App\Question','question_id','id');
    }
}
