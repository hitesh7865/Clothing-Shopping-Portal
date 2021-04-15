@extends('layouts.no_header')

@section('title', 'YouRHired : Login')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'login')
@section('content')


<section class="page__wrapper-content">
    {{--
    <section class="banner banner-about">
        <img src="{{ asset('/images/bg.jpg') }}" />
        <div class="banner-about__header g-element-center">
            <h1>
          Redefining ecommerce
      for peace of mind.</h1>
        </div>

    </section>

    @include('partials.about') --}}
    @include('partials.login_form')
</section>

@endsection