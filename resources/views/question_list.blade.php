@extends('layouts.default')

@section('title', 'YouRHired : Questions')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'questions')
@section('content')

<section class="page__wrapper-content category">

    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Questions</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{route('questions.create')}}" class="btn btn_add btn_blue btn_md">Add</a>
                </div>
            </div>
            <!-- <div class="filters">
                {{--
                <div class="filters__item">
                    <label class="filters__item-label">Jobs</label>
                    <select class="filters__item-input jsJobsListing">
                      @foreach($categories as $category)
                          <option value="{{$category['id']}}" {{ !empty($category_id) ? ($category_id == $category['id'] ? "selected" : "") :""}}>{{$category['title']}}</option>
                      @endforeach
                    </select>
                </div> --}}
                <div class="filters__item">
                    <label class="filters__item-label">Status</label>
                    <select class="filters__item-input jsQuestionStatus">
                      @foreach($statuses as $status)
                          <option value="{{$status['id']}}" {{ !empty($job_status_id) ? ($job_status_id == $status['value'] ? "selected" : "") :""}}>{{$status['value']}}</option>
                      @endforeach
                    </select>
                </div>
            </div> -->
            <div class="">
                <div class=" g-cleared">

                    <table id="questions-listing" class="js-questions-listing datatable row-border cell-border hover order-column stripe">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Type</th>
                                <th>Options</th>
                                <th>Positives</th>
                                <th>Negatives</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6">Wait for the awesome!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br />

        </div>
        </div>

    </section>
</section>

@endsection