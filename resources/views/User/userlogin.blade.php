<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('/new-css/images/icons/favicon.ico')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/fonts/iconic/css/material-design-iconic-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('/new-css/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>
	
	
	<div class="container-login100" style="background-image: url('/new-css/images/bg-01.jpg');">
	@if(isset(Auth::user()->email))
		<script></script>
	@endif
	

	
		<div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
			<form class="login100-form validate-form" action="{{URL::to('/logins')}}" method="post">
			@csrf
				<span class="login100-form-title p-b-37">
					Log In
				</span>

				<div class="wrap-input100 validate-input m-b-20" data-validate="Enter username or email">
					<input class="input100" type="text" name="username" placeholder="Enter Email..." require>
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input class="input100" type="password" name="password" placeholder="Enter Password..." require>
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<button type="submit" class="login100-form-btn" name="loginbtn">
						Log In
					</button>
				</div>
				<div class="text-center p-t-57 p-b-20">
					<span class="txt1">
						Or
					</span>
					<br>
					<span>
						<a href="/forgetpassword" class="txt1">
							<strong>Forget Password</strong>
						</a>
					</span>
				</div>
				
				<div class="flex-c p-b-112">
					<a href="#" class="login100-social-item">
						<i class="fa fa-facebook-f"></i>
					</a>

					<a href="#" class="login100-social-item">
						<img src="/new-css/images/icons/icon-google.png" alt="GOOGLE">
					</a>
				</div>

				<div class="text-center">
					<a href="user-register" class="txt2 hov1">
						Register New Account
					</a>
				</div>
			</form>

			
		</div>
	</div>
	
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="/new-css/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="/new-css/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="/new-css/vendor/bootstrap/js/popper.js"></script>
	<script src="/new-css/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="/new-css/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="/new-css/vendor/daterangepicker/moment.min.js"></script>
	<script src="/new-css/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="/new-css/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="/new-css/js/main.js"></script>

</body>
</html>