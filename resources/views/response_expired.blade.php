@extends('layouts.no_header')

@section('title', 'YouRHired :: Thanks')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'response')
@section('content')


<section class="page__wrapper-content">
    <section class="g-cleared sign-up ">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class=" panel panel_hanging">
                        <div class="panel__heading text-center">
                            <h4>YouRHired </h4>
                            <p class="text-center">Woohoo! It seems either the link has expired or you are trying to re-submit
                                the form</p>
                            <p class="text-center">Please <a href="{{route('contact')}}">contact us</a> for any queries related
                                to submissions.</p>
                            <i class="glyphicon glyphicon-heart"> </i>
                            <p class="text-center">Have a good day :)</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection