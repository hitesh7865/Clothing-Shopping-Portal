@extends('layouts.no_header')
@section('title', 'Homepage')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel_hanging panel-default register">

                <div class="panel__heading text-center">
                    <h4>Signup</h4></div>

                <div class="panel__body">

                    <form class="form-horizontal form js-register-form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form__group ">
                            <div class="">
                                <input placeholder="Name*" id="fullname" type="text" class="register__form-control {{ $errors->has('fullname') ? ' has-error' : '' }}"
                                  name="fullname" value="{{ old('fullname') }}" required autofocus>

                                @if ($errors->has('fullname'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('fullname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form__group ">
                            <div class="">
                                <input placeholder="Email*" id="email" type="email" class="register__form-control {{ $errors->has('email') ? ' has-error' : '' }}"
                                  name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <label class="error" for="email">{{ $errors->first('email') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form__group row">
                            <div class="col-md-12 form__group ">
                                <input placeholder="Company name*" id="organization_name" type="text" class="register__form-control {{ $errors->has('organization_name') ? ' has-error' : '' }}"
                                  name="organization_name" required value="{{ old('organization_name') }}">
                            </div>
                            @if ($errors->has('organization_name'))
                            <label class="error" for="organization_name">{{ $errors->first('organization_name') }}</label>
                            @endif
                        </div>
                        <div class="form__group">
                            <div class="">
                                <input placeholder="Password*" id="password" type="password" class="register__form-control  {{ $errors->has('password') ? ' has-error' : '' }}"
                                  name="password" required>

                                @if ($errors->has('password'))
                                <label class="error" for="password">{{ $errors->first('password') }}</label>
                                @endif
                            </div>
                        </div>



                        <div class="form__group text-center">
                            <button type="submit" class="btn btn_blue">
                                    Get me in
                                </button>
                        </div>
                        <p class="first text-center">
                            Or <a href="{{route('login')}}">Login</a> to continue.</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection