@extends('layouts.default')

@section('title', 'YouRHired : Create User')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'create-user')
@section('content')


<section class="page__wrapper-content category">
    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Create User</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{ route('users.index') }}" class="btn btn_add btn_blue btn_md">Back</a>
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
                <div class=" g-cleared page__blockpage__block_first user__create-form">

                    <form method="post" id="jsCreateUserForm" action="{{ route('users.store') }}" class="col-md-12 g-no-gutter form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="name" placeholder="Full Name*" value="{{ old('name') }}">
                            <span class="float-label">Name*</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="user_name" placeholder="User Name*" value="{{ old('user_name') }}">
                            <span class="float-label">User Name*</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="email" placeholder="Email*" value="{{ old('email') }}">
                            <span class="float-label">Email*</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="number" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                            <span class="float-label">Phone Number</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <select name="role" id="role" class="jsUserRole">


                                <option value="2" @if (old('role')==2) selected @endif>Moderator</option>

                                <option value="3" @if (old('role')==3) selected @endif>Job Poster</option>

                                {{-- <option value="2"> moderator </option>
                                <option value="3"> Job Poster </option> --}}

                            </select>
                        </div>





                        {{-- @isset(old('field.0'))
                            
                        @endisset --}}
                        <?php
                        if (old('role') == null || old('role') == '') {
                        ?>
                            <div class="g-cleared form__input-wrapper has-float-label" style="display: none;">
                                <select name="country" id="country" class="jsUserCountry">
                                    <option> Select Country </option>
                                </select>

                            </div>

                            <div class="g-cleared form__input-wrapper has-float-label jsUserCityWrap" style="display: none;">

                                <select type="text" name="city[]" id="city" placeholder="Cities" class="jsTags jsUserCity" multiple>

                                </select>

                                <span class="float-label">City</span>

                            </div>

                            <?php
                        } else {
                            if (old('role') == "2") {
                            ?>

                                <div class="g-cleared form__input-wrapper has-float-label" style="display: none;">

                                    <select name="country" id="country" class="jsUserCountry">
                                        <option> Select Country </option>
                                    </select>

                                </div>

                                <div class="g-cleared form__input-wrapper has-float-label jsUserCityWrap" style="display: none;">

                                    <select type="text" name="city[]" id="city" placeholder="Cities" class="jsTags jsUserCity" multiple>

                                    </select>

                                    <span class="float-label">City</span>

                                </div>
                        <?php
                            }
                        }
                        ?>

                        <input type="submit" class="btn btn_blue" value="Create">
                    </form>
                </div>
            </div>
            <br />

        </div>
        </div>

    </section>
</section>


@endsection