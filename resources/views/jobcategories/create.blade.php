@extends('layouts.default')

@section('title', 'YouRHired : Job Categories')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'job-category job-categories')
@section('content')


<section class="page__wrapper-content category">
    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">{{ empty($data[ 'id']) ? 'Add' :'Edit' }} Job Category</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="/job-categories" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>
            @if($errors->any())
            <div class="inner-nav">
                @foreach($errors->all() as $error)
                <p class="custom-error">{{ $error }}</p>
                @endforeach
            </div>
            @endif
            <div class="col-md-7 g-no-padding">
                <div class=" g-cleared page__blockpage__block_first">
                    <form method="post" id="tag-form" action="{{  empty($data[ 'id']) ? route('job-categories.store') : route('job-categories.update',$data[ 'id']) }}" class="col-md-12 g-no-gutter js-job-category-form form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        @if(!empty($data[ 'id'])) {{ method_field('PUT') }}
                        @endif
                        @if( !empty($data['id']))
                        <input type="hidden" name="id" value="{{  !empty($data[ 'id']) ? $data[ 'id'] :'' }}" />
                        @endif


                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="name" placeholder="Tag name*" value="{{ !empty($data[ 'name']) ? $data[ 'name'] :''}}">
                            <span class="float-label">Job Category name*</span>
                        </div>

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