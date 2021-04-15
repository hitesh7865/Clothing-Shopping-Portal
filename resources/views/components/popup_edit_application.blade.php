<div class="white-popup mfp-hide jsEditApplicationPopup popup">
    <div class="popup__header">
        Edit Candidate
    </div>
    <div class="popup__content">
        <form id="popup-candidate-form">
            <div class="g-cleared form__input-wrapper row">
                <div class="col-md-6  has-float-label">
                    <input type="text" name="name" placeholder="" tabindex="1">
                    <span class="float-label">Name</span>
                </div>
            </div>
            <div class="form__input-wrapper has-float-label">
                <select name="rating" placeholder="Rating" class="jsRating">
                  @for($i=1; $i <= 5 ; $i++)
                  <option value="{{$i}}">{{$i}}</option>
                  @endfor
              </select>
                <span class="float-label">Rating</span>
            </div>
        </form>
    </div>
    <div class="popup__footer g-cleared">
        <div class="col-md-6 action action_save jsSaveApplicationEdit">
            Save
        </div>
        <div class="col-md-6 action action_close jsCancelApplicationEdit">
            Cancel
        </div>
    </div>

</div>