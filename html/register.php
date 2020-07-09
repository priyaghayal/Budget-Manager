<?php
    require_once "dbconnect.php";
    
    $username = $password = $email = "";

    if(isset($_POST['reg_btn'])) {
        $sql = "SELECT uid FROM user_info WHERE username = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    echo "<script>alert('This username is already taken.')</script>";
                } 
                else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
          
        }


    // Validate password
        //$username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
		
    // Check input errors before inserting in database
    if(!empty($username)  && !empty($email) && !empty($password) ){

        // Prepare an insert statement
        $sql = "INSERT INTO user_info (username , email,password) VALUES (?,?,?)";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email,$param_password);
          // Set parameters
                $param_username = $username;
                $param_email = $email;
		        $param_password = md5($password); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                echo "<script>alert('You are registered successfully!! Now Login');location.replace('index.php')</script>";
                //header("location: index.php");
                
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
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

    <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript" src="../js/jsScript.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" >
    <script type="text/javascript" src="../bootstrap/js/jquery-3.3.1.js"></script>
    
</head>
<body>
        <div class="navigation-bar">
            <nav class="navbar navbar-expand-lg  bg-light">
                <a class="navbar-brand" href="#">
                <img src="../images/logo.png" alt="BudgetBuddy">
                BudgetBuddy
                </a>
            </nav>
        </div>

<h3>Sign Up For Free!!!</h3>
<div class="main-content">

    <!--Registration form-->
    <form name="register-form" id="register-form" method = "post" action = "register.php" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="InputName">Username</label>
            <input type="text" class="form-control" id="Name" placeholder="User Name" name = "username" required>
        </div>

        <div class="form-group">
            <label>Email address</label>
            <input type="email" class="form-control" id="Email1" name = "email" placeholder="Email" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" id="Password1" name = "password" placeholder="Password" name="Password1" required>
        </div>

        <div class="form-group">
            <label>Re-Password</label>
            <input type="password" class="form-control" id="Password2" placeholder="Re-Password" name="Password2" required>
        </div>

        <div class="checkbox form-group">
            <label>
                <input type="checkbox" required> I agree with all terms and conditions.
            </label>
        </div>

        <input type = "submit" class="btn btn-primary" value = "Register" name = "reg_btn">
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>        
        
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>