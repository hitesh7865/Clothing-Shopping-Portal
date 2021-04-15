@extends('layouts.default')

@section('title', 'Login | Admin')

@section('content')
@if (session('error_message'))
    <script>
        toastr.error("{{session('error_message')}}");
    </script>
@endif
<div class="loading g-hidden">&#8230;</div>
<section class="g-gutter admin-login-container">
    <div class="admin-login-container__form">
        <form id="admin_login" action="/admin/login" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="row">
                <div class="col-md-12">
                    <input type="email" name="email" placeholder="Email">
                </div>
                <div class="col-md-12">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div class="col-md-12 admin-login-container__form_submit">                
                    <input type="submit" class="btn btn_teal"value="Login">
                </div>
            </div>
        </form>
    </div>
</section>
<script>
    $('#admin_login').submit(function() {
        if ($(this).valid()) {
            $('.loading').removeClass('g-hidden');
        }
    })
</script>
@endsection
