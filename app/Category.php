<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Enums\CategoryStatus;

class Category extends Model
{
    //
    protected $table = "categories";
    // protected $casts = [
    //   'status' => 'boolean',
    // ];
    protected $fillable = [
        'org_id','title','location','subject_filter','email_filter','start_date_time','end_date_time','functional_area','working_hours','work_from_home','number_of_vacancies','salary_currency','minimum_salary','maximum_salary','job_benefits','gender_preference','minimum_age_group','maximum_age_group','education_qualification','experience_range','organisation_name','organisation_email','organisation_description','organisation_additional_contact_details','status','question_set_id','job_category_id','description','created_by','key_skills'
    ];
    public function job_locations() {
        return $this->hasMany('App\JobLocation','job_id','id');
    }
    public function job_tags() {
        return $this->hasMany('App\JobTag','job_id','id');
    }
    public function question_set()
    {
        return $this->belongsTo('App\QuestionSet', 'question_set_id');
    }
    public function job_category()
    {
        return $this->belongsTo('App\JobCategory', 'job_category_id');
    }
    public function functional_area()
    {
        return $this->belongsTo('App\FunctionalArea', 'functional_area');
    }
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'org_id');
    }
    // public function questions()
    // {
    //     return $this->hasMany('App\Question', 'cat_id', 'id');
    // }
    public function mailboxes()
    {
        return $this->belongsToMany('App\Mailbox', 'category_mailbox', 'cat_id', 'mailbox_id')->withTimestamps();
        ;
    }
    public function questions()
    {
        return $this->belongsToMany('App\Question', 'category_question', 'cat_id', 'question_id');
    }
    public function applications()
    {
        return $this->hasMany('App\Application', 'cat_id', 'id');
    }
    public static function getFreeCategoriesByOrg($id)
    {
        return DB::table('categories')
           ->leftJoin('questions', 'categories.id', '=', 'questions.cat_id')
           ->where('categories.org_id', $id)
           ->where('questions.cat_id', null)
           ->where('categories.status', CategoryStatus::ACTIVE)
           ->select('categories.id', 'title', 'filter')->get();
    }
    public static function getCategoryIdBySubjectAndOrgId($subject, $orgId)
    {
        if (!isset($subject) || $subject == null || $subject == "") {
            return null;
        }
        $category=DB::select("select * from categories where ? like CONCAT('%',subject_filter,'%')  and org_id = ? and status= ?", [$subject,$orgId, CategoryStatus::ACTIVE]);
        if (count($category) > 0) {
            return $category[0]->id;
        } else {
            null;
        }
    }
}
