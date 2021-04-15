<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Application;
use App\Enums\ApplicationStatus;

class ApplicationActivity extends Model
{
    //
    protected $fillable = [
        'app_id', 'question_id','answer','status'
    ];
    public function application()
    {
        return $this->hasOne('App\Application', 'app_id');
    }
    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }
    public function setAnswerAttribute($value)
    {
        $this->attributes['answer'] = is_array($value) ? json_encode($value) : $value;
    }
    // public function getAnswerAttribute($value)
    // {
    //     return !empty($value) ? json_decode($value) : $value;
    // }
    public static function addActivtyItems($items)
    {
        $arrRecords = [];
        // Supports saving of Multiple Questions for more than one Job Posting
        foreach ($items as $key => $item) {
            $record = [
              'app_id' => $item['id']
            ];
            foreach ($item['replies'] as $reply) {
                $record['question_id'] = $reply['question_id'];
                $record['answer'] = $reply['answer'];
                $existenceRecord = ApplicationActivity::where('app_id', $item['id'])
                  ->where('question_id', $reply['question_id'])
                  ->first();
                // Do not save duplicates
                if (!$existenceRecord) {
                    ApplicationActivity::create($record);
                    // Update the status of the main email and the reply emails in applications table
                    $appId = Application::where('id', $item['id'])->update(['status' =>  ApplicationStatus::RESPONDED]);
                    Application::where('id', $appId)->update(['status' =>  ApplicationStatus::RESPONDED]);
                }
            }
        }
        return $arrRecords;
    }
}
