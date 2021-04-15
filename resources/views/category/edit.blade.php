@extends('layouts.default')

@section('title', 'YouRHired : Jobs')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'job jobs Edit')
@section('content')

<section class="page__wrapper-content category">
    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Edit Job</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="/jobs" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>

            <div class="col-md-8 g-no-padding">
                <div class=" g-cleared page__blockpage__block_first">
                    <form method="post" id="jobs_edit_form" class="col-md-12 g-no-gutter js-category-form form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        {{ method_field('PUT') }}
                        <input type="hidden" id="job_id" name="job_id" value="{{  !empty($data[ 'id']) ? $data[ 'id'] :'' }}" />


                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="title" placeholder="Job title*" value="{{$data[ 'title']}}">
                            <span class="float-label">Job title*</span>
                        </div>
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="description" placeholder="Job description*">{{$data[ 'description']}}</textarea>
                            <span class="float-label">Job description*</span>
                        </div>

                        @if (Auth::user()->role_id == 1)
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <select name="created_by" class="custom-select" placeholder="Created By">
                                @foreach($users as $user)
                                <option value="{{$user['id']}}" {{ (!empty($user[ 'id']) && $data[ 'created_by'] == $user['id'])  ? "selected='true'" : ""}}>{{$user['fullname']}} ({{$user['user_name']}})</option>
                                @endforeach
                            </select>
                            <span class="float-label">Created By*</span>
                        </div>

                        @endif


                        <div class="has-float-label">
                            <select name="location" id="country" class="jsUserCountry custom-select">
                                <option> Select Country </option>
                                @foreach($job_locations as $key => $job_location)
                                <option value="{{$job_location['country_code']}}" id="{{$job_location['country_code']}}" data-country="{{$job_location['country']}}" selected>{{$job_location['country']}}</option>
                                @break;
                                @endforeach
                            </select>
                            <span class="float-label">Location</span>
                        </div>
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <select type="text" name="city[]" id="city" placeholder="Cities" class="jsUserCity" multiple>
                                <?php if (isset($job_location)) { ?>
                                    @foreach($job_locations as $job_location)
                                    <option value="{{$job_location['city']}}" id="{{$job_location['city_id']}}" selected>
                                        {{$job_location['city']}}
                                    </option>
                                    @endforeach
                                <?php } ?>
                            </select>
                            <input type="hidden" name="hidden_city[]" id="hidden_city" class="jsHiddenUserCity" value="">
                            <span class="float-label">City</span>
                        </div>



                        <!-- <div class=" has-float-label">
                            <select name="PreferredCountry" id="PreferredCountry " class="jsUserPreferredCountry custom-select">
                                <?php //if (isset($job_location)) { 
                                ?>
                                    @foreach($job_preferred_locations as $job_preferred_location)
                                    <option value="{{$job_location['id']}}" {{ (!empty($data[ 'job_location']) && $data[ 'job_location'] == $job_preferred_location['id'])  ? "selected='true'" : ""}}>{{$job_preferred_location['country']}}</option>
                                    @endforeach
                                <?php //} 
                                ?>
                            </select>
                            <span class="float-label">Preferred Location</span>
                        </div> -->

                        <div class="has-float-label">
                            <select name="functional_area" class="custom-select" placeholder="Job working hour">
                                <option value=""></option>
                                @foreach($functional_area as $farea)
                                <option value="{{$farea['id']}}" {{ (!empty($data[ 'functional_area']) && $data[ 'functional_area'] == $farea['id'])  ? "selected='true'" : ""}}>{{$farea['name']}}</option>
                                @endforeach
                            </select>
                            <span class="float-label">Functional area*</span>
                        </div>

                        <!-- <div class="g-cleared form__input-wrapper has-float-label">
                            <select type="text" name="PreferredCity" id="PreferredCity" placeholder="Cities" class="jsTags jsPreferredCity" multiple>
                                @foreach($citylist as $city)
                                <option value="{{$city['city_name']}}" id="{{$city['city_id']}}" selected>
                                    {{$city['city_name']}}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="hidden_preferred_city[]" id="hidden_preferred_city" class="jsHiddenPreferredCity" value="">
                            <span class="float-label">Preferred City</span>
                        </div> -->

                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="subject_filter" placeholder="Subject filter*" value="{{$data[ 'subject_filter']}}">
                                <span class="float-label">Subject filter</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="email_filter" placeholder="Email filter" value="{{$data[ 'email_filter']}}">
                                <span class="float-label">Email filter</span>
                            </div>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <select type="text" name="job_category_id" placeholder="Job Category" class="jsJobCategory custom-select">
                                    @foreach($job_categories as $job_category)
                                    <option value="{{$job_category['id']}}" {{ (!empty($data[ 'job_category_id']) && $data[ 'job_category_id'] == $job_category['id'])  ? "selected='true'" : ""}}>{{$job_category['name']}}</option>
                                    @endforeach
                                </select>
                                <span class="float-label">Job Category*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <select type="text" name="question_set_id" placeholder="Job Question set" class="custom-select">
                                    <option value=""></option>

                                    @foreach($question_sets as $question_set)
                                    <option value="{{$question_set['id']}}" {{ (!empty($data[ 'question_set_id']) && $question_set['id'] == $data['question_set_id']) ? "selected='true'" : ""}}>{{$question_set['name']}}</option>
                                    @endforeach
                                </select>
                                <span class="float-label">Question Set</span>
                            </div>
                        </div>
                        <div class="form__input-wrapper row">
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
                                <select name="work_from_home" placeholder="Job working hour" class="custom-select">
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
                                <select name="gender_preference" placeholder="Gender Preference" class="custom-select">
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
                                <input type="text" id="minimumAgeGroup" name="minimum_age_group" placeholder="Minimum age group*" value="{{$data[ 'minimum_age_group']}}">
                                <span class="float-label">Minimum age group*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" id="maximumAgeGroup" name="maximum_age_group" placeholder="Maximum age group" value="{{$data[ 'maximum_age_group'] }}">
                                <span class="float-label">Maximum age group</span>
                            </div>
                        </div>

                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="education_qualification" placeholder="Education qualification*" value="{{$data[ 'education_qualification']}}">
                                <span class="float-label">Education qualification*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="experience_range" placeholder="Experience range" value="{{$data[ 'experience_range']}}">
                                <span class="float-label">Experience range</span>
                            </div>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="job_benefits" placeholder="Job benefits*">{{ $data[ 'job_benefits']}}</textarea>
                            <span class="float-label">Job benefits*</span>
                        </div>

                        <div class="form__input-wrapper row ">
                            <div class="col-md-4 has-float-label">
                                <select name="salary_currency" placeholder="Salary currency" class="custom-select" value="">
                                    @foreach($currencies as $key => $currency)
                                    <option value="{{$currency['id']}}" {{ (!empty($data[ 'salary_currency']) && $data[ 'salary_currency'] == $currency['id'])  ? "selected='true'" : ""}}>{{$currency['name']}}</option>
                                    @endforeach
                                </select>
                                <span class="float-label">Salary currency*</span>
                            </div>
                            <div class="col-md-4 has-float-label">
                                <input type="text" id="minimumSalary" name="minimum_salary" placeholder="Minimum Salary*" value="{{ $data[ 'minimum_salary']}}">
                                <span class="float-label">Minimum Salary*</span>
                            </div>
                            <div class="col-md-4 has-float-label">
                                <input type="text" id="maximumSalary" name="maximum_salary" placeholder="maximum Salary*" value="{{ $data[ 'maximum_salary']}}">
                                <span class="float-label">Maximum Salary*</span>
                            </div>
                        </div>

                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="organisation_name" placeholder="Organisation name*" value="{{ $data[ 'organisation_name']}}">
                                <span class="float-label">Organisation name*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="organisation_email" placeholder="Organisation Email" value="{{ $data[ 'organisation_email']}}">
                                <span class="float-label">Organisation Email</span>
                            </div>
                        </div>
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="organisation_description" placeholder="Organisation Description*">{{ $data[ 'organisation_description']}}</textarea>
                            <span class="float-label">Organisation Description*</span>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-12 has-float-label">
                                <input type="text" name="organisation_additional_contact_details" placeholder="Organisation additional contact details*" value="{{ $data[ 'organisation_additional_contact_details'] }}">
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
                                <input type="hidden" id="txtHiddenStartDateTime" value="{{ !empty($data[ 'start_date_time']) ? $data[ 'start_date_time'] : ''}}">

                                <input type="text" class="jsEditStartDateTime" name="start_date_time" placeholder="Start Date" value="{{ !empty($data[ 'start_date_time']) ? $data[ 'start_date_time'] : ''}}">
                                <span class="float-label">Start Date & Time</span>
                            </div>
                            <div class="col-md-4 has-float-label">
                                <input type="hidden" id="txtHiddenEndDateTime" value="{{ !empty($data[ 'end_date_time']) ? $data[ 'end_date_time'] : ''}}">
                                <input type="text" class="jsEditEndDateTime" name="end_date_time" placeholder="End Date" value="{{ !empty($data[ 'end_date_time']) ? $data[ 'end_date_time'] : ''}}">
                                <span class="float-label">End Date & Time</span>
                            </div>
                            @if (Auth::user()->role_id == 1 && $data['status'] != 5)
                            <div class="col-md-4">
                                <div class="checkbox checkbox-switch switch-primary text-right">
                                    <label>
                                        <input type="hidden" name="hidden_status_code" value="{{$data[ 'status']}}">
                                        <input type="checkbox" name="status" id="editJobStatus" {{  (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "checked" : '' }} class="jsStatus" />
                                        <span></span>
                                        <label class="jsStatusLabel">{{ (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "Active" : 'Paused' }}</label>
                                    </label>
                                </div>
                            </div>
                            @elseif(Auth::user()->role_id != 1 && $data['status'] == 0 || $data['status'] == 1 || $data['status'] == 2 )
                            <div class="col-md-4">
                                <div class="checkbox checkbox-switch switch-primary text-right">
                                    <label>
                                        <input type="hidden" name="hidden_status_code" value="{{$data[ 'status']}}">
                                        <input type="checkbox" name="status" id="editJobStatus" {{  (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "checked" : '' }} class="jsStatus" />
                                        <span></span>
                                        <label class="jsStatusLabel">{{ (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "Active" : 'Paused' }}</label>
                                    </label>
                                </div>
                            </div>
                            @endif

                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-12 has-float-label">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Rejected By</th>
                                            <th>Reason</th>  
                                            <th>Date</th> 
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Rejected By</th>
                                            <th>Reason</th> 
                                            <th>Date</th> 
                                        </tr>
                                    </tfoot>
                                    @if($rejectdata==null)
                                        <tbody>
                                            <tr>
                                                <td colspan='3' style='text-align:center'>No data available in table</td>
                                            </tr>
                                        </tbody>
                                    @else
                                        <tbody>
                                        @foreach($rejectdata as $rd)
                                            <tr>
                                                <td>{{$rd['name']}}-{{$rd['rejected_by']}}</td>
                                                <td>{{$rd['reason']}}</td>
                                                <td>{{Carbon\Carbon::parse($rd['created_at'])->format('d M Y')}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <input type="submit" class="btn btn_blue" value="Update">
                    </form>
                </div>
            </div>
            <br />

        </div>
        </div>

    </section>
</section>


@endsection