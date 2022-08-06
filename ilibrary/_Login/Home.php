<?php
session_start();  
if(isset($_POST['std_logout']) || isset($_POST['admin_out']) )
{ 
    session_destroy();
}
else
{
    if(isset($_SESSION["S_Name"]))
    {   
    header("Location: /ilibrary/Student/Home.php");
    exit();   
    }
    else if(isset($_SESSION["admin_user"]))
    {   
    header("Location: /ilibrary/Admin/Home.php");
    exit();   
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to iLibrary</title>
    <link rel="icon" type="image/x-icon" href="/iLibrary/xtra/favicon.ico">
    <link rel="stylesheet" href="Home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<!------------------------------ Login form ------------------------------->

<div class="container">
<div class="login">
    <div id="header">Login</div>

    <form id="form1" method="post" autocomplete="off">
        
        <div id="choice" class="formitems">
        <label id="l1" class="labels l_default" for="one" onclick="f1()">
            <span id="c1" class="checkmark c_default"></span>
            <input type="radio" class="radio" id="one" name="usertype" value="student" checked="checked">
            <span>Student</span>
        </label>

        <label id="l2" class="labels" for="two" onclick="f2()">
            <span id="c2" class="checkmark"></span>
            <input type="radio" class="radio" id="two" name="usertype" value="admin">
            <span>Admin</span>
        </label>
        </div>

        <div>
            <input class="inputs" type="text" name="username" placeholder="Username" required>
        </div>

        <div>
            <input class="inputs" id="input_pass" type="password" name="pass" placeholder="Password" required>
            <div>
            <input id='checkbox_pass' type="checkbox" onclick="show_pass()">
            <label for="checkbox_pass" id='label_showpass'>Show password</label>
            </div>
        </div>

        <div class="formitems" id="login_div">
            <input class="formdivitems" id="login_b" type="submit" name="submit" value="Login">
        </div>
    </form>
 
    <?php
        $conn = mysqli_connect("localhost","root","","ilibrary");
        if(!$conn) die("<br>Could not connect to the server");

        if (isset($_POST['submit'])) 
        {
         extract($_POST);

         echo '<div id="response">';    
            if ($usertype == "student")
            { 
                $query = "select * from students where S_Roll = '$username' and S_Pass = '$pass'";
                $result = mysqli_query($conn , $query);
                
                    if(mysqli_num_rows($result) > 0)
                    {
                    $userinfo = mysqli_fetch_array($result);
                    $_SESSION=$userinfo;
                    header("Location: ../Student/Home.php");
                    exit();
                    }
                    else
                    {
                    echo "<i class='fa fa-exclamation-circle warn'></i>Invalid login credentials";
                    }
            }
            else    //when admin is selected
            {   
                $query = "select * from admin where admin_user = '$username' and admin_pass = '$pass'";
                $result = mysqli_query($conn , $query);

                    if(mysqli_num_rows($result) > 0)
                    {
                    $admininfo = mysqli_fetch_array($result);
                    $_SESSION=$admininfo;
                    header("Location: ../Admin/Home.php");
                    exit();
                    }
                    else 
                    {
                    echo "<i class='fa fa-exclamation-circle warn'></i>Invalid login credentials";
                    }
            }
         echo '</div>';    
        }
        mysqli_close($conn);     
    ?>
    
    <div id="footer">
        <span>Don't have an account?</span>
        <a id="reg_link" href="register.php">Register</a>
    </div>

</div>
<!-- login closed  -->
</div>
<!-- container closed -->

<!-- student/admin label, showpass onclick JS functions -->
<script>
    function f1()
    {
        l1.classList.add("l_default");
        l2.classList.remove("l_click");
        c1.classList.add("c_default");
        c2.classList.remove("c_click");
        footer.style.display = 'block';
    }
        
    function f2()
    {
        l1.classList.remove("l_default");
        l2.classList.add("l_click");
        c1.classList.remove("c_default");
        c2.classList.add("c_click");
        footer.style.display = 'none';
    }

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
</script>

</body>
</html>