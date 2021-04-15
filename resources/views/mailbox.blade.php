@extends('layouts.default')

@section('title', 'YouRHired : Mailboxes')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'mailbox_form')
@section('content')
<section class="page__wrapper-content mailboxes">
    <section class="g-cleared  ">
        <div class="container">
            <div class="inner-nav">
                <h2 class="inner-nav__title">{{  empty($data[ 'id']) ? 'Add' :'Edit' }} Mailbox</h2>
                <div class="inner-nav__cta-wrapper">
                    <a href="{{ route('mailboxes')}}" class="btn btn_add btn_blue btn_md">Back</a>
                </div>
            </div>
            <div class="col-md-7 g-no-padding">
                <div class=" g-cleared page__blockpage__block_first">
                    <form method="post" id="mailbox-form" action="{{  empty($data[ 'id']) ? route('mailboxes.store') :route('mailboxes.update',$data[ 'id']) }}"
                      class="col-md-12 g-no-gutter js-mailbox-form form">
                        {{ csrf_field() }}
                        @if( !empty($data['id'])) {{ method_field('PUT') }}
                        <input type="hidden" name="id" value="{{ $data[ 'id'] }}" />
                        @endif
                        <input type="hidden" name="imap_connection_status" value="{{ !empty($data['imap_connection_status'])
                          ? $data[ 'imap_connection_status'] : '0' }}" />


                        <div class="g-cleared form__input-wrapper">
                            <input type="text" name="imap_name" class="{{ $errors->has('imap_name') ? ' has-error' : '' }}" placeholder="Name* - e.g Gmail Hire Box"
                              value="{{ !empty($data[ 'imap_name']) ? $data[ 'imap_name'] :''}}">
                            @if ($errors->has('imap_name'))
                            <label class="error" for="email">{{ $errors->first('imap_name') }}</label>
                            @endif
                        </div>
                        <div class="row form__input-wrapper ">
                            <div class="col-md-3 g-no-padding-right">
                                <select name="imap_connection_type" class="jsConnectionType">
                                @foreach($connectionTypes as $connectionType)
                                    <option data-connection="{{$connectionType['connection']}}" value="{{$connectionType['id']}}" {{ !empty($data['imap_connection_type']) ? ($connectionType['id'] == $data['imap_connection_type'] ? "selected" : "") :""}}>{{$connectionType['value']}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-9 g-no-padding-right">
                                <input type="text" name="imap_host" class="{{ $errors->has('imap_host') ? ' has-error' : '' }}" placeholder="IMAP host*"
                                  value="{{ !empty($data[ 'imap_host']) ? $data[ 'imap_host'] :''}}">
                                @if ($errors->has('imap_host'))
                                <label class="error" for="email">{{ $errors->first('imap_host') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form__input-wrapper row ">
                            <div class="col-md-6">
                                <input type="text" name="imap_user" class="{{ $errors->has('imap_user') ? ' has-error' : '' }}" placeholder="IMAP user*"
                                  value="{{ !empty($data[ 'imap_user']) ? $data[ 'imap_user'] : ''}}">
                                @if ($errors->has('imap_user'))
                                <label class="error" for="email">{{ $errors->first('imap_user') }}</label>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <input type="password" name="imap_password" class="{{ $errors->has('imap_password') ? ' has-error' : '' }}"
                                  placeholder="IMAP password*" value="{{ !empty($data[ 'imap_password']) ? $data[ 'imap_password'] : ''}}">
                                @if ($errors->has('imap_password'))
                                <label class="error" for="email">{{ $errors->first('imap_password') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form__input-wrapper row">
                            <div class="col-md-6">
                                <label class="mailboxes__connect jsTestConnection"><icon class="g-icon g-icon_test_connection"></icon>Test Connection</label>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox checkbox-switch switch-primary text-left">
                                    <label>
                                        <input type="checkbox"  {{  ( !empty($data[ 'status']) && $data[ 'status'] =="1") ? ""  : "disabled" }} name="status" id="status" {{  (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "checked" : '' }}  class="jsStatus" />
                                        <span></span>
                                        <label class="jsStatusLabel">{{  (!empty($data[ 'status']) && $data[ 'status'] =='1') ? "Active" : 'Paused' }}</label>
                                    </label>
                                </div>
                            </div>


                        </div>

                        @if( !empty($data['id']))
                        <input type="submit" class="btn btn_blue" value="Update">
                        @else
                        <input type="submit" class="btn btn_blue" value="Save">
                        @endif

                    </form>
                </div>
            </div>
            <br/>

        </div>
        </div>

    </section>
</section>


@endsection