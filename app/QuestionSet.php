<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QuestionSetQuestion;
class QuestionSet extends Model
{
    protected $fillable = [
        'name','org_id','created_by','status'
    ];
    public function question_set_question(){
        return $this->hasMany('App\QuestionSetQuestion','question_set_id','id');
    }
}
