<?php
    require_once "dbconnect.php";
    session_start();
    $email = $_SESSION['email'];

    if(isset($_POST['record_btn'])) {
      header("location:viewRecords.php");
    }

    if(isset($_POST['custom_btn'])) {
      header("location:customplan.php");
    }

    if(isset($_POST['income_bp_user'])) {
      $income_bp_user = $_POST['income'];
      $sql_income_bp_user = "UPDATE user_info SET income =$income_bp_user WHERE email = '$email'";
      $query_income_bp_user = mysqli_query($conn,$sql_income_bp_user);
      header("location:dashboard.php");
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
    
</head>
<body>
  
 <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
     <a class="navbar-brand" href="#">
      <img src="../images/logo.png" alt="BudgetBuddy">
         BudgetBuddy
     </a>
     
     <button class="navbar-toggler " type="btn btn-dark" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
     </button>
     
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
          <nav class="navbar col-md-2 d-md-block sidebar bg-light">
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
                  <a class="nav-link" href="viewRecords.php">
                    <span><img src="../images/message-square.svg" alt="" class="feather"></span>
                    View Records
                  </a>
                </li>
              </ul>
        </div>
        </nav>
        <!--PIECHART SPACE-->
        <div class="sidep">
        <h3 class="">Your base plan</h3>    
          <!--Pie Chart-->
            <div class="piechart">   
                <div id="piechart_user" ></div>
            </div>
            <div class="sidep">
              <!--
              <h3>Click here to see all previous records</h3> -->
              <form  method = "post">
              <button id = "btn_records" value = "record_btn" type = "submit" class = "btn btn-success" name = "record_btn">View All Records</button>
              </form>
            </div>
            <div class="sidep">
              <!--
              <h3>Do you want to customize your plan?</h3> -->
              <form action = "dashboard.php" method = "post">
              <button value = "custom_btn" type = "submit" class = "btn btn-success" name = "custom_btn">Customize Plan</button>
              </form>
            </div>
           
        </div> <!--div sidep containing piechart ends here-->
        
        <div class="sidep">
          <!--CURRENT SAVINGS TABLE-->
          <table class = "table table-striped">
          <thead class="thead-dark">
          <th>Current Savings(In Rs)</th>
          <th>Date</th>
          </thead>
            <?php
                //select uid,sum(contribution) from user_plan where uid = (select uid from user_info where email like 'xyz@gmail.com') group by uid;
                require_once "dbconnect.php";
                session_start();
                $email = $_SESSION['email'];
                
                //To avoid duplicate entry for savings table in database, UNIQUE condition is given to savings column
                $sql_spent = "SELECT uid,SUM(contribution) AS spent FROM user_plan WHERE category NOT IN ('Savings') AND  uid = (SELECT uid FROM user_info WHERE email = '$email') GROUP BY uid";
                $query_spent = mysqli_query($conn,$sql_spent);
                $result_spent = mysqli_fetch_array($query_spent);
                $uid = $result_spent['uid'];
                $spent =(float)$result_spent["spent"];
              
                //CALCULATION PART
                $sql_income = "SELECT income FROM user_info WHERE email = '$email'";
                $query_income = mysqli_query($conn,$sql_income);
                $result_income = mysqli_fetch_array($query_income);
                if($result_income['income'] != null) {
                $income = floatval($result_income['income']);

                $total = $income - $spent;

                $percent_savings = ($total/$income)*100;

                //INSERTING SAVINGS TO DATABASE
                $date = date("Y/m/d");
                $sql_savings = "INSERT INTO user_record VALUES ($uid,$total,'$date')";
                $query_savings = mysqli_query($conn,$sql_savings);

                //UPDATE user_plan SET contribution = 70 WHERE category LIKE 'Savings' AND uid = (SELECT uid FROM user_info WHERE email = 'pqr@gmail.com')
                
                $sql_insert_savings = "UPDATE user_plan SET contribution = $total 
                                      WHERE category LIKE 'Savings' AND uid = $uid
                                      ";
                $query_insert_savings = mysqli_query($conn,$sql_insert_savings);

                echo "<tr><td>".$total."</td><td>". $date."</td></tr>";
                echo "</table>";
                echo"<h4>Current Savings are ".$percent_savings." % of your total income</h4>";
              }
              else {
                echo"<h4>To view your savings, please enter income</h4>";
                echo"<form method = 'post'>";
                echo"<input type = 'number' placeholder = 'Enter your income: ' name = 'income'>";
                echo"<button value = 'income_bp_user' type = 'submit' class = 'btn btn-success' name = 'income_bp_user'>Add Income</button>";
                echo"</form>";
              }
                //PS - Run either ABOVE or OPTIONAL snippet
                /*
                //OPTIONAL: CODE FOR DISPLAYING EXPENDITURE 
                $sql_record = "INSERT INTO user_record (select uid,sum(contribution) from user_plan where uid = (select uid from user_info where email = '$email') group by uid) ";
                $query_record = mysqli_query($conn,$sql_record);

                $sql_savings_display = "SELECT savings FROM user_record WHERE uid = (SELECT uid FROM user_info WHERE email = '$email')";
                $query_display = mysqli_query($conn,$sql_savings_display);
                $result_display = mysqli_fetch_array($query_display);

                echo "<tr><td>".$result_display['savings']."</td></tr>";
                */     
            ?>
          <!--CURRENT PLAN TABLE-->  
          <table class = "table table-striped">
          <thead class="thead-dark">
              <th>Category</th>
              <th>Contribution(in Rs)</th>
          </thead>
          <tbody class = "table-hover">
          <?php
            require_once 'dbconnect.php';
            session_start();
            
            $email = $_SESSION['email'];
            $sql = "SELECT category,contribution FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') ";
            $result = mysqli_query($conn,$sql);
            if (mysqli_num_rows($result)>0) {
              
              while($row = mysqli_fetch_assoc($result)) {
                //if($row['category'] == 'Savings')
                  //break;
                //$float = (float)row['contribution'];
                echo "<tr><td>".$row['category']."</td>
                      <td>".floatval($row['contribution'])."</td></tr>";
                
              }
            }
            else {
              echo "We were not able to display your plan. Kindly select a baseplan of your choice through the customize plan option";
            }
          ?>
          </tbody>
          </table>          

        </div>
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
              'title':'Current plan', 
              'width':550, 
              'height':400,
              
          };

          // Display the chart inside the <div> element with id="piechart"
          var chart = new google.visualization.PieChart(document.getElementById('piechart_user'));
          
          chart.draw(data, options);
          }
          
        
        </script>


          <br><br><br>
        </div>
    </div>
</div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
</body>
</html>
