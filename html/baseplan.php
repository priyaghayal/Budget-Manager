<?php
    require_once "dbconnect.php";
    session_start();
    $email = $_SESSION['email'];
    //update user_info set income = 100 where email like 'pqr@gmail.com';

    if(isset($_POST['income_btn'])) {
        $income = $_POST['income'];
        $sql = "UPDATE user_info SET income = $income WHERE email = '$email';";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $value = 'N';
        $first_login_sql = "UPDATE user_info SET first_login ='$value' WHERE email = '$email'";
        $stmt1 = $conn->prepare($first_login_sql);
        $stmt1->execute();

        $sql_plan = "INSERT INTO user_plan(uid) SELECT uid FROM  user_info WHERE email = '$email'";
        $stmt_plan = $conn->prepare($sql_plan);
        $stmt_plan->execute();

        $sql_bp0 = "INSERT INTO user_plan(uid , category , contribution) 
        SELECT user_plan.uid , bp0.category , bp0.contribution FROM bp0,user_plan WHERE 
        user_plan.uid = (SELECT uid FROM user_info WHERE email = '$email');";	
        $stmt_bp0 = $conn->prepare($sql_bp0);
        $stmt_bp0->execute();

        $null_entry_sql = "DELETE FROM user_plan WHERE category IS NULL AND uid = (SELECT uid FROM user_info WHERE email = '$email');";
        $stmt2 = $conn->prepare($null_entry_sql);
        $stmt2->execute();

        header("location:customplan.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Base Plan</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"> 
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script type = "text/javascript" src= "../js/jsScript.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Pie chart base plan 1 -->
    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartBP1);
        google.charts.setOnLoadCallback(drawChartBP2);

        // Draw the chart and set the chart values
        function drawChartBP1() {
            var data = google.visualization.arrayToDataTable([  
                          ['category', 'contribution'],  
                          <?php  
                           $query = "SELECT  category,sum(contribution) FROM bp1 GROUP BY category";  
                           $result = mysqli_query($conn, $query);
                           while($row = mysqli_fetch_array($result))  
                           {  
                                echo "['".$row["category"]."', ".$row["sum(contribution)"]."],";  
                           }  
                           
                          ?>  
                     ]);  

        // Optional; add a title and set the width and height of the chart
        var options = {
            'title':'Base Plan 1', 
            'width':550, 
            'height':400,
            
        };

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart_bp1'));
        
        chart.draw(data, options);
        }
        function drawChartBP2() {
            var data = google.visualization.arrayToDataTable([  
                          ['category', 'contribution'],  
                          <?php  
                           $query = "SELECT  category,sum(contribution) FROM bp2 GROUP BY category";  
                           $result = mysqli_query($conn, $query);
                           while($row = mysqli_fetch_array($result))  
                           {  
                                echo "['".$row["category"]."', ".$row["sum(contribution)"]."],";  
                           }  
                           
                          ?>  
                     ]);  

        // Optional; add a title and set the width and height of the chart
        var options = {
            'title':'Base Plan 2', 
            'width':550, 
            'height':400,
            
        };

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart_bp2'));
        
        chart.draw(data, options);
        }
        
    </script> 

</head>
<body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../images/logo.png" alt="BudgetBuddy">
                BudgetBuddy
            </a>
        </div>
    </nav>
   
    
    <header>
            <ul class="nav nav-pills justify-content-center" >
                <li class="nav-item">
                  <a class="nav-link active" href="baseplan.php">Base Plan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="customplan.php">Custom Plan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="suggestions.php">Suggestions</a>
                </li>           
            </ul>
    </header>
        
 

    
    <h3 class="">Select your base plan</h3>    
    
     <!--Pie Chart-->
    <div class="piechart"> 
        <form action="baseplan.php" method = "post">
            <div class="form-group">
                <label for="income"><h4> Create your own plan &nbsp &nbsp</h4></label>
                <input type="number" class="form-control" name = "income" placeholder = "Enter monthly income">
            </div>
            <div class="form-group">
                <button class = "btn btn-success" type="submit" value="submit" name = "income_btn">Submit</button>
            </div>
        </form>
    </div>
    <div class="piechart">
        <h4>OR</h4>
    </div>
    <div class="piechart">
        <h4>Choose any sample plan</h4>
    </div>
    </div>
    <div class="piechart">
        <h4>Sample plan values are in (%)</h4>
    </div> 
    <div class="piechart">
        <h4>Sample plan will be adjusted according to your income later.</h4>
            
    </div>
    <div class="piechart">
        
        <h4>Sample plan 1</h4>
        <div id="piechart_bp1"></div> 

        <h4>Sample plan 2</h4>
        <div id="piechart_bp2"></div>
  
</body>
</html>
