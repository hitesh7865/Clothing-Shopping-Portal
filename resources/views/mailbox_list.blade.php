@extends('layouts.default')

@section('title', 'YouRHired : Mailbox')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'mailboxes')
@section('content')

<section class="page__wrapper-content mailbox">

    <section class="g-cleare ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">Mailbox</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{ route('mailboxes.create')}}" class="btn btn_add btn_blue btn_md">Add</a>
                </div>
            </div>
            <div class="">
                <div class=" g-cleare">
                    <table id="jobs-listing" class="js-mailboxes-listing datatable row-border cell-border hover order-column stripe">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Host Info</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Wait for the awesome!</td>
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

@include('partials.mailbox_filter_detail')
@endsection