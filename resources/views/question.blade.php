@extends('layouts.default')

@section('title', 'YouRHired : Jobs')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'question questions')
@section('content')


<section class="page__wrapper-content questions">
    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">{{ empty($data[ 'id']) ? 'Add' :'Edit' }} Question</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{route('questions')}}" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>
            <div class="col-md-8 g-no-padding">
                <div class=" g-cleared ">
                    <form method="post" id="question-form" action="{{  empty($data[ 'id']) ? route('questions.store') : route('questions.update',$data[ 'id']) }}" class="col-md-8 g-no-gutter js-question-form">
                        {{csrf_field()}}
                        @if(!empty($data[ 'id'])) {{ method_field('PUT') }}
                        @endif
                        @if( !empty($data['id']))
                        <input type="hidden" name="id" value="{{  !empty($data[ 'id']) ? $data[ 'id'] :'' }}" />
                        @endif
                        <div class="g-cleared ">
                            <select name="type" id="type" class="jsQuestionType">
                                <option value="1" {{!empty($data['type']) && $data['type'] == 1 ? 'selected' : ''}}>Range</option>
                                <option value="2" {{!empty($data['type']) && $data['type'] == 2 ? 'selected' : ''}}>Multi Option</option>
                                <option value="3" {{!empty($data['type']) && $data['type'] == 3 ? 'selected' : ''}}>Text</option>
                                <option value="4" {{!empty($data['type']) && $data['type'] == 4 ? 'selected' : ''}}>Long Text</option>
                                <option value="5" {{!empty($data['type']) && $data['type'] == 5 ? 'selected' : ''}}>Dropdown</option>
                            </select>
                        </div>
                        
                        <div class="g-cleared form__input-wrapper has-float-label ">
                            <input type="text" name="question" value="{{ !empty($data[ 'question']) ? $data[ 'question'] :''}}">
                            <span class="float-label">Question*</span>
                        </div>
                        
                        <div class="form__select-wrapper has-float-label">
                            <select type="text" name="question_sets[]" placeholder="Options" class="jsQuestionSets" multiple>
                                @if(!empty($data['question_sets']))
                                @foreach($data['question_sets'] as $question_set)
                                <option value="{{$question_set['id']}}" @if(!empty($data['id']) && in_array($question_set['id'],$data['question_set_question'])) {{'selected="true"'}} @endif>{{$question_set['name']}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="float-label">Add Question Set*</span>
                        </div>

                        <div class="form__select-wrapper has-float-label jsOptionsWrapper">
                            <select type="text" name="options[]" placeholder="Options" class="jsOptions" multiple>
                                @if(!empty($data['options']))
                                @foreach($data['options'] as $option)
                                <option value="{{$option}}" selected="true">{{$option}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="float-label">Add Options*</span>
                        </div>
                        <div class="form__select-wrapper has-float-label">
                            <select type="text" name="positives[]" placeholder="Options" class="jsPositives" multiple>
                                @if(!empty($data['positives']))
                                @foreach($data['positives'] as $option)
                                <option value="{{$option}}" selected="true">{{$option}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="float-label">Add Postives</span>
                        </div>
                        <div class="form__select-wrapper has-float-label">
                            <select type="text" name="negatives[]" placeholder="Options" class="jsNegatives" multiple>
                                @if(!empty($data['negatives']))
                                @foreach($data['negatives'] as $option)
                                <option value="{{$option}}" selected="true">{{$option}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="float-label">Add Negatives</span>
                        </div>

                        {{--
                        <div class="g-cleared">

                            <select name='cat_id'>
                              @if( !empty($data[ 'categories']))
                                @foreach($data[ 'categories'] as $category)
                                <option value="{{ $category->id }}" {{ !empty($data[ 'cat_id']) ? ($data[ 'cat_id'] == $category->id ? "selected" : "") :""}}>{{ $category->title }}</option>
                        @endforeach
                        @endif
                        </select>
                </div> --}}
                @if( !empty($data['id']))
                        <input type="submit" class="btn btn_blue" value="Update">
                        @else
                        <input type="submit" class="btn btn_blue" value="Save">
                        @endif
                </form>
            </div>
        </div>
        <br />

        </div>
        </div>

    </section>
</section>


@endsection