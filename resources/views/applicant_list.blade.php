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
                <h2 class="inner-nav__title">Applicants</h2> {{--
                <div class="inner-nav__cta-wrapper">
                    <a href="/question/create" class="btn btn_add btn_blue btn_md">Add</a>
                </div> --}}
            </div>
            <div class="">
                <div class=" g-cleared">
                    <table id="applicants-listing" class="js-applicants-listing datatable row-border cell-border hover order-column stripe">
                        <thead>
                            <tr>
                                <th>Application ID</th>
                                <th>Question ID</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Rating</th>
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


@endsection