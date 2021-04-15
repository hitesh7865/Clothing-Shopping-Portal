@extends('layouts.default')

@section('title', 'YouRHired : Users')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'users')
@section('content')

<section class="page__wrapper-content category">

    <section class="g-cleare ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Users</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{ route('users.create') }}" class="btn btn_add btn_blue btn_md">Add</a>
                </div>
            </div>
            <div class="">
                <div class=" g-cleare">
                    <table id="users-listing" class="js-users-listing datatable row-border cell-border hover order-column stripe" style="width:100% !important">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
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