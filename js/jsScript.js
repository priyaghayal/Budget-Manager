$(document).ready(function(){
    $('#piechart_bp1').click(function(){
        
        var message = 'You have selected your base plan';
        var number = 1;
        //Paused on exception TypeError: $.ajax is not a function
        //Solution: Use full version of JQuery not the slim version
        $.ajax({
            url:'../html/insert.php',
            method:'POST',
            data:{
                message:message,
                number:number
            },
           success:function(data){
              return window.location.href = "../html/dashboard.php",true;
           }
        });
    });

    $('#piechart_bp2').click(function(){
        var message = 'You have selected your base plan';
        var number = 2;
        $.ajax({
            url:'../html/insert.php',
            method:'POST',
            data:{
                message:message,
                number:number
            },
           success:function(data){
            return window.location.href = "../html/dashboard.php",true;
           }
        });
    });
    $('#btn_spacing').click(function(){
        $('#edit_profile').toggle();
    });
    
});
function validateForm() {
    var password1 = document.forms["register-form"]["Password1"].value; 
    var password2 = document.forms["register-form"]["Password2"].value; 
  
    if (password1 != password2) {
      alert("Passwords don't match");
      return false;
    }
  } 