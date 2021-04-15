<div class="g-cleared">
    <div class="inner-nav">
        <h2 class="inner-nav__title"> Profile</h2>
    </div>
    @if ($errors->any())
    <div class="inner-nav">
        @foreach ($errors->all() as $error)
        <p class="custom-error">{{ $error }}</p>
        @endforeach
    </div>
    @endif
    <div class="col-md-8 g-no-padding imap-settings">
        <div class="user-profile ">
            <form id="user-profile" method="post" action="{{route('profile.update',$settings[ 'id'])}}" class="" autocomplete="off">
                {{csrf_field()}}
                {{ method_field('PUT') }}
                <div class="g-cleared form__input-wrapper has-float-label">
                    <input type="text" name="fullname" placeholder="Host" value="{{ !empty($settings[ 'fullname']) ? $settings[ 'fullname'] :''}}" readonly>
                    <span class="float-label">Name*</span>
                </div>
                <div class="g-cleared form__input-wrapper has-float-label">
                    <input type="text" name="user_name" placeholder="User Name*" value="{{ !empty($settings[ 'user_name']) ? $settings[ 'user_name'] :''}}" autocomplete="off">
                    <span class="float-label">User Name*</span>
                </div>
                <div class="g-cleared form__input-wrapper has-float-label">
                    <input type="text" name="email" placeholder="Email*" value="{{ !empty($settings[ 'email']) ? $settings[ 'email'] :''}}">
                    <span class="float-label">Email*</span>
                </div>
                <div class="form__input-wrapper row ">
                    <div class="col-md-6 has-float-label">
                        <input type="password" name="new_password" id="new_password" placeholder="New Password" value="" autocomplete="new-password">
                        <span class="float-label">New Password</span>
                    </div>
                    <div class="col-md-6 has-float-label">
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="">
                        <span class="float-label">Confirm Password</span>
                    </div>
                </div>
                <input type="submit" class="btn btn_blue" value="Save">
            </form>
        </div>
    </div>
</div>