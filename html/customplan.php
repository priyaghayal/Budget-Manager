
<?php
    require_once "dbconnect.php";
    session_start();
    $email = $_SESSION['email'];
    //UPDATE user_plan SET contribution = 8 WHERE category LIKE 'Groceries' AND uid = (SELECT uid FROM user_info WHERE email = 'abc@gmail.com') 
    //SELECT  category,sum(contribution) FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email like 'abc@gmail.com') GROUP BY category;

    if(isset($_POST['dashboard_btn'])) {
      header("location:dashboard.php");
    }

    if(isset($_POST['add_btn'])) {
        $addCategory = $_POST['addCategory'];
        $addContribution = $_POST['addContribution'];

        $sql_check = "SELECT * FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') AND category = '$addCategory'";	
        $query_check = mysqli_query($conn,$sql_check);
        $count = mysqli_num_rows($query_check);
        
        if($count != 1 ) {
        $sql_add = "INSERT INTO user_plan(uid , category ,contribution) VALUES ((SELECT uid FROM user_info  WHERE email = '$email'),'$addCategory',$addContribution)";	
        $stmt_add = $conn->prepare($sql_add);
        $stmt_add->execute();
        echo "<script>alert('Added successfully!!');location.replace('customplan.php')</script>";
        
        }
        else {
            echo "<script>alert('Category exists')</script>";
        }
    }
    if(isset($_POST['update_btn'])) {
        $updateCategory = $_POST['updateCategory'];
        $newContribution = floatval($_POST['newContribution']);
        
        
          $sql_check = "SELECT * FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') AND category = '$updateCategory'";	
          $query_check = mysqli_query($conn,$sql_check);
          $count = mysqli_num_rows($query_check);

          if($count == 1){
              $sql_update = "UPDATE user_plan SET contribution = $newContribution WHERE category ='$updateCategory' AND 
              uid = (SELECT uid FROM user_info WHERE email = '$email') ";
              $stmt = $conn->prepare($sql_update);
              $stmt->execute();     


              
              echo "<script>alert('Updated successfully!!');</script>";
              header("location:dashboard.php");
    
          } 
          else {
              echo "<script>alert('Category does not exist! Add Category first')</script>";
          }

          
        
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
                  <a class="nav-link" href="dashboard.php">
                    <span><img src="../images/pie-chart.svg" alt="" class="feather"></span>
                    Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="customplan.php">
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
        <!--Peichart Space-->
        <div class="sidep">
            <h3 class="">Your base plan</h3>    
            <!--Pie Chart-->
            <div class="piechart">   
                <div id="piechart_user" ></div>
            </div>  <!--piechart div ends here -->
            <div class="add_category">
                <h4>Click to add new category</h4>
                <button type="button" class = "btn btn-success" data-toggle="modal" data-target="#exampleModalCenter1">Add</button> 
                
            </div>
            <div class = "sidep">
              <form action = "customplan.php" method = "post">
              <h4>Return to dashboard</h4>
              <button value = "dashboard_btn" type = "submit" class = "btn btn-success" name = "dashboard_btn">View Dashboard</button>
              </form>
            </div>

            <!--Modal Section-->

               
                <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">New Category </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        
                        <div class="modal-body">
                            <!--Modal Form-->
                            <form method = "post" id="custom-form" name="custom-form">
                                <div class="form-group">
                                    <label>Category</label>
                                    <input type="text" placeholder="Enter new category" class="form-control" name="addCategory" required>

                                    <label>Contribution</label>
                                    <input type="number" placeholder="Enter category Contribution" class="form-control" name="addContribution" required>
                                </div>
                                <input type = "submit" name = "add_btn" class="btn btn-primary" value = "Update">
                                
                            </form>
                        </div>
                    </div>
                    </div>
                </div>

            
        </div> <!--sidep div ends here -->
        <div class="sidep">
                <table class = "table table-striped ">
                <thead class="thead-dark">
                    <th>Category</th>
                    <th>Contribution</th>
                </thead>
                <?php
                    require_once 'dbconnect.php';
                    session_start();
                    
                    $email = $_SESSION['email'];
                    $sql = "SELECT category,contribution FROM user_plan WHERE uid = (SELECT uid FROM user_info WHERE email = '$email') ";
                    $result = mysqli_query($conn,$sql);
                    
                    if (mysqli_num_rows($result)>0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            //if($row['category'] == 'Savings')
                              // break;
                            $float = (float)row['contribution'];
                            $category = $row['category'];
                            
                            echo "<tr><td>".$row['category']."</td>
                                <td>".floatval($row['contribution'])."</td></tr>";
                           
                        
                        }
                    }
                    else {
                    echo "We were not able to display your plan. Kindly select a baseplan of your choice";
                    }
                ?>

                
                </table>
                <br>
                <table class = "table table-striped ">
                    <thead class="thead-dark">
                        <th>Update contribution</th>
                    </thead>
                    <tr>
                        <td>
                        <h6>Savings will be updated automatically based on your income</h6>
                            <form method = "post" >
                              <div class="form-row">
                                <div class="form-col">
                                <select type="text" class="form-control" name="updateCategory">
                                  <option>Enter category</option>
                                  <option value="Groceries">Groceries</option>
                                  <option value="Medical">Medical</option>
                                  <option value="Bills">Bills</option>
                                  <option value="Transport">Transport</option>
                                  <option value="Shopping">Shopping</option>
                                </select>
                                </div>
                                <div class="form-col">
                                  <input type="number" class="form-control" name = "newContribution" placeholder ="Enter new contribution">
                                </div>
                                <div class="form-col">
                                  <input class = "btn btn-success" type="submit" name = "update_btn" value = "Update">
                                </div>
                              </div>  
                            </form>
                        
                        </td>
                    </tr>
                </table>
          </div>  <!--sidep div ends here --> 
          
        <br><br><br>


        </div>
    </div>
</div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
</body>
</html>
