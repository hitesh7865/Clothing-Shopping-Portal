<div class="g-cleared">
    <div class="inner-nav">
        <h2 class="inner-nav__title"> SMTP</h2>
    </div>
    <div class="col-md-8  g-no-padding smtp-settings">
        <div class="organization-settings ">
            <form id="organization-settings" method="post" action="/imap/save" class="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="g-cleared">
                    <input type="text" name="smtp_host" placeholder="Host" value="{{ $settings[ 'smtp_host'] }}">
                </div>
                <div class="g-cleared">
                    <input type="text" name="smtp_user" placeholder="Username" value="{{$settings[ 'smtp_user']}}">
                </div>
                <div class="g-cleared">
                    <input type="password" name="smtp_password" placeholder="Password" value="{{ isset($settings['smtp_password']) ? Crypt::decrypt($settings['smtp_password']) : ''}}">
                </div>
                <input type="submit" class="btn btn_blue" value="Save">
            </form>
        </div>
    </div>
</div>