<div class="g-cleared">
    <div class="inner-nav">
        <h2 class="inner-nav__title"> Organization </h2>
    </div>
    <div class="col-md-8 g-no-padding organization-settings settings__organization">
        <div class=" g-cleared page__blockpage__block_first">
            <form id="organization-settings" method="post" action="/organization/save" class="">
                <input type="hidden" name="_token" id="csrfToken" value="{{ csrf_token() }}" />
                <div class="g-cleared form__input-wrapper">
                    <input type="text" name="name" placeholder="Organization name*" value="{{ $settings[ 'name'] }}">
                </div>
                <div class="g-cleared form__input-wrapper">
                    <input type="text" name="telephone" placeholder="Telephone no*" value="{{$settings[ 'telephone']}}">
                </div>
                <div class="g-cleared form__input-wrapper">
                    <input type="text" name="email" placeholder="Organization email" value="{{$settings[ 'email']}}">
                </div>
                <div class="g-cleared form__input-wrapper">
                    <input type="text" name="website" placeholder="Website" value="{{$settings[ 'website']}}">
                </div>
                <div class="g-cleared form__input-wrapper row mx-auto">

                    <div class="col-md-9">

                    <input type="text" name="api_key" placeholder="Api key" id="apiKey" value="{{$settings[ 'api_key']}}">

                    </div>

                    <div class="col-md-3">
                    <button type="button" class="btn_xs btn_blue m-0 api-key-create" id="genrateApiToken">Generate</button>
                    </div>


                </div>

                <div class="form__input-wrapper g-cleared ">
                    <div class="checkbox checkbox-switch switch-primary text-left">
                        <label>
                        <label class="" for="send_thankyou_email">Enable mail scraping</label>
                        <input type="checkbox" name="send_thankyou_email" id="send_thankyou_email" class="">
                        <span></span>
                        </label>
                    </div>
                </div>
                <input type="submit" class="btn btn_blue" value="Save">
            </form>
        </div>
    </div>
</div>