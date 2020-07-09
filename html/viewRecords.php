<?php
    require_once "dbconnect.php";
    session_start();
    $email = $_SESSION['email'];


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
            'title':'Current plan', 
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
 <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
     <a class="navbar-brand" href="#">
      <img src="../images/logo.png" alt="BudgetBuddy">
     BudgetBuddy
     </a>
         
     <button class="navbar-toggler " type="btn btn-dark" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
     </button>
     
     <div class="collapse navbar-collapse justify-content-end" id="navbarTogglerDemo01">
         <div class="btn-group ml-auto">
             <div class="dropdown">
             <button class="btn">
             <img class="rounded-circle" src="../images/user.svg" style="width: 40px; height: 40px; "></button>
             <?php
             require_once 'dbconnect.php'; //session_start();
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
                  <a class="nav-link " href="dashboard.php">
                    <span><img src="../images/pie-chart.svg" alt="" class="feather"></span>
                    Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="customplan.php">
                    <span><img src="../images/sliders.svg" alt="" class="feather"></span>
                    Customize Plan
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="ViewRecords.php">
                    <span><img src="../images/message-square.svg" alt="" class="feather"></span>
                    View Records
                  </a>
                </li>
              </ul> 
        </div>
        </nav>
        <!--PIECHART SPACE-->
        <div class="sidep">
        <h3 class="">Current plan</h3>    
          <!--Pie Chart-->
            <div class="piechart">   
                <div id="piechart_user" ></div>
            </div>
            <table class = "table table-stripped table table-bordered">
              <thead>
                <form method = "post">
                  <th>
                    <input type = "text" name = "searchBalance" placeholder = "Enter Balance">  
                  </th>
                  <th>
                    <input type = "text" name = "searchDate" placeholder = "Enter Date (YYYY-MM-DD)">  
                  </th>
                  <th>
                    <button class = "btn btn-info" type = "submit" name = "search_btn" >Search</button>  
                  </th>
                </form>
                <th>
                  <form method  = "post">
                    <button class = "btn btn-info" type = "submit" name = "date_btn_rf">Sort by date(recent first)</button>
                    <button class = "btn btn-info" type = "submit" name = "date_btn_rl">Sort by date(recent last)</button>
                  </form>
                </th>
              </thead>
              <?php
                  require_once "dbconnect.php";
                  session_start();
                  $email = $_SESSION['email'];
                  
                  if(isset($_POST['search_btn'])) {
                    echo"<tr><td><h5>Search Results</h5></td></tr>";

                    $balance = floatval($_POST['searchBalance']);
                    $date = $_POST['searchDate'];
                
                    if(!empty($balance) && !empty($date)) {
                      $sql_bal_date = "SELECT savings,till_date FROM user_record 
                                      WHERE savings = $balance AND till_date ='$date' 
                                      AND uid = (SELECT uid FROM user_info WHERE email = '$email')";
                      $query_bal_date = mysqli_query($conn,$sql_bal_date);
                      if(mysqli_num_rows($query_bal_date)>0) {   
                        while ($row = mysqli_fetch_array($query_bal_date) ) {
                          echo"<tr><td>".$row['savings']."</td><td>".$row['till_date']."</td></tr>";
                        }
                      }
                      else {
                        echo"<tr><td>No record found for the entry --</td>
                        <td>Balance ".$balance."Date ".$date."</td></tr>";
                      }
                                        
                    }
                    
                    if(!empty($balance) && empty($date)) {
                      $sql_bal = "SELECT savings,till_date FROM user_record 
                                  WHERE savings = $balance 
                                  AND uid = (SELECT uid FROM user_info WHERE email = '$email') ";
                      $query_bal = mysqli_query($conn,$sql_bal);
                      if(mysqli_num_rows($query_bal)>0) { 
                        while($row = mysqli_fetch_array($query_bal)) {
                          echo"<tr><td>".$row['savings']."</td><td>".$row['till_date']."</td></tr>";
                        }
                      }
                      else {
                        echo"<tr><td>No record found for the entry --</td>
                        <td>Balance ".$balance."</td></tr>";
                      }
                    }

                    if(empty($balance) && !empty($date)) {
                      $sql_date = "SELECT savings,till_date FROM user_record 
                                  WHERE till_date = '$date' 
                                  AND uid = (SELECT uid FROM user_info WHERE email = '$email') ";
                      $query_date = mysqli_query($conn,$sql_date);
                      if(mysqli_num_rows($query_date)>0) { 
                        while($row = mysqli_fetch_array($query_date)) {
                          echo"<tr><td>".$row['savings']."</td><td>".$row['till_date']."</td></tr>";
                        }
                      }
                      else {
                        echo"<tr><td>No record found for the entry--</td>
                        <td>Date ".$date."</td></tr>";
                      }
                    }
                  }
                  //Sort by date - recent entry first
                  if(isset($_POST['date_btn_rf'])) {
                    echo"<tr><td><h5>Ordered by date:</h5></td><td><h5>Recent entry first</h5></td></tr>";
                    $sql_sort_rf = "SELECT savings,till_date FROM user_record 
                                    WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') 
                                    ORDER BY till_date DESC;";
                    $query_sort_rf = mysqli_query($conn,$sql_sort_rf);

                    if(mysqli_num_rows($query_sort_rf)>0) { 
                      while($row = mysqli_fetch_array($query_sort_rf)) {
                        echo"<tr><td>".$row['savings']."</td><td>".$row['till_date']."</td></tr>";
                      }
                    }
                    else {
                      echo"<tr><td><h6>You'll see your records here</h6></td></tr>";
                    }

                  }

                  //Sort by date - recent entry last
                  if(isset($_POST['date_btn_rl'])) {
                    echo"<tr><td><h5>Ordered by date:</h5></td><td><h5>Recent entry last</h5></td></tr>";
                    $sql_sort_rl = "SELECT savings,till_date FROM user_record 
                                    WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') 
                                    ORDER BY till_date ASC;";
                    $query_sort_rl = mysqli_query($conn,$sql_sort_rl);

                    if(mysqli_num_rows($query_sort_rl)>0) { 
                      while($row = mysqli_fetch_array($query_sort_rl)) {
                        echo"<tr><td>".$row['savings']."</td><td>".$row['till_date']."</td></tr>";
                      }
                    }
                    else {
                      
                      echo"<tr><td><h6>You'll see your records here</h6></td></tr>";
                    }

                  }
              
              ?>
            </table>
            <?php
              require_once "dbconnect.php";
              session_start();
              $email = $_SESSION['email'];
             
                $sql_all_records = "SELECT * FROM user_record WHERE uid = (SELECT uid from user_info WHERE email = '$email')";
                $query_all_records = mysqli_query($conn,$sql_all_records);
                
                if (mysqli_num_rows($query_all_records)>0) {
                  echo"<h5>&nbsp All records till Today</h5>";
                  echo "<table class = 'table table-striped'>
                  <thead class='thead-dark'>
                      <th>Balance(In Rs)</th>
                      <th>Till Date</th>
                  </thead>";
                  while($row = mysqli_fetch_array($query_all_records)) {
                      echo "<tr><td>".$row['savings']."</td>
                            <td>".$row['till_date']."</td></tr>";
                    }      
                    
                  }
                else {
                  echo "<h5>Welcome , You can view all your records here.</h5>";
                }   
            ?>
            </table>
           
        </div> <!--div sidep containing piechart ends here-->         
          <br><br><br>
        </div>
    </div>
</div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
</body>
</html>
