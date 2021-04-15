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
                <h2 class="inner-nav__title">Edit' Job</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="/jobs" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>
            
            <div class="col-md-7 g-no-padding">
                <div class=" g-cleared page__blockpage__block_first ">
                    {{-- <form method="post" id="category-form" class="col-md-12 g-no-gutter js-category-form  job-read-only-form form"> --}}
                        {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}" /> --}}
                        {{-- {{ method_field('PUT') }} --}}
                        <input type="hidden" name="id" value="{{  !empty($data['id']) ? $data['id'] :'' }}" />

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="title" placeholder="Job title*" value="{{$data['title']}}" readonly>
                            <span class="float-label">Job title*</span>
                        </div>
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="description" placeholder="Job description*" readonly> {{$data['description']}} </textarea>
                            <span class="float-label">Job description*</span>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="subject_filter" placeholder="Subject filter*" value="{{$data['subject_filter']}}" readonly>
                                <span class="float-label">Subject filter*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="email_filter" placeholder="Email filter" value="{{$data['email_filter']}}" readonly>
                                <span class="float-label">Email filter</span>
                            </div>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <select type="text" name="job_category_id" placeholder="Job Category" class="custom-select" readonly disabled="true">
                                    @foreach($job_categories as $job_category)
                                    <option value="{{$job_category['id']}}" {{ (!empty($data['job_category_id']) && $data['job_category_id'] == $job_category['id'])  ? "selected='true'" : ""}}>{{$job_category['name']}}</option>
                                    @endforeach
                                </select>
                                <span class="float-label">Job Category*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <select type="text" name="question_set_id" placeholder="Job Question set" class="custom-select" disabled="true">
                                    @foreach($question_sets as $question_set)
                                    <option value="{{$question_set['id']}}" {{ (!empty($data['question_set_id']) && $question_set['id'] == $data['question_set_id']) ? "selected='true'" : ""}}>{{$question_set['name']}}</option>
                                    @endforeach
                                </select>
                                <span class="float-label">Question Set*</span>
                            </div>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <select name="working_hours" placeholder="Job working hour" class="jsJobWorkingHour" multiple disabled="true">
                                    <option value="1" {{ (!empty($data['working_hours']) && $data['working_hours'] == '1')  ? "selected='true'" : ""}}>1</option>
                                    <option value="2" {{ (!empty($data['working_hours']) && $data['working_hours'] == '2')  ? "selected='true'" : ""}}>2</option>
                                    <option value="3" {{ (!empty($data['working_hours']) && $data['working_hours'] == '3')  ? "selected='true'" : ""}}>3</option>
                                    <option value="4" {{ (!empty($data['working_hours']) && $data['working_hours'] == '4')  ? "selected='true'" : ""}}>4</option>
                                    <option value="5" {{ (!empty($data['working_hours']) && $data['working_hours'] == '5')  ? "selected='true'" : ""}}>5</option>
                                    <option value="6" {{ (!empty($data['working_hours']) && $data['working_hours'] == '6')  ? "selected='true'" : ""}}>6</option>
                                    <option value="7" {{ (!empty($data['working_hours']) && $data['working_hours'] == '7')  ? "selected='true'" : ""}}>7</option>
                                    <option value="8" {{ (!empty($data['working_hours']) && $data['working_hours'] == '8')  ? "selected='true'" : ""}}>8</option>
                                    <option value="part-time" {{ (!empty($data['working_hours']) && $data['working_hours'] == 'part-time')  ? "selected='true'" : ""}}>Part Time</option>
                                    <option value="full-time" {{ (!empty($data['working_hours']) && $data['working_hours'] == 'full-time')  ? "selected='true'" : ""}}>Full Time</option>
                                </select>
                                <span class="float-label">Working hours*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <select name="work_from_home" placeholder="Job working hour" class="custom-select" disabled="true">
                                    <option value="1" {{ (!empty($data['work_from_home']) && $data['work_from_home'] == '1')  ? "selected='true'" : ""}}>Yes</option>
                                    <option value="2" {{ (!empty($data['work_from_home']) && $data['work_from_home'] == '2')  ? "selected='true'" : ""}}>No</option>
                                </select>
                                <span class="float-label">Work from home*</span>
                            </div>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="number_of_vacancies" placeholder="Number of vacancies*" value="{{ !empty($data['number_of_vacancies']) ? $data['number_of_vacancies'] :''}}" readonly>
                                <span class="float-label">Number of vacancies*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <select name="gender_preference" placeholder="Gender Preference" class="custom-select"  disabled="true">
                                    <option value="1" {{ (!empty($data['gender_preference']) && $data['gender_preference'] == '1')  ? "selected='true'" : ""}}>None</option>
                                    <option value="2" {{ (!empty($data['gender_preference']) && $data['gender_preference'] == '2')  ? "selected='true'" : ""}}>Male</option>
                                    <option value="3" {{ (!empty($data['gender_preference']) && $data['gender_preference'] == '3')  ? "selected='true'" : ""}}>Female</option>
                                    <option value="4" {{ (!empty($data['gender_preference']) && $data['gender_preference'] == '4')  ? "selected='true'" : ""}}>Other</option>
                                </select>
                                <span class="float-label">Gender Preference*</span>
                            </div>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="minimum_age_group" placeholder="Minimum age group*" value="{{$data['minimum_age_group']}}" readonly>
                                <span class="float-label">Minimum age group*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="maximum_age_group" placeholder="Maximum age group" value="{{$data['maximum_age_group'] }}" readonly>
                                <span class="float-label">Maximum age group</span>
                            </div>
                        </div>

                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="education_qualification" placeholder="Education qualification*" value="{{$data['education_qualification']}}" readonly>
                                <span class="float-label">Education qualification*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="experience_range" placeholder="Experience range" value="{{$data['experience_range']}}" readonly>
                                <span class="float-label">Experience range</span>
                            </div>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="job_benefits" placeholder="Job benefits*" readonly>{{ $data['job_benefits']}}</textarea>
                            <span class="float-label">Job benefits*</span>
                        </div>

                        <div class="form__input-wrapper row ">
                            <div class="col-md-4 has-float-label">
                                <select name="salary_currency" placeholder="Salary currency" class="custom-select" disabled="true">
                                    @foreach($currencies as $key => $currency)
                                        <option value="{{$currency->id}}" {{ (!empty($data[ 'salary_currency']) && $data[ 'salary_currency'] == $currency->id)  ? "selected='true'" : ""}}>{{$currency->name}}</option>
                                    @endforeach
                                    
                                    <!-- <option value="1" {{ (!empty($data['salary_currency']) && $data['salary_currency'] == '1')  ? "selected='true'" : ""}}>USD</option>
                                    <option value="2" {{ (!empty($data['salary_currency']) && $data['salary_currency'] == '2')  ? "selected='true'" : ""}}>INR</option>
                                    <option value="3" {{ (!empty($data['salary_currency']) && $data['salary_currency'] == '3')  ? "selected='true'" : ""}}>SGD</option> -->
                                </select>
                                <span class="float-label">Salary currency*</span>
                            </div>
                            <div class="col-md-4 has-float-label">
                                <input type="text" name="minimum_salary" placeholder="Minimum Salary*" value="{{ $data['minimum_salary']}}" readonly>
                                <span class="float-label">Minimum Salary*</span>
                            </div>
                            <div class="col-md-4 has-float-label">
                                <input type="text" name="maximum_salary" placeholder="maximum Salary*" value="{{ $data['maximum_salary']}}" readonly>
                                <span class="float-label">Maximum Salary*</span>
                            </div>
                        </div>
                        
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="organisation_name" placeholder="Organisation name*" value="{{ $data['organisation_name']}}" readonly>
                                <span class="float-label">Organisation name*</span>
                            </div>
                            <div class="col-md-6 has-float-label">
                                <input type="text" name="organisation_email" placeholder="Organisation Email" value="{{ $data['organisation_email']}}" readonly>
                                <span class="float-label">Organisation Email</span>
                            </div>
                        </div>
                        <div class="g-cleared form__input-wrapper has-float-label">
                            <textarea type="text" name="organisation_description" placeholder="Organisation Description*" readonly>{{ $data['organisation_description']}}</textarea>
                            <span class="float-label">Organisation Description*</span>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-12 has-float-label">
                                <input type="text" name="organisation_additional_contact_details" placeholder="Organisation additional contact details*" value="{{ $data['organisation_additional_contact_details'] }}" readonly>
                                <span class="float-label">Organisation Additional contact details*</span>
                            </div>
                        </div>

                        <div class="form__input-wrapper has-float-label">
                            <select type="text" name="tags[]" placeholder="Tags" class="jsTags" multiple disabled="true">
                                @foreach($tags as $tag)
                                <option value="{{$tag['id']}}" {{ !empty($job_tags) && in_array($tag['id'],$job_tags) ? "selected='true'" : ""}}>{{$tag['name']}}</option>
                                @endforeach
                            </select>
                            <span class="float-label">Tags</span>
                        </div>

                        <div class="form__input-wrapper has-float-label">
                            <input type="text" name="key_skills" placeholder="Key Skills*" value="{{ !empty($data[ 'key_skills']) ? $data[ 'key_skills'] : ''}}" readonly>
                            <span class="float-label">Key Skills</span>
                        </div>                   

                        <div class="form__input-wrapper row">
                            <div class="col-md-4 has-float-label">
                                <input type="text"  name="start_date_time" placeholder="Start Date" value="{{ !empty($data['start_date_time']) ? date('d-m-Y H:i',strtotime($data['start_date_time'])) : ''}}" readonly>
                                <span class="float-label">Start Date & Time</span>
                            </div>
                            <div class="col-md-4 has-float-label">
                                <input type="text" name="end_date_time" placeholder="End Date" value="{{ !empty($data['end_date_time']) ? date('d-m-Y H:i',strtotime($data['end_date_time'])) : ''}}" readonly>
                                <span class="float-label">End Date & Time</span>
                            </div>
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

                        <div class="row job-status">

                            <div class="col-md-4">

                                <form method="post" action="{{ route('pending-moderator.update',$data['id']) }}" id="js-accept-job" class="col-md-12 g-no-gutter   job-read-only-form form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    {{ method_field('PUT') }}
                                    <input type="hidden" name="reason" id="reason" value="false">
                                    <input type="hidden" name="approve" value="false">
                                    <input type="submit"  value="Reject" id="rejectbtn" class="btn_blue btn_red btn_md">
                                </form>
    
                            </div> 

                        <div class="col-md-4">

                            <form action="{{ route('pending-moderator.update',$data['id']) }}" method="post" id="category-form" class="col-md-12 g-no-gutter   job-read-only-form form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                {{ method_field('PUT') }}
                                <input type="hidden" name="approve" value="true">
                                <input type="submit"  value="Approve" class="btn_blue btn_md">
                            </form>

                        </div>  

                        </div>

                       
                </div>
            </div>
            <br />

        </div>
        </div>

    </section>
</section>


@endsection
