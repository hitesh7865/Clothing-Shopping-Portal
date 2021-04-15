@extends('layouts.default')

@section('title', 'YouRHired : Jobs')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'jobs')
@section('content')

<section class="page__wrapper-content category">

    <section class="g-cleare ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Jobs</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="/jobs/create" class="btn btn_add btn_blue btn_md">Add</a>
                </div>
            </div>            
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="inner-nav">
                <div class="alert alert-success">
                    
                </div>
            </div>

            <div class="">
                <div class=" g-cleare">                    
                    <table id="jobs-listing" class="js-jobs-listing datatable row-border cell-border hover order-column stripe">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Filters</th>
                                <th>Mailboxes</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">Wait for the awesome!</td>
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

@include('partials.category_filter_detail')
@endsection