<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="icon" type="image/x-icon" href="/iLibrary/xtra/favicon.ico">
    <link rel="stylesheet" href="/iLibrary/Admin/_navbar.css">
    <link rel="stylesheet" href="/iLibrary/Admin/Pages.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
<body>

<?php 
include '../_navbar.php';      //navbar
$conn = mysqli_connect("localhost","root","","ilibrary");
if(!$conn) die("<br>Could not connect to the server");

echo "<div id='main_heading'>
        <a href='/ilibrary/Admin/Pages/Account.php'>Account Settings</a>
      </div>"; 

echo '<div id="act_container">

  <div class="act_items">
    <label class="labels" for="target1"><div class="sub_heading">Username</div></label>
    <form method="post" class="act_form" autocomplete="off">
      <input type ="text" name="ex_user" class="inputs act_inputs inputs_focus" placeholder="Enter existing username" required id="target1">
      <input type ="text" name="new_user" class="inputs act_inputs inputs_focus" placeholder="Enter your new username" required>
      <input type ="submit" name="subuser" class="act_submit buttons buttons_hover" value="Change Username">
    </form>';

    echo '<div class="act_response">';
    if(isset($_POST['subuser']))
    {
      extract($_POST);
      
      if($new_user == $ex_user)
      {
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        Both usernames are same</div>";
      }
      else
      {
        $query_cu = "select admin_user from admin where admin_user= '$ex_user'";
        $result_cu = mysqli_query($conn, $query_cu);
    
        if(mysqli_num_rows($result_cu) > 0)
        {
          $query_uu = "update admin set admin_user ='$new_user' where admin_user = '$ex_user'";
          $result_uu = mysqli_query($conn, $query_uu);

          if($result_uu)
          {
            echo "<div class='success'><i class='fa fa-check-circle icon'></i>
            Username changed</div>";
          }
          else
          {
            echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
            Failed to update the username / ".mysqli_error($conn).
            "</div>";
          }
        }
        else
        {
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        Incorrect existing username</div>";
        }
      }
    }
    echo '</div>'; //response for change user closed

  echo '</div>';

  echo' 
    <div class="act_items">
      <label class="labels" for="target2"><div class="sub_heading">Password</div></label>
      <form method="post" class="act_form" autocomplete="off">
        <input type ="password" name="ex_pass" class="inputs act_inputs inputs_focus" placeholder="Enter existing password" required id="target2">
        <input type ="password" name="new_pass" class="inputs act_inputs inputs_focus" placeholder="Enter your new password" required>
        <input type ="submit" name="subpass" class="act_submit buttons buttons_hover" value="Change Password">
      </form>';

      echo '<div class="act_response">';
      if(isset($_POST['subpass']))
      {
        extract($_POST);
        
        if($new_pass == $ex_pass)
        {
          echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
          Both passwords are same</div>";
        }
        else
        {
          if(strlen($new_pass)>=6)
          {
            $query_cu = "select admin_pass from admin where admin_pass= '$ex_pass'";
            $result_cu = mysqli_query($conn, $query_cu);
      
            if(mysqli_num_rows($result_cu) > 0)
            {
              $query_uu = "update admin set admin_pass ='$new_pass' where admin_pass = '$ex_pass'";
              $result_uu = mysqli_query($conn, $query_uu);

              if($result_uu)
              {
                echo "<div class='success'><i class='fa fa-check-circle icon'></i>
                Password changed</div>";
              }
              else
              {
                echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
                Failed to update the password / ".mysqli_error($conn).
                "</div>";
              }
            }
            else
            {
            echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
            Incorrect existing password</div>";
            }
          }
          else
          {
          echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
          Password should have atleast 6 characters</div>";
          }
        }
      }
      echo '</div>'; //response for change pass closed

  echo '</div>';

echo '</div>';  //container closed

mysqli_close($conn);
?>
</body>
</html>