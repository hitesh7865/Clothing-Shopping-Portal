<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Register</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="/new-css/css/opensans-font.css">
	<link rel="stylesheet" type="text/css" href="/new-css/fonts/line-awesome/css/line-awesome.min.css">
	<!-- Jquery -->
	<link rel="stylesheet" href="/new-css/https://jqueryvalidation.org/files/demo/site-demos.css">
	<!-- Main Style Css -->
    <link rel="stylesheet" href="/new-css/css/style.css"/>
</head>
<body class="form-v7">
	<div class="page-content">
		<div class="form-v7-content">
			<div class="form-left">
				<img src="/new-css/images/form-v7.jpg" alt="form">
				<p class="text-1">Sign Up</p>
			</div>
			<form class="form-detail" action="{{URL::to('/Rdata')}}" method="post" id="myform">
			@csrf
				<div class="form-row">
					<label for="Firstname">FIRSTNAME</label>
					<input type="text" name="firstname" id="firstname" class="input-text">
				</div>
				<div class="form-row">
					<label for="lastname">LASTNAME</label>
					<input type="text" name="lastname" id="lastname" class="input-text">
				</div>
				<div class="form-row">
					<label for="username">USERNAME</label>
					<input type="text" name="username" id="username" class="input-text">
				</div>
				<div class="form-row">
					<label for="your_email">E-MAIL</label>
					<input type="text" name="email" id="your_email" class="input-text" required pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}">
				</div>
				<div class="form-row">
					<label for="password">PASSWORD</label>
					<input type="password" name="password" id="password" class="input-text" required>
					<span id="pw"></span>
				</div>
				<div class="form-row">
					<label for="comfirm_password">CONFIRM PASSWORD</label>
					<input type="password" name="comfirm_password" id="comfirm_password" class="input-text" required>
					<span id="cpw"></span>
				</div>

				<div class="form-row">
					<label for="Address">Address</label>
					<input type="text" name="Address" id="Address" class="input-text" required>
				</div>

				<div class="form-row">
					<label for="Zip Code">ZipCode</label>
					<input type="text" name="Zip_code" id="Zip_code" class="input-text" required >
				</div>

				<div class="form-row">
					<label for="Mobile">Mobile</label>
					<input type="text" name="mobile" id="Mobile" class="input-text" required >
				</div>

				<div class="form-row-last">
					<input type="submit" id="btn" name="register" class="register" value="Register">
					<p>Or<a href="user-login">Log in</a></p>
				</div>
			</form>
		</div>
	</div>
	<script>
	document.querySelector('.register').onclick=function(){
		var password = document.querySelectbyid('#password').value,
			confirmpassword = document.querySelectbyid('#comfirm_password').value;

			if(password != confirmpassword){
				alert("Password Didn't Match");
				return false;
			}
			return true
	}
	</script>
	<script src="{{asset('/new-css/https://code.jquery.com/jquery-1.11.1.min.js')}}"></script>
	<script src="{{asset('/new-css/https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js')}}"></script>
	<script src="{{asset('/new-css/https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js')}}"></script>
	<script>
		// just for the demos, avoids form submit
		jQuery.validator.setDefaults({
		  	debug: true,
		  	success:  function(label){
        		label.attr('id', 'valid');
   		 	},
		});
		$( "#myform" ).validate({
		  	rules: {
			    your_email: {
			      	required: true,
			      	email: true
			    },
			    password: "required",
		    	comfirm_password: {
		      		equalTo: "#password"
		    	}
		  	},
		  	messages: {
		  		username: {
		  			required: "Please enter an username"
		  		},
		  		your_email: {
		  			required: "Please provide an email"
		  		},
		  		password: {
	  				required: "Please provide a password"
		  		},
		  		comfirm_password: {
		  			required: "Please provide a password",
		      		equalTo: "Wrong Password"
		    	}
		  	}
		});
	</script>
</body>
</html>