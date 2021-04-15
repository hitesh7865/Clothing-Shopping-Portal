<div class="g-cleared">
    <div class="inner-nav">
        <h2 class="inner-nav__title"> IMAP</h2>
    </div>
    <div class="col-md-8 g-no-padding imap-settings">
        <div class="organization-settings ">
            <form id="organization-settings" method="post" action="/smtp/save" class="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="g-cleared">
                    <input type="text" name="imap_host" placeholder="Host" value="{{ $settings[ 'imap_host'] }}">
                </div>
                <div class="g-cleared">
                    <input type="text" name="imap_user" placeholder="Username" value="{{$settings[ 'imap_user']}}">
                </div>
                <div class="g-cleared">
                    <input type="password" name="imap_password" placeholder="Password" value="{{$settings['imap_password']}}">
                </div>
                <div class="g-cleared">
                    <input type="text" name="imap_folder" placeholder="Inbox/Folder" value="INBOX" disabled>
                </div>
                <div class="g-cleared">
                    <input type="text" name="imap_connection_type" placeholder="Connection" value="{{$settings[ 'imap_connection_type']}}">
                </div>
                <input type="submit" class="btn btn_blue" value="Save">
            </form>
        </div>
    </div>
</div>