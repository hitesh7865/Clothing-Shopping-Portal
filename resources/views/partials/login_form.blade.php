@if (session('success'))
<script>
    toastr.success("{{session('success')}}");
    // toastr.success('We do have the Kapua suite available.', 'Turtle Bay Resort', {timeOut: 5000})
</script>
@endif
<section class="g-cleared sign-up ">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 signupform" id="signup">
                <div class=" panel panel_hanging">
                    <div class="panel__heading text-center">
                        <h4>YouRHired - Login</h4>
                    </div>

                    <form id="loginform" method="post" action="/login" class="form panel__body text-center">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form__input-wrapper">
                                    <input type="text" name="email" value="{{ old('email')}}" placeholder="Email" class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                    @if ($errors->has('email'))
                                    <label class="error" for="email">{{ $errors->first('email') }}</label>
                                    @endif
                                </div>
                                <div class="form__input-wrapper">
                                    <input type="password" name="password" placeholder="Password" class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                    @if ($errors->has('password'))
                                    <label class="error" for="email">{{ $errors->first('password') }}</label>
                                    @endif
                                </div>
                                <input type="submit" class="btn btn_blue" value="Login">
                            </div>
                        </div>
                        <p class="first text-center">Don't have an account?</br>Register
                            <a href="{{ route('register') }}">here</a>.</br>
                        </p>

                        <a class="btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>