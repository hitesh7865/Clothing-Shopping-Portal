@extends('layouts.default')

@section('title', 'YouRHired : Jobs')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'job jobs')
@section('content')


<section class="page__wrapper-content category">
    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">{{ empty($data[ 'id']) ? 'Add' :'Edit' }} Job</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="/jobs" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>
            <div class="col-md-8 g-no-padding">
                <div class=" g-cleared page__blockpage__block_first">
                    <form method="POST" id="{{  empty($data[ 'id']) ? 'category-form' : 'category-form-update' }}" class="col-md-12 g-no-gutter js-category-form form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        @if(!empty($data[ 'id'])) {{ method_field('PUT') }}

                        @endif
                        @if( !empty($data['id']))
                        <input type="hidden" name="id" value="{{  !empty($data['id']) ? $data[ 'id'] :'' }}" />
                        @endif

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="title" placeholder="Job title*" value="{{ !empty($data[ 'title']) ? $data[ 'title'] :''}}">
                            <span class="float-label">Job title*</span>
                        </div>
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="description" placeholder="Job description*">{{ !empty($data[ 'description']) ? $data[ 'description'] :''}}</textarea>
                            <span class="float-label">Job description*</span>
                        </div>

                        {{-- <div class="form__input-wrapper has-float-label">
                            <select name="created_by" class="jsJobCategory" placeholder="Select the user">
                                @foreach($users as $user)
                                    <option value="{{$user['id']}}">{{$user['fullname']}}</option>
                        @endforeach
                        </select>
                        <span class="float-label">Created By*</span>
                </div> --}}

                @if (Auth::user()->role_id == 1)

                <div class="has-float-label">
                    <select name="created_by" class="custom-select" placeholder="Created By">
                        @foreach($users as $user)
                        <option value="{{$user['id']}}">{{$user['fullname']}} ({{$user['user_name']}})</option>
                        @endforeach
                    </select>
                    <span class="float-label">Created By*</span>
                </div>

                @endif



                <div class="has-float-label">
                    <select name="location" id="country" class="jsUserCountry custom-select">
                        <option> Select Country </option>
                    </select>
                    <span class="float-label">Location</span>
                </div>
                <div class="g-cleared form__input-wrapper has-float-label">
                    <select type="text" name="city" id="city" placeholder="Select city" class="jsUserCity" multiple>
                    </select>
                    <input type="hidden" name="hidden_city[]" id="hidden_city" class="jsHiddenUserCity" value="">
                    <span class="float-label">Preferred Location</span>
                </div>




                {{-- <div class="has-float-label">
                                    <select name="PreferredCountry" id="PreferredCountry " class="jsUserPreferredCountry custom-select">
                                        <option>Select Country </option>
                                    </select>
                                    <span class="float-label">Preferred Location</span>
                                </div>
                                <div class="g-cleared form__input-wrapper has-float-label">
                                    <select type="text" name="PreferredCity" id="PreferredCity" placeholder="Select city" class="jsUserCity jsPreferredCity" multiple>
                                    </select>
                                <input type="hidden" name="hidden_preferred_city[]" id="hidden_preferred_city" class="jsHiddenPreferredCity" value="" >
                                <span class="float-label">Preferred City</span>
                                </div>  --}}


                <div class="has-float-label">
                    <select name="functional_area" class="custom-select" placeholder="Functional Area">
                    <option value="" selected></option>

                    @foreach($functional_area as $farea)
                    <option value="{{$farea['id']}}">{{$farea['name']}}</option>
                    @endforeach
                    </select>
                    <span class="float-label">Functional area</span>
                </div>

                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="subject_filter" placeholder="Subject filter*" value="{{ !empty($data[ 'subject_filter']) ? $data[ 'subject_filter'] : ''}}">
                        <span class="float-label">Subject filter</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="email_filter" placeholder="Email filter" value="{{ !empty($data[ 'email_filter']) ? $data[ 'email_filter'] : ''}}">
                        <span class="float-label">Email filter</span>
                    </div>
                </div>

                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <select type="text" name="job_category_id" placeholder="Job Category" class="jsJobCategory custom-select" >
                            @foreach($job_categories as $job_category)
                            <option value="{{$job_category['id']}}" {{ (!empty($data[ 'job_category_id']) && $data[ 'job_category_id'] == $job_category['id'])  ? "selected='true'" : ""}}>{{$job_category['name']}}</option>
                            @endforeach
                        </select>
                        <span class="float-label">Job Category*</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <select type="text" name="question_set_id" placeholder="Job Question set" class="custom-select" >
                            <option value=""></option>
                            @foreach($question_sets as $question_set)
                            <option value="{{$question_set['id']}}" {{ (!empty($data[ 'question_set_id']) && $question_set['id'] == $data['question_set_id']) ? "selected='true'" : ""}}>{{$question_set['name']}}</option>
                            @endforeach
                        </select>
                        <span class="float-label">Question Set</span>
                    </div>
                </div>
                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <select name="working_hours" placeholder="Job working hour" class="jsJobWorkingHour" multiple>
                            <option value="full-time" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == 'full-time')  ? "selected='true'" : ""}}>Full Time</option>
                            <option value="part-time" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == 'part-time')  ? "selected='true'" : ""}}>Part Time</option>
                            <option value="1" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '1')  ? "selected='true'" : ""}}>1</option>
                            <option value="2" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '2')  ? "selected='true'" : ""}}>2</option>
                            <option value="3" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '3')  ? "selected='true'" : ""}}>3</option>
                            <option value="4" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '4')  ? "selected='true'" : ""}}>4</option>
                            <option value="5" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '5')  ? "selected='true'" : ""}}>5</option>
                            <option value="6" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '6')  ? "selected='true'" : ""}}>6</option>
                            <option value="7" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '7')  ? "selected='true'" : ""}}>7</option>
                            <option value="8" {{ (!empty($data[ 'working_hours']) && $data[ 'working_hours'] == '8')  ? "selected='true'" : ""}}>8</option>
                            
                        </select>
                        <span class="float-label">Working hours*</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <select name="work_from_home" class="custom-select" placeholder="Job working hour">
                            <option value="1" {{ (!empty($data[ 'work_from_home']) && $data[ 'work_from_home'] == '1')  ? "selected='true'" : ""}}>Yes</option>
                            <option value="2" {{ (!empty($data[ 'work_from_home']) && $data[ 'work_from_home'] == '2')  ? "selected='true'" : ""}}>No</option>
                        </select>
                        <span class="float-label">Work from home*</span>
                    </div>
                </div>
                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="number_of_vacancies" placeholder="Number of vacancies*" value="{{ !empty($data[ 'number_of_vacancies']) ? $data[ 'number_of_vacancies'] :''}}">
                        <span class="float-label">Number of vacancies*</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <select name="gender_preference" class="custom-select" placeholder="Gender Preference">
                            <option value="1" {{ (!empty($data[ 'gender_preference']) && $data[ 'gender_preference'] == '1')  ? "selected='true'" : ""}}>All</option>
                            <option value="2" {{ (!empty($data[ 'gender_preference']) && $data[ 'gender_preference'] == '2')  ? "selected='true'" : ""}}>Male</option>
                            <option value="3" {{ (!empty($data[ 'gender_preference']) && $data[ 'gender_preference'] == '3')  ? "selected='true'" : ""}}>Female</option>
                            <!-- <option value="4" {{ (!empty($data[ 'gender_preference']) && $data[ 'gender_preference'] == '4')  ? "selected='true'" : ""}}>Other</option> -->
                        </select>
                        <span class="float-label">Gender Preference*</span>
                    </div>
                </div>
                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <input type="text" id="minimumAgeGroup" name="minimum_age_group" placeholder="Minimum age group*" value="{{ !empty($data[ 'minimum_age_group']) ? $data[ 'minimum_age_group'] : ''}}">
                        <span class="float-label">Minimum age group*</span>
                    </div>
                    <div class="col-md-6 has-float-label ">
                        <input type="text" id="maximumAgeGroup" name="maximum_age_group" placeholder="Maximum age group" value="{{ !empty($data[ 'maximum_age_group']) ? $data[ 'maximum_age_group'] : ''}}">
                        <span class="float-label">Maximum age group</span>
                    </div>
                </div>

                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="education_qualification" placeholder="Education qualification*" value="{{ !empty($data[ 'education_qualification']) ? $data[ 'education_qualification'] : ''}}">
                        <span class="float-label">Education qualification*</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="experience_range" placeholder="Experience range" value="{{ !empty($data[ 'maxmum_age_group']) ? $data[ 'maxmum_age_group'] : ''}}">
                        <span class="float-label">Experience range</span>
                    </div>
                </div>

                <div class="g-cleared form__input-wrapper has-float-label">
                    <textarea type="text" name="job_benefits" placeholder="Job benefits*">{{ !empty($data[ '']) ? $data[ 'job_benefits'] :''}}</textarea>
                    <span class="float-label">Job benefits*</span>
                </div>

                <div class="form__input-wrapper row ">
                    <div class="col-md-4 has-float-label">
                        
                        <select name="salary_currency" placeholder="Salary currency" class="custom-select">

                            @foreach($currencies as $key => $currency)
                            <option value="{{$currency['id']}}" >{{$currency['name']}}</option>
                            @endforeach
                        </select>
                        <span class="float-label">Salary currency*</span>
                    </div>
                    <div class="col-md-4 has-float-label">
                        <input type="text" id="minimumSalary" name="minimum_salary" placeholder="Minimum Salary*" value="{{ !empty($data[ 'minimum_salary']) ? $data[ 'minimum_salary'] :''}}">

                        <span class="float-label">Minimum Salary*</span>
                    </div>
                    <div class="col-md-4 has-float-label">
                        <input type="text" id="maximumSalary" name="maximum_salary" placeholder="maximum Salary*" value="{{ !empty($data[ 'maximum_salary']) ? $data[ 'maximum_salary'] :''}}">
                        <span class="float-label">Maximum Salary*</span>
                    </div>
                </div>





                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="organisation_name" placeholder="Organisation name*" value="{{ !empty($data[ 'organisation_name']) ? $data[ 'organisation_name'] : ''}}">
                        <span class="float-label">Organisation name*</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <input type="text" name="organisation_email" placeholder="Organisation Email" value="{{ !empty($data[ 'organisation_email']) ? $data[ 'organisation_email'] : ''}}">
                        <span class="float-label">Organisation Email</span>
                    </div>
                </div>
                <div class="g-cleared form__input-wrapper has-float-label">
                    <textarea type="text" name="organisation_description" placeholder="Organisation Description*">{{ !empty($data[ 'organisation_description']) ? $data[ 'organisation_description'] :''}}</textarea>
                    <span class="float-label">Organisation Description*</span>
                </div>
                <div class="form__input-wrapper row ">
                    <div class="col-md-12 has-float-label">
                        <input type="text" name="organisation_additional_contact_details" placeholder="Organisation additional contact details*" value="{{ !empty($data[ 'organisation_additional_contact_details']) ? $data[ 'organisation_additional_contact_details'] : ''}}">
                        <span class="float-label">Organisation Additional contact details*</span>
                    </div>
                </div>

                <div class="form__input-wrapper has-float-label">
                    <select type="text" name="mailboxes[]" placeholder="Mailboxes" class="jsMailboxes" multiple>
                        @foreach($mailboxes as $mailbox)
                        <option value="{{$mailbox['id']}}" {{ !empty($mailbox['selected']) ? "selected='true'" : ""}}>{{$mailbox['imap_name']}}</option>
                        @endforeach
                    </select>
                    <span class="float-label">MailBoxes</span>
                </div>
                <!-- <div class="form__input-wrapper has-float-label">
                            <select type="text" name="questions[]" placeholder="Questions" class="jsQuestions" multiple>
                                @foreach($questions as $question)
                                <option value="{{$question['id']}}" {{ !empty($question['selected']) ? "selected='true'" : ""}}>{{$question['question']}}</option>
                                @endforeach
                            </select>
                            <span class="float-label">Questions</span>
                        </div> -->

                <div class="form__input-wrapper has-float-label">
                    <select type="text" name="tags[]" placeholder="Tags" class="jsTags" multiple>
                        @foreach($tags as $tag)
                        <option value="{{$tag['id']}}" {{ !empty($job_tags) && in_array($tag['id'],$job_tags) ? "selected='true'" : ""}}>{{$tag['name']}}</option>
                        @endforeach
                    </select>
                    <span class="float-label">Tags</span>
                </div>

                <div class="form__input-wrapper has-float-label">
                    <input type="text" name="key_skills" placeholder="Key Skills*" value="{{ !empty($data[ 'key_skills']) ? $data[ 'key_skills'] : ''}}">
                    <span class="float-label">Key Skills</span>
                </div>

                <div class="form__input-wrapper row">
                    <div class="col-md-4 has-float-label">
                        <input type="text" class="jsStartDateTime" name="start_date_time" placeholder="Start Date" value="{{ !empty($data[ 'start_date_time']) ? $data[ 'start_date_time'] : ''}}">
                        <span class="float-label">Start Date & Time</span>
                    </div>
                    <div class="col-md-4 has-float-label">
                        <input type="text" class="jsEndDateTime" name="end_date_time" placeholder="End Date" value="{{ !empty($data[ 'end_date_time']) ? $data[ 'end_date_time'] : ''}}">
                        <span class="float-label">End Date & Time</span>
                    </div>
                    
                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )
                    <div class="col-md-4">
                        <div class="checkbox checkbox-switch switch-primary text-right">
                            <label>
                                <input type="checkbox" name="status" id="status" {{  (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "checked" : '' }} class="jsStatus" />
                                <span></span>
                                <label class="jsStatusLabel">{{ (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "Active" : 'Paused' }}</label>
                            </label>
                        </div>
                    </div>

                    @endif

                </div>
                <input type="submit" class="btn btn_blue" value="Save">
                </form>
            </div>
        </div>
        <br />

        </div>
        </div>

    </section>
</section>


@endsection