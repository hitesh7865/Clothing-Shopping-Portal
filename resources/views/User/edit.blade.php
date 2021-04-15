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
                <h2 class="inner-nav__title">Edit User</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{ route('users.index') }}" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>
            @if ($errors->any())
            <div class="inner-nav">
                @foreach ($errors->all() as $error)
                <p class="custom-error">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="col-md-7 g-no-padding">
                <div class=" g-cleared page__blockpage__block_first">

                    <form method="post" id="jsCreateUserForm" action="{{ route('users.update', $data['id']) }}" class="col-md-12 g-no-gutter js-tag-form form">

                        {{ method_field('PUT') }}

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="name" placeholder="Full Name*" value="{{ $data['fullname'] }}">
                            <span class="float-label">Name*</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="user_name" placeholder="User Name*" value="{{ $data['user_name'] }}">
                            <span class="float-label">User Name*</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="text" name="email" placeholder="Email*" value="{{ $data['email'] }}" readonly="true">
                            <span class="float-label">Email*</span>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <input type="number" name="phone" placeholder="Phone Number" value="{{ $data['phone'] }}">
                            <span class="float-label">Phone Number</span>
                        </div>



                        <div class="g-cleared form__input-wrapper has-float-label">
                            <select name="role_id" id="role" class="custom-select col-md-12 jsUserRole">

                                <option value="2" @if ($data['role_id']=="2" ) selected @endif> Moderator </option>
                                <option value="3" @if ($data['role_id']=="3" ) selected @endif> Job Poster </option>

                            </select>
                        </div>

                        @if ($data['role_id'] == "2")

                        <div class="g-cleared form__input-wrapper has-float-label" style="display: none;">
                            <select name="country" id="country" class="jsUserCountry custom-select">
                                <option> Select Country </option>
                            </select>
                        </div>

                        <div class="g-cleared form__input-wrapper has-float-label jsUserCityWrap" style="display: none;">

                            <select type="text" name="city[]" id="city" placeholder="Cities" class="jsTags jsUserCity" multiple>

                            </select>

                        </div>

                        @endif
                        {{-- <div class="g-cleared form__input-wrapper has-float-label">
                                <input type='checkbox' class='custom-control-input toggle-switch' id="status" data-toggle='toggle'
                                    name='status' @if ($data['status'] == 1)
                                        checked="on"
                                    @endif>
                                <label class='custom-control-label' for='status'>Status</label>

                            </div> --}}

                        <div class="g-cleared form__input-wrapper has-float-label">
                            <div class="checkbox checkbox-switch switch-primary">
                                <label>
                                    <input type="checkbox" name="status" id="jsUserStatus" class="jsStatus" @if ($data['status']==1) checked @endif>
                                    <span></span>
                                    <label class="jsUserStatusLabel">@php
                                        echo ($data['status'] == '1') ? 'Active' : 'In Active';
                                        @endphp</label>
                                </label>
                            </div>
                        </div>



                        <input type="submit" class="btn btn_blue" value="Update">
                    </form>
                </div>
            </div>
            <br />

        </div>
        </div>

    </section>
</section>


@endsection