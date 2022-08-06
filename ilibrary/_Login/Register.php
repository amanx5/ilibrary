<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="icon" type="image/x-icon" href="/iLibrary/xtra/favicon.ico">
    <link rel="stylesheet" href="Register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
<body>

<div class="container">
 <div class="register">
 <div id='header'>Fill your details</div>

    <form  method="post" autocomplete="off">
    
    <div id='form_divs_container'>
      <div class= "form_divs">

        <span>Login Details</span>
        <div class='formitems'>
        <input title='Enter your college Roll no.' id='username' class=' inputs' type="number" name="r_roll" placeholder="Roll number" min='1' required>
        </div>

        <div class='formitems' id='pass_formdiv'>
        <input class='inputs' id="input_pass" type="password" name="r_pass" placeholder="Password" required>
            <div>
            <input id='checkbox_pass' type="checkbox" onclick="show_pass()">
            <label for="checkbox_pass" id='label_showpass'>Show password</label>
            </div>
        </div>
      </div>

      <div class= "form_divs">
        <span>Personal Details</span>
        <div class='formitems'>
        <input class='inputs' type="text" name="r_name" placeholder="Name" required>
        </div>
        
        <div class='formitems'>
        <select id="select_div" class="light" name="r_class" required onchange="bold()">
            <option disabled selected value style="display:none;">Course</option>'
            <option value='BBA'>BBA</option>
            <option value='BCA'>BCA</option>
            <option value='BCOM'>BCOM</option>
            <option value='BSC'>BSC</option>
            <option value='MA'>MA</option>
            <option value='MCOM'>MCOM</option>
            <option value='MSC'>MSC</option>

        </select>
        </div>

        <div class='formitems'>
        <input class='inputs' type="email" name="r_mail" placeholder="E-Mail" required>
        </div>
      </div>
      
    </div>  
    <!-- form_divs closed -->
      
    <div class='formitems' id="buttons_div">
    <a class="go_back" href="Home.php">Go Back</a>
    <input id="submit_b" type="submit" name="submit" value="Submit">
    </div>
</form>

<?php
if(isset($_POST['submit'])) 
{   
    $conn = mysqli_connect("localhost","root","","ilibrary");
    if(!$conn) die("<br>Could not connect to the server");
    extract($_POST);

  if (preg_match("/^[a-zA-Z]+(\s+[a-zA-Z]*)*$/", $r_name))
  {
    $query_SR = "select * from students where S_Roll = '$r_roll'";
    $result_SR = mysqli_query($conn , $query_SR);
    $query_SM = "select * from students where S_Mail = '$r_mail'";
    $result_SM = mysqli_query($conn , $query_SM);
    $query_RR = "select * from registrations where R_Roll = '$r_roll'";
    $result_RR = mysqli_query($conn , $query_RR);
    $query_RM = "select * from registrations where R_Mail = '$r_mail'";
    $result_RM = mysqli_query($conn , $query_RM);
    
    if(mysqli_num_rows($result_SR) > 0)
    echo "<div class='fail_resp'><i class='fa fa-exclamation-circle warn'></i>Account with this Roll number already exists.</div>";
   
    else if(mysqli_num_rows($result_SM) > 0)
    echo "<div class='fail_resp'><i class='fa fa-exclamation-circle warn'></i>Account with this E-mail already exists.</div>";

    else if(mysqli_num_rows($result_RR) > 0)
    echo "<div class='fail_resp'><i class='fa fa-exclamation-circle warn'></i>Your Roll number is already registered.</div>";

    else if(mysqli_num_rows($result_RM) > 0)
    echo "<div class='fail_resp'><i class='fa fa-exclamation-circle warn'></i>Your E-Mail is already registered.</div>";
 
    else
    {
        $time = date("Y-m-d H:i:s");
        $query = "insert into registrations values('$r_roll','$r_pass','$r_name','$r_class','$r_mail','$time')";
        $result = mysqli_query($conn,$query);

        if($result)
        {   
            echo "<script>
            header.style.display = 'none';
            form_divs_container.style.display = 'none';
            buttons_div.style.display = 'none';
            </script>";

            echo "<div id='response'>
                <div id='resp_header'>
                <i class='fa fa-check-square tick'></i>Form Submitted
                </div>
                <div id='resp_footer'>
                Reach out to the librarian with your college ID card for the verification.
                </div>
                <a class='go_back' href='Home.php'>Return to Login Page</a>
            </div>";
        }

        else echo mysqli_error($conn);
    } 
    mysqli_close($conn); 
  }
  else 
  {
    echo "<div class='fail_resp'><i class='fa fa-exclamation-circle warn'></i>
    Name should only have alphabets & space(not in beginning)</div>";  
  }

}
?>

</div>
<!-- register closed -->
</div>
<!-- container closed -->

<script>
    function show_pass()
    {
        if (input_pass.type === "password")
        {
        input_pass.type = "text";
        } 
        else
        {
        input_pass.type = "password";
        }
    }
    function bold()
    {
        select_div.classList.add("bold");
    }

</script>
</body>
</html>