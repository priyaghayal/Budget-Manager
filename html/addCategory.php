<?php
    require_once "dbconnect.php";
    session_start();
    $email = $_SESSION['email'];
    $category = $contribution = '';
    //SELECT * FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email like 'abc@gmail.com') AND category = 'Bills';
    //INSERT INTO user_plan(uid , category ,contribution) VALUES ((SELECT uid FROM user_info  WHERE email  like 'abc@gmail.com'),'A',10);
    if(isset($_POST['add'])) {
        $category = $_POST['category'];
        $contribution = $_POST['contribution'];
        $sql = "SELECT * FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') AND category = '$category'";	
		    $query = mysqli_query($conn,$sql);
        $count = mysqli_num_rows($query);
        
        if($count != 1 ) {
          $sql1 = "INSERT INTO user_plan(uid , category ,contribution) VALUES ((SELECT uid FROM user_info  WHERE email = '$email'),'$category',$contribution)";	
          $stmt1 = $conn->prepare($sql1);
          $stmt1->execute();
          echo "<script>alert('Added successfully!!');location.replace('customplan.php')</script>";
          
        }
        else {
          echo "<script>alert('Category exists')</script>";
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

 
    <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="../js/jsScript.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css.map">


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Pie chart base plan 1 -->
    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartUser);

        // Draw the chart and set the chart values
        function drawChartUser() {
            var data = google.visualization.arrayToDataTable([  
                          ['category', 'contribution'],  
                          <?php  
                           $query = "SELECT  category,sum(contribution) FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email ='$email') GROUP BY category;";  
                           $result = mysqli_query($conn, $query);
                           while($row = mysqli_fetch_array($result))  
                           {  
                                echo "['".$row["category"]."', ".$row["sum(contribution)"]."],";  
                           }  
                           
                          ?>  
                     ]);  

        // Optional; add a title and set the width and height of the chart
        var options = {
            'title':'Your base plan', 
            'width':550, 
            'height':400,
            
        };

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart_user'));
        
        chart.draw(data, options);
        }
        
        
    </script>


</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark">
        <a class="navbar-brand" href="#">
          <img src="../images/logo.png" alt="BudgetBuddy">BudgetBuddy</a> 
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="btn-group ml-auto">
                <div class="dropdown">
                    <button class="btn">
                    <img  class="rounded-circle" src="../images/user.svg"  style="width: 40px; height: 40px; "></button>
                    <?php
                        require_once 'dbconnect.php';       //session_start();
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        $email = $_SESSION['email'];
                        $sql = "SELECT username,email FROM user_info where email = '$email';";
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
                    <div class="dropdown-content">
                        <a href="editprofile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
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
                    Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="customplan.php">
                    <span><img src="../images/sliders.svg" alt="" class="feather"></span>
                    Customize Plan
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="suggestion.php">
                    <span><img src="../images/message-square.svg" alt="" class="feather"></span>
                    Suggestions
                  </a>
                </li>
		<li>
                  <a class="nav-link "href="editprofile.php">
                    <span><img src="../images/user.svg" alt="" class="feather"></span>
                      Profile
                  </a>
                </li>
	</ul>
            </div>
        </nav>

    
      <div class="sidep">
        <form action="addCategory.php" method = "post">
            <div class="form-group">
                <label>Category</label>
                <input type="text" class="form-control" id="category" name = "category" placeholder="Enter category to add">
            </div>
            <div class="form-group">
                <label>Contribution</label>
                <input type="text" class="form-control" id="contribution" name = "contribution" placeholder="Enter contribution">
            </div>
            <div class="form-group">
            <input type="submit" class="btn btn-success" value = "Add" name ="add">
            </div>
        </form>
      </div>
      <div class="sidep">
        <div class="piechart">
          <div class="piechart_user"></div>
        </div>
      </div>
    </div>
</div>
</body>
</html>
