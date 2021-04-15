<div class="white-popup mfp-hide jsCarbonateAuthenticationPopup popup">
    <div class="popup__header">
        Carbonate Authentication
    </div>
    <div class="popup__content">
        <p>
            Please login with your Carbonate credentials!
        </p>
        <form id="carbonate-auth-form">
            <input type="hidden" name="authapp" value="carbonate">
            <input type="hidden" name="applicantId" value="">
            <div class="g-cleared form__input-wrapper has-float-label">
                <input type="text" name="email" value="" tabindex="1">
                <span class="float-label">Email</span>
            </div>
            <div class="g-cleared form__input-wrapper has-float-label">
                <input type="password" name="password" value="" tabindex="1">
                <span class="float-label">Password</span>
            </div>
        </form>
    </div>
    <div class="popup__footer g-cleared">
        <div class="col-md-12 action action_save jsSubmitAuth">
            Submit
        </div>
    </div>

</div>

<div class="white-popup mfp-hide jsCarbonateAccountSwitchPopup popup">
    <input type="hidden" name="applicantId" value="">
    <div class="popup__header">
        Confirm Account
    </div>
    <div class="popup__content">
        <p>
            Active Carbonate account: <span id="carbonateUserName" class="g-text-bold"></span> (<span id="carbonateUserEmail"></span>)
        </p>
    </div>
    <div class="popup__footer g-cleared">
        <div class="col-md-6 action action_save jsPerformCarbonateSync">
            Continue
        </div>
        <div class="col-md-6 action action_close jsSwitchAccount">Switch account</div>
    </div>

</div>