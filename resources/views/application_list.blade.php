@extends('layouts.default')

@section('title', 'Applications')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'applications')
@section('content')

<section class="page__wrapper-content category">
    <section class="g-cleared">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Applications</h2>
                <div class="inner-nav__cta-wrapper">
                    <button class="btn btn_add btn_blue btn_md js-fetch-all" id="fetch-all">Fetch</button>
                </div>
            </div>
            <div class="filters">
                <div class="filters__item">
                    <label class="filters__item-label">Job Title</label>
                    <select class="filters__item-input jsAppTitle">
                      <option value="-1">All</option>
                      @foreach($categoryGroups as $key => $categoryGroup)
                          <optgroup label="{{$key}}">
                            @foreach($categoryGroup as $category)
                            <option value="{{$category['id']}}" {{ !empty($app_title) ? ($app_title == $category['id'] ? "selected" : "") :""}}>{{$category['title']}}</option>
                            @endforeach
                          </optgroup>
                      @endforeach
                    </select>
                </div>
                <div class="filters__item">
                    <label class="filters__item-label">Status</label>
                    <select class="filters__item-input jsAppStatus">
                      @foreach($statuses as $status)
                          <option value="{{$status['id']}}" {{ !empty($app_status) ? ($app_status == $status['id'] ? "selected" : "") :""}}>{{$status['value']}}</option>
                      @endforeach
                    </select>
                    <input type="hidden" value="{{$app_id}}" name="app_id" class="jsQueryAppId" />
                </div>

                {{--
                <div class="filters__item">
                    <label class="filters__item-label">Type</label>
                    <select class="filters__item-input jsAppType" name="app_type">
                      <option value="-1" {{ empty($app_type) ? "selected" : ""}}>All</option>
                      <option value="1" {{ !empty($app_type) ? ($app_type == "1" ? "selected" : "") :""}}>Fresh</option>
                      <option value="2" {{ !empty($app_type) ? ($app_type == "2" ? "selected" : "") :""}}>Replies</option>
                    </select>
                </div> --}}
            </div>
            <div class="">
                <div class=" g-cleared">
                    <table id="applications-listing" class="js-applications-listing datatable row-border cell-border hover order-column stripe">
                        <thead>
                            <tr>
                                <th>Content</th>
                                <th>Job Title</th>
                                <th>Applicant</th>

                                <th>Rating</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7">Wait for the awesome!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>

        </div>
        </div>

    </section>
</section>

@include('components.popup_hire_email')
@include('components.popup_edit_application')
@include('components.carbonate_authentication')
@include('partials.application_detail')

@endsection