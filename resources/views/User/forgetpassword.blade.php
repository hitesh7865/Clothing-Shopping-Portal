<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password</title>
        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets1/bootstrap/css/bootstrap.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="assets1/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets1/css/form-elements.css">
        <link rel="stylesheet" href="assets1/css/style.css">
    </head>
    <body onLoad='fire()'>
        <!-- Top content -->
        <div class="top-content">
            <div class="inner-bg">
                <div class="container">
                	
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1>Forgot Password</h1>                            
                        </div>
                    </div>
                        <div class="col-sm-6 col-sm-offset-3 text">                        	
                        	<div class="form-box">
	                        	<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Forgot Password</h3>
	                            		<p>Enter email address to get password:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-lock"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
				                    <form role="form" action="forget" method="post" class="login-form">
                                        <div class="form-group">
				                    		<label class="sr-only" for="form-username">Username</label>
				                        	<input type="text" name="email" placeholder="Enter Email..." class="form-username form-control" id="form-username" require>
				                        </div>				                        
                                        @csrf
                                            <button type="submit" class="btn col-sm-5">Submit</button>
                                            <br><br>
                                            <a class="btn btn-primary" href="user-login">Log In</a>
                                            <a class="btn btn-primary" href="user-register" style="float:right;">Register New Account</a>
				                    </form>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div> 
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">
        </script>     
        <script type="text/javascript">
        function fire()
        {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email is not In our server!'
            });
        }
        </script> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
        <script src="assets1/js/jquery-1.11.1.min.js"></script>
        <script src="assets1/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets1/js/jquery.backstretch.min.js"></script>
        <script src="assets1/js/scripts.js"></script>        
    </body>
</html>