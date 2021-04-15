<div class="white-popup mfp-hide jsHireEmailPopup popup">
    <div class="popup__header">
        Send Confirmation email
    </div>
    <div class="popup__content">
        <form id="popup-hire-email-form">
            <div class="g-cleared form__input-wrapper has-float-label">
                <input type="text" name="subject" value="Hiring Confirmation for the job" tabindex="1">
                <span class="float-label">Subject</span>
            </div>
            <div class="g-cleared form__input-wrapper has-float-label">
                <textarea name="content" value="" tabindex="2">Yay!
We are glad to inform you that we you have been selected for the job post.</textarea>
                <span class="float-label">Content</span>
            </div>
        </form>
    </div>
    <div class="popup__footer g-cleared">
        <div class="col-md-6 action action_save jsSendEmail">
            Send
        </div>
        <div class="col-md-6 action action_close jsContinue">Don't send and continue</div>
    </div>

</div>

<div class="white-popup mfp-hide jsRejectionEmailPopup popup">
    <div class="popup__header">
        Send Rejection email
    </div>
    <div class="popup__content">
        <form id="popup-hire-email-form">
            <div class="g-cleared form__input-wrapper has-float-label">
                <input type="text" name="subject" value="Application status" tabindex="1">
                <span class="float-label">Subject</span>
            </div>
            <div class="g-cleared form__input-wrapper has-float-label">
                <textarea name="content" value="" tabindex="2">Thank you for spending your time so far, but unfortunately you do not fit into our requirement.Best of luck for your carrier.
                </textarea>
                <span class="float-label">Content</span>
            </div>
        </form>
    </div>
    <div class="popup__footer g-cleared">
        <div class="col-md-6 action action_save jsSendEmail">
            Send
        </div>
        <div class="col-md-6 action action_close jsContinue">Don't send and continue</div>
    </div>

</div>