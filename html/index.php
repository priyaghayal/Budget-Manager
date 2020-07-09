<?php
	require_once 'dbconnect.php';
	session_start();
	
	$email = $password = "";
	
	if(isset($_POST['login_btn'])) {
		$email = trim($_POST['email']);
		$password = md5(trim($_POST['password']));
		
		if(!$email == '' && !$password == '') {
			
			$sql = "SELECT * FROM user_info WHERE  email = '$email' AND password = '$password'";
			
			$query = mysqli_query($conn,$sql);
			$count = mysqli_num_rows($query);
			
			if($count == 1) {
                $sql1 = "SELECT first_login FROM user_info where email = '$email'";
                $result = mysqli_query($conn,$sql1);
                
                $value = mysqli_fetch_assoc($result);
                
			
                if($value['first_login'] == 'N') {
                $_SESSION['email'] = $email;
                header("location:dashboard.php");
                }      
                else {
                    $_SESSION['email'] = $email;
                    header("location:baseplan.php"); 
                }          
			}
			else {
				echo "<script>alert('E-mail or password is incorrect')</script>";
			}
			
				
		}
		else {
			echo "<script>alert('Please enter E-mail and password')</script>";
		}
	
	}
	else {
	
	}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetBuddy</title>
    <link rel='shortcut icon' href='../images/logo.png' type='image/x-icon' />

    <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/script.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" >
    <script type="text/json" src="../bootstrap/jquery/jquery-3.5.1.min.map"></script>
    
</head>
<body>
    <div id="home">
            <!--Navigation Bar-->
            
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <!--Logo -->
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="../images/logo.png" alt="BudgetBuddy">
                        BudgetBuddy
                    </a>

                    <button class="navbar-toggler" type="btn btn-dark" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>

                    <!--Tabs -->
                    <div class="collapse navbar-collapse justify-content-end" id="navbarTogglerDemo01">
                        <ul class="navbar nav ">
                            <li class="nav-item active">
                            <a class="nav-link" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#about">About Us</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                            </li>
                            <li class="nav-item">
                            <button type="button" class="btn btn-outline-primary mr-sm-2" data-toggle="modal" data-target="#exampleModalCenter">Login</button>
                            </li>
                            <li class="nav-item">
                            <button class="btn btn-outline-primary" onclick="window.location.href = 'register.php';">Register</button>
                            </li>                
                        </ul>
                            
                    </div>
                </div>
            </nav>

    <!--Jumbotron for heading-->
    <div class="jumbotron">
        <div class="container-jumbotron">
            <h1 class="display-4">BudgetBuddy</h1>
            <p class="lead">This is web based application which can help you with your track your spendings
                <br>Let's Get Started!!!
            </p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                Login Now
              </button>

              
    <!-- Modal section-->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Login </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <!--Modal Form-->
                            <form method = "post" action = "index.php" id="login-form" name="login-form">
                                <div class="form-group">
                                    <label for="InputEmail">Email address</label>
                                    <input type="email" placeholder="Enter email" class="form-control" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="InputPassword">Password</label>
                                    <input type="password" placeholder="Password" class="form-control" name="password">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberme" checked>
                                    <label class="form-check-label" for="Check">Remember Me?</label>
                                </div>
                                <div class="form-group">
                                <input type = "submit" name = "login_btn" class="btn btn-primary" value = "Login">
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
        </div>
    </div>
        
    </div>

    <!--About Us -->
    <div id="about">
        <h1><b>About Us</b></h1>
    </div>

    <div class="no-padding">

        
        <div id="myCarousel" class="carousel slide" data-ride="carousel1">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>
      
          <!-- Wrapper for slides -->
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="../images/a.jpg" alt="savings" id="carousel-img">
              <div class="carousel-caption">
                <h3>HAVING TROUBLE MANAGING YOUR INCOME AND EXPENSES ? YOU CAME TO THE RIGHT PERSON</h3>
                <p>Our team focuses on maintaining a balance between your income and expenses</p>
              </div>
            </div>
      
            <div class="carousel-item">
              <img src="../images/b.jpg" alt="budget" id="carousel-img">
              <div class="carousel-caption">
                <h3>WE CAN INCREASE YOUR SAVINGS LIKE ANYTHING</h3>
                <p>We use various investment options and trading strategies to increase your savings</p>
              </div>
            </div>
          
            <div class="carousel-item">
              <img src="../images/c.jpg" alt="expenditure" id="carousel-img">
              <div class="carousel-caption">
                <h3>WE CAN ALSO HELP YOU PLAN YOUR BUDGET</h3>
                <p>You can leave tedious budget calculations to us</p>
              </div>
            </div>
          </div>
      
          <!-- Left and right controls -->
          <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#myCarousel" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>

    <!-- Contact -->
    <div id="contact">
        <h1><b>Contact Us</b></h1>
    </div>

    <div class="contact-form">

        <form name="contact-form" action="/action_page.php" target="_blank">
            <div class="form-group">
                <label for="InputName">Full Name *</label>
                <input type="text" class="form-control" id="Name" placeholder="Enter your name" name = "username" required>
            </div>
    
            <div class="form-group">
                <label>Email address *</label>
                <input type="email" class="form-control" id="Email1" name = "email" placeholder="Enter email" required>
            </div>
            
            <div class="form-group">
                <label>Subject</label>
                <input type="text" class="form-control" name="Subject" placeholder="Enter subject" required>
            </div>
    
            <div class="form-group">
                <label>Message</label>
                <textarea type="text" class="form-control" id="Message" placeholder="Enter Your Message here..." rows="3" required></textarea>
            </div>
    
            <input type = "submit" class="btn btn-dark" value = "Send" name = "send_btn" id="send">
        </form>
    </div>  
	
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <span class="text-muted"></span>
                <h4>BudgetBuddy</h4>  
                <div>
                    <img src="../images/facebook.svg" alt="facebook" class="feather">
                    <img src="../images/instagram.svg" alt="instagram" class="feather">
                    <img src="../images/linkedin.svg" alt="linkedin" class="feather">
                    <img src="../images/twitter.svg" alt="twitter" class="feather">
                </div>
                <div>
                    <button class="btn btn-dark" onclick="window.location.href = '#home';">
                        <img src="../images/arrow-up.svg" alt="up" class="feather">
                    </button>
                </div>      
        </span>
        </div>
      </footer>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
