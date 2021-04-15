@extends('layouts.default')

@section('title', 'Homepage')

@section('content')

<section>
<div class="banner">
<img  class="banner__image" src="{{ asset('/images/upi_dining_final.jpg') }}">
</div>
</section>
<form id="newsLetterForm" method="post" action="/save">
<section class="form-container__section">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="row">
        <div class="col-md-6">
            <!-- <label>Company name </label> -->
            <label>Name</label>
            <input type="text" name="name">
        </div>
        <div class="col-md-6">
            <!-- <label>Website URL </label> -->
            <label>Email </label>
            <input type="email" name="email">
        </div>
    </div>

</section>
<section class="form-container__submit">
    <p class="email-note">
        <input type="checkbox" id="terms_checked" name="terms_checked" checked>
        <label for="terms_checked">subscribe</label>
    </p>
    <input type="submit" class="btn btn-red"value="Submit">
</section>
</form>
<section class="center slider">
 <div>
   <img src="http://placehold.it/350x300?text=1">
 </div>
 <div>
   <img src="http://placehold.it/350x300?text=2">
 </div>
 <div>
   <img src="http://placehold.it/350x300?text=3">
 </div>
 <div>
   <img src="http://placehold.it/350x300?text=3">
 </div>
</section>

<script src="js/main.js"></script>
@endsection
