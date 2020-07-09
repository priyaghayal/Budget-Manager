<?php
    require_once "dbconnect.php";
    session_start();
    $message = $_POST["message"];
    $number = $_POST["number"];
    //create table user_plan(uid int not null , category varchar(30),contribution float , foreign key(uid) references user_info(uid));
    /*
    insert into user_plan(uid) select uid from user_info;

    insert into user_plan(uid , category , contribution) 
    select user_plan.uid , bp1.category , bp1.contribution from bp1,user_plan where 
    user_plan.uid = (select uid from user_info where email like 'abc@gmail.com');

    insert into user_plan (category,contribution)
    select bp1.category , bp1.contribution from bp1,user_plan where 
    user_plan.uid = (select uid from user_info where email like 'pqr@gmail.com');

    update user_info set first_login = 1 where email like 'pqr@gmail.com';


*/
    
    $email = $_SESSION['email'];
    //echo ".$email.";
    $user_plan_uid = "INSERT INTO user_plan(uid) SELECT uid FROM user_info WHERE email = '$email'";
    $stmt_uid = $conn->prepare($user_plan_uid);
    $stmt_uid->execute(); 
    if ($number == 1) {

        //Setting the selected plan as baseplan of user
        $sql = "INSERT INTO user_plan(uid , category , contribution) 
        SELECT user_plan.uid , bp1.category , bp1.contribution FROM bp1,user_plan WHERE 
        user_plan.uid = (SELECT uid FROM user_info WHERE email = '$email');";	
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        //Setting value to first time login 
        $value = 'N';
        $first_login_sql = "UPDATE user_info SET first_login ='$value' WHERE email = '$email'";
        $stmt1 = $conn->prepare($first_login_sql);
        $stmt1->execute();

        //deleting null entry in database
        /*$null_entry_sql = "DELETE FROM user_plan WHERE category IS NULL AND uid = (SELECT uid FROM user_info WHERE email = '$email');";
        $stmt2 = $conn->prepare($null_entry_sql);
        $stmt2->execute();*/
        }         

    if ($number == 2) {

        $sql = "INSERT INTO user_plan(uid , category , contribution) 
        SELECT user_plan.uid , bp1.category , bp1.contribution FROM bp1,user_plan WHERE 
        user_plan.uid = (SELECT uid FROM user_info WHERE email = '$email');";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $value = 'N';
        $first_login_sql = "UPDATE user_info SET first_login ='$value' WHERE email = '$email'";
        $stmt1 = $conn->prepare($first_login_sql);
        $stmt1->execute();
        
        //deleting null entry in database
        $null_entry_sql = "DELETE FROM user_plan WHERE category IS NULL AND uid = (SELECT uid FROM user_info WHERE email = '$email');";
        $stmt2 = $conn->prepare($null_entry_sql);
        $stmt2->execute();
        
    } 
    
?>
