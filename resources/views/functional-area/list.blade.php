@extends('layouts.default')

@section('title', 'YouRHired : Tags')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'tags')
@section('content')

<section class="page__wrapper-content category">

    <section class="g-cleare ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Functional Area</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="/functional-areas/create" class="btn btn_add btn_blue btn_md">Add</a>
                </div>
            </div>
            <div class="">
                <div class=" g-cleare">
                    <table id="functional-area-listing" class="js-functional-area-listing datatable row-border cell-border hover order-column stripe">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3">Wait for the awesome!</td>
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