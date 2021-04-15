<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ApplicationStatus;
use App\Enums\QuestionType;

class Application extends Model
{
    protected $table = "applications";
    protected $fillable = [
        'org_id', 'cat_id', 'parent_id','type', 'name','text','attachments','subject','from_email','to_email','message_id','references','in_reply_to','uid','udate','rating','extra_field'
    ];
    protected $casts = [
        'attachments' => 'array',
    ];
    public function application_activities()
    {
        return $this->hasMany('App\ApplicationActivity', 'app_id', 'id');
    }
    public function application_meta()
    {
        return $this->hasMany('App\ApplicationMeta', 'app_id', 'id');
    }
    public function reminders()
    {
        return $this->hasMany('App\Reminder', 'app_id', 'id');
    }
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'org_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Category', 'cat_id');
    }
    // public function setAttachmentsAttribute($value)
    // {
    //     $this->attributes['attachments'] = $value ?json_encode($value) : $value;
    // }
    // public function getAttachmentsAttribute($value)
    // {
    //     return json_decode($value);
    // }
    public function generateRating()
    {
        if (!empty($this->rating)) {
            return;
        }
        $activities = $this->application_activities()->get();
        // dd($activities->toArray());
        $ratingByCorrectness = $this->getRatingByCorrectness($activities);
        $ratingByTotalAttempts = $this->getRatingByTotalAttempts($activities);
        
        $averageRating = ($ratingByCorrectness+$ratingByTotalAttempts)/2;
        $this->update(['rating' => $averageRating]);
        return $averageRating;
    }
    
    
    private function rateForCorrectness($activityModel, $questionModel)
    {
        $question = $questionModel->question;
        $answers = $activityModel->answer;
        $positives = $questionModel->positives;
        $negatives=$questionModel->negatives ;
        $type = $questionModel->type;
        
        switch ($type) {
        case QuestionType::RANGE: // Select box
            if (in_array($answers[0], $positives)) {
                $rating = 4;
            } elseif (in_array($answers[0], $negatives)) {
                $rating = 1;
            } else {
                $rating =1;
            }
            return $rating;
            break;
        case QuestionType::MULTI_OPTION:
            $rating = 0;
            $counter =0;
            foreach ($answers as $answer) {
                if (in_array($answer, $positives)) {
                    $rating = $rating + 4;
                } elseif (in_array($answer, $negatives)) {
                    $rating = $rating + 1;
                }
                $counter++;
            }
            return $rating/$counter;
            
            break;
      }
    }
    private function getRatingByCorrectness($activites)
    {
        $rating = 0;
        $counter = 0;
        foreach ($activites as $activity) {
            $question = $activity->question()->first();
          
            if ($question->type == QuestionType::TEXT || empty($question->positives) || empty($question->negatives)) {
                continue;
            }
            if (empty($activity->answer)) {
                continue;
            }
            $rating  = $rating + $this->rateForCorrectness($activity, $question);
            $counter++;
        }
        
        if ($counter==0) {
            return 1;
        }
        
        return $rating/$counter;
    }
    private function getRatingByTotalAttempts($activities)
    {
        $rating = 0;
        $counter = 0;
        foreach ($activities as $activity) {
            $question = $activity->question()->first();
            if (empty($activity->answer)) {
                $rating =$rating + 1;
            } else {
                $rating =$rating + 4;
            }
            $counter++;
        }
        if ($counter == 0) {
            return 1;
        }
        return $rating/$counter;
    }
    private function getRatingByPunctuality($activities)
    {
    }
    
    public static function addItemsByOrgId($items, $orgId)
    {
        $arrPushes = [];
        foreach ($items as $key => $item) {
            $item['org_id']= $orgId;
            $record = Application::where('message_id', $item['message_id'])->where('udate', $item['udate'])->first();
            if (!$record) {
                // Application::create($item);
                array_push($arrPushes, Application::create($item));
            }
        }
        return $arrPushes;
    }
    public static function getApplicantsByDays($days)
    {
    }
    public static function getAppIdBySubjectAndOrgId($subject)
    {
        if (!isset($subject) || $subject == null || $subject == "") {
            return null;
        }
        $subject = strtoupper($subject);
        $words = (explode(" ", $subject));
        foreach ($words as $word) {
            if (strpos($word, 'YOURHIRED-') !== false) {
                return substr($word, strlen('YOURHIRED-'));
            }
        }
        // Find ID in subject
        // $category=DB::select("select * from categories where ? like CONCAT('%',subject_filter,'%')  and org_id = ? and status=1", [$subject,$orgId]);
        // if (count($category) > 0) {
        //     return $category[0]->id;
        // } else {
        //     null;
        // }
    }
}
