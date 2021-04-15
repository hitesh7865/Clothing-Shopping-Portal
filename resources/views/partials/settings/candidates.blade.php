<div class="g-cleared">
    <div class="inner-nav">
        <h2 class="inner-nav__title"> Candidates</h2>
    </div>
    <div class="col-md-8 g-no-padding candidate-settings">
        <div class="">
            <form id="candidate-settings" method="post" action="{{  empty($settings['id']) ? route('settings.store') :route('settings.update',$settings[ 'id']) }}"
              class="">
                {{ csrf_field() }}

                @if( !empty($settings['id'])) {{ method_field('PUT') }}
                <input type="hidden" name="id" value="{{ $settings[ 'id'] }}" />
                @endif

                <div class="form__input-wrapper row ">
                    <div class="col-md-10 ">
                        <div class="checkbox checkbox-switch switch-primary text-left candidate-settings__reminder-checkbox">
                            <label>
                              <label class="jsStatusLabel" for="send_reminder_toggle">Send a reminder to Candidates for a reply ? </label>
                            <input type="checkbox" name="send_reminder_toggle" id="send_reminder" {{ (!empty($settings[
                              'send_reminder_email_days']) && $settings[ 'send_reminder_email_days'] !='0' ) ? "checked" : '' }}
                              class="jsReminderToggle" />
                            <span></span>
                            <label>in</label>
                            </label>
                        </div>
                        <div class="candidate-settings__days-input-wrapper">
                            <input name="send_reminder_email_days" class="candidate-settings__input-days {{ $errors->has('send_reminder_email_days') ? ' has-error' : '' }}"
                              value="{{ !empty($settings[ 'send_reminder_email_days'] ) ? $settings[ 'send_reminder_email_days'] :''  }}"
                              type="text" {{ ( !empty($settings[ 'send_reminder_email_days']) ) ? "" :
                              "disabled" }} />
                            @if ($errors->has('imap_name'))
                            <label class="error" for="email">{{ $errors->first('send_reminder_email_days') }}</label>
                            @endif
                            <label class=" candidate-settings__label-days">Days</label>
                        </div>
                    </div>

                </div>
                <div class="form__input-wrapper g-cleared ">
                    <div class="checkbox checkbox-switch switch-primary text-left">
                        <label>
                        <label class="" for="send_thankyou_email">Send a Thank you email ?</label>
                        <input type="checkbox" name="send_thankyou_email" id="send_thankyou_email" {{ (!empty($settings[
                          'send_thankyou_email']) && $settings[ 'send_thankyou_email']=='1' ) ? "checked" : '' }} class=""
                        />
                        <span></span>
                        </label>
                    </div>
                </div>
                <input type="submit" class="btn btn_blue" value="Save">
            </form>
        </div>
    </div>
</div>