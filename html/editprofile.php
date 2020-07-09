<?php
    require_once 'dbconnect.php';
    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Budget Buddy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    
    <title>Profile</title>

    <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" >
    <script type="text/json" src="../bootstrap/jquery/jquery-3.5.1.min.map"></script>
    <script type="text/javascript" src="../js/jsScript.js"></script>


</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark">
        <a class="navbar-brand" href="#">
            <img src="../images/logo.png" alt="BudgetBuddy">BudgetBuddy</a> 
        <div class="ml-auto">
           <div class="dropdown" >
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <img  class="rounded-circle" src="../images/user.svg"  style="width: 40px; height: 40px; ">
     <?php
                        require_once 'dbconnect.php';
                        //session_start();
                        if (session_status() == PHP_SESSION_NONE) {
                                 session_start();
                                   }
                        $email = $_SESSION['email'];
                        $sql = "SELECT username FROM user_info where email = '$email';";
                        $result = mysqli_query($conn,$sql);

                        if (mysqli_num_rows($result)>0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                
                                echo "<br><font color='white'><b>".$row['username']."</font></b>";
                                
                            }
                        }
                        else{
                            echo "No such result";
                        } 
                    ?>
    
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
    <a class="dropdown-item" href=editprofile.php">Profile</a>
    <a class="dropdown-item" href="logout.php">Logout</a>
  </div>
</div>
   </div> 
       
    </nav> 

    <div class="container-fluid">
        <div class="row">
            <nav class="navbar col-md-2 d-none d-md-block sidebar bg-light">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <span><img src="../images/pie-chart.svg" alt="" class="feather"></span>
                                Dashboard</a>
                        </li>
                
                        <li class="nav-item">
                            <a class="nav-link" href="customplan.php">
                                <span><img src="../images/sliders.svg" alt="" class="feather"></span>
                                Customize Plan</a>
                            </li>

                        <li class="nav-item">
                            <a class="nav-link" href="suggestion.php">
                                <span><img src="../images/message-square.svg" alt="" class="feather"></span>
                                Suggestions</a>
                        </li>

                        <li>
                            <a class="nav-link "href="editprofile.php">
                                <span><img src="../images/user.svg" alt="" class="feather"></span>
                            Profile
                            </a>
                        </li>

                        <li>
                            <a class="nav-link" href="logout.php">
                                <span><img src="../images/log-out.svg" alt="" class="feather"></span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Contact card-->
            <div class="sidep">
                <div class = "card">
                    <img class="card-img-top" src="../images/user.svg" alt="Card Image">
                    <hr>
                    <div class="card-body">
                        <?php
                            require_once 'dbconnect.php';
                            session_start();
                            $email = $_SESSION['email'];
                            $sql = "SELECT username,email FROM user_info where email = '$email';";
                            $result = mysqli_query($conn,$sql);

                            if (mysqli_num_rows($result)>0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "Hello, ".$row['username']."<br><br>";
                                    echo "Email id : ".$row['email']."<br>";
                                }
                            }
                            else{
                                echo "No such result";
                            }
                        ?>
                    </div>
                    <hr>
                    <div class="card-button">   
                        <button class="btn btn-info" value="sumbit" name="edit_btn" formtarget="_parent"  id="btn_spacing" >Edit</button>
                        <button class="btn btn-info" value="submit" name="logout_btn" onclick="window.location.href = 'logout.php'">Logout</button>
                    </div>
              </div>
            </div>


            <!--Edit Profile-->
            <div class="sidep" id="edit_profile" style= "display:none">
                <table class="table">
                <thead class="thead-light"><tr><th>Edit your profile </th></tr></thead>
                
                <tr>
                <form name="edit_username" id="edit_username" method = "post" action = "editprofile.php">
                <td><label>Username : </label></td>
                <td><input type="text" name="username"> </td>
                <td><button class="btn btn-success">Update</button><br><br></td>
                </form>
                </tr>
                <tr>
                <form name="edit_email" id="edit_email" method = "post" action = "editprofile.php">
                    <td><label>Email Id :</label></td>
                     <td><input type="Email" name="email"></td>
                     <td><button class="btn btn-success">Update</button><br><br></td>
                </form>
                </tr>
                <tr>
                <form name="edit_password" id="edit_password" method = "post" action = "editprofile.php">
                   <td> <label>Current Password : </label></td>
                    <td><input type="password" name="cp"></td></tr>
                    <tr>
                    <td><label>New Password : </label></td>
                    <td><input type="password" name="np"></td></tr>
                    <tr><td><label>Retype New Password : </label></td>
                     <td><input type="password" name="np1"></td>
                    <td><button class="btn btn-success">Update</button></td>
                </form>
                </tr>
                <?php 

                    if (isset($_POST['username'])) {
                        $username = $_POST['username'];
                        $email = $_SESSION['email'];
                        $sql = "UPDATE user_info SET username = '$username' where email = '$email'";
                        $result = mysqli_query($conn,$sql);
                        echo "<meta http-equiv='refresh' content='0' url='editprofile.php'> Username updated successfully";
                    }

                    if (isset($_POST['email'])) {
                        $mailid = $_POST['email'];
                        $email = $_SESSION['email'];
                        $sql = "UPDATE user_info SET email = '$mailid' where email = '$email'";
                        $result = mysqli_query($conn,$sql);
                        $_SESSION['email'] = $mailid;
                        echo "<meta http-equiv='refresh' content='0' url='editprofile.php'>";
                    } 

                    if (isset($_POST['np']) && $_POST['cp']) {
                        $pwd = trim($_POST['cp']);
                        $pwd1 = trim($_POST['np']);
                        $hash_pwd = md5($pwd1);
                        $pwd2 = trim($_POST['np1']);
                        $email = $_SESSION['email'];
                        $sql = "SELECT password from user_info where email = '$email'";
                        $query = mysqli_query($conn,$sql);
                        $result = mysqli_fetch_array($query);
                        if (md5($pwd)== $result['password']) {
                            if ($pwd1 == $pwd2) {
                                $sql1 = "UPDATE user_info SET password = '$hash_pwd' where email = '$email'";
                                $result = mysqli_query($conn,$sql1);
                                echo "<meta http-equiv='refresh' content='0' url='editprofile.php'>";  
                            }
                            else{
                                echo "Please check your password !!!";
                            }
                        }
                        else{
                            echo "Please check your password !!!";   
                        }
                    }
                ?>   
                </table>    
            </div>
            
        </div>
    </div>

</body>
</html>
