@extends('layouts.no_header')

@section('title', 'YouRHired :: Questionaire')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'response')
@section('content')


<section class="page__wrapper-content">
    <section class="g-cleared sign-up ">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 signupformm" id="">
                    <div class=" panel panel_hanging">
                        <div class="panel__heading text-center">
                            <h4>YouRHired - Questionaire</h4>
                            <p class="text-center small">Thank you for applying with us. Please answer the following questions to
                                help us speeding up the screeing process.</p>
                        </div>

                        <form id="questionaire-form" method="post" action="{{route('response.store')}}" class="form panel__body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="app_token" value={{Request::input('token')}} />

                                    @foreach($questions as $question)
                                    <div class="form__input-wrapper">
                                        <h6>{{$question['question']}}</h6>
                                        @if($question[ 'type'] == "1" || $question[ 'type'] == "2")
                                        <select name="question_{{$question['id']}}[]" {{($question[ 'type']=='2' ) ? 'multiple' : '' }} class="{{($question[ 'type'] == '1') ? 'jsSelectRange' : 'jsSelectTags' }}">
                                          @if(isset($question['options']))
                                            @foreach($question['options'] as $option)
                                              <option>{{$option}}</option>
                                            @endforeach
                                          @endif
                                        </select>
                                        @else
                                        <input type="text" name="question_{{$question['id']}}[]" placeholder="">
                                        @endif

                                    </div>
                                    @endforeach
                                    <div class="text-center">
                                    @if( !empty($data['id']))
                                    <input type="submit" class="btn btn_blue" value="Update">
                                    @else
                                    <input type="submit" class="btn btn_blue" value="Save">
                                    @endif
                                    </div>
                                </div>
                            </div>
                            <p class="first text-center">Don't worry, we won't be spamming you much after this :) <br/> </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection