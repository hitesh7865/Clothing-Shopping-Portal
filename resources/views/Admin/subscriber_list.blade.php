@extends('layouts.default')

@section('title', 'Subscriber List')

@section('content')
    <div class="g-gutter admin-logout">
        <a class="btn btn-red admin-logout__button" href="/admin/logout">Logout&nbsp;<i class="fa fa-sign-out"></i></a>
    </div>
    <div style="padding-top: 0px" class="admin-list-container g-gutter">
        <div id="pagination-container">
            <div class="admin-list-container__csv">
                <a class="btn btn_teal admin-list-container__csv_button" href="/admin/export-subscribers">CSV <i class="fa fa-arrow-circle-o-down"></i></a>
            </div>
            <div class="admin-list-container__display">
                <label for="pagination_size">Number of records to display: </label>
                <select class="admin-list-container__display_select" id="pagination_size" onchange='initPagination(this.value)'>

                    <option value="10" checked>10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    
                </select>
            </div>
        </div>
        <div id="data-container" class="g-table-simple"></div>
    </div>
    <script src='{{asset("js/admin/subscriber_list.js")}}'></script>
<script>
    initPagination($('#pagination_size').val());
</script>
@endsection
