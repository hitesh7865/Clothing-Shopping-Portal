@if (session('success'))
<script>
    toastr.success("{{session('success')}}");
    // toastr.success('We do have the Kapua suite available.', 'Turtle Bay Resort', {timeOut: 5000})
</script>
@endif
<section class="g-cleared sign-up g-bg_palegrey">
    <div class="container">
        <div class="signupform  g-cleared" id="signup">
<div class="col-md-12 signup__header">
<h2>To find out more, sign up here.</h2>
<form id="signupform" method="post" action="/save">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="col-lg-12 custom-label g-cleared">
        <label class="g-font_18">I am a:</label>
        <div class="radio-label">
        <input type="radio"class="radio-custom" id="test1" name="type" checked value="Merchant">
<label  class="radio-custom-label" for="test1">Merchant</label>
</div>
<div class="radio-label">
<input type="radio" class="radio-custom" id="test2" name="type" value="Consumer">
<label class="radio-custom-label"  for="test2">Consumer</label>
</div>
    </div>
    <div class="col-lg-12 g-cleared">
        <input type="text" name="name" placeholder="name*">
    </div>
    <div class="col-lg-12 g-cleared">
        <input type="text" name="email" placeholder="email address*">
    </div>
    <div class="col-lg-12 g-cleared">
        <input type="number" name="phone" placeholder="phone number*">
    </div>
    <div class="col-lg-12 g-cleared">
        <input type="text" name="url" placeholder="URL or Facebook page*">
    </div>
    <input type="submit" class="btn btn_signup"value="Sign up">
</form>
</div>
</div>

</div>
</section>
