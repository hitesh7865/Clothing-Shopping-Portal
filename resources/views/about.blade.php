@extends('layouts.default')

@section('title', 'Acloudery')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('content')
<header class="header ">
    @include('partials.header')
</header>
<section class="banner banner-about">
<img src="{{ asset('/images/bg.jpg') }}"/>
<div class="banner-about__header g-element-center">
<h1>
    Redefining ecommerce
for peace of mind.</h1>
</div>

</section>

@include('partials.about')
@include('partials.signupform')

@endsection
