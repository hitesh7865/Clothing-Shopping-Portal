<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Question extends Model
{
    //
    protected $table = "questions";
    
    protected $fillable = [
        'org_id', 'question','type','options','positives','negatives','status'
    ];
    // public function category()
    // {
    //     return $this->belongsTo('App\Category', 'cat_id');
    // }
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_question', 'question_id', 'cat_id');
    }
    public function application_activities()
    {
        return $this->hasMany('App\ApplicationActivity', 'question_id', 'id');
    }
    public static function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode("", $ids);
        }
        Question::whereIn('id', $ids)
        ->update(['status'=>-1]);
    }
  
    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = !empty($value) ?json_encode($value) : $value;
    }
    public function setPositivesAttribute($value)
    {
        $this->attributes['positives'] = !empty($value) ?json_encode($value): $value;
    }
    public function setNegativesAttribute($value)
    {
        $this->attributes['negatives'] = !empty($value) ? json_encode($value) : $value;
    }
    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }
    public function getPositivesAttribute($value)
    {
        return json_decode($value);
    }
    public function getNegativesAttribute($value)
    {
        return json_decode($value);
    }
    public static function getUser($questionId)
    {
        $question = Question::where('id', $questionId)->first();
        if ($question && $question->category && $question->category->organization) {
            return User::where('id', $question->category->organization->user_id)->first();
        } else {
            return null;
        }
    }
}
