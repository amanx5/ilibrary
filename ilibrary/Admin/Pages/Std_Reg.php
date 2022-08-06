<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations</title>
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
        <a href='/ilibrary/Admin/Pages/Std_Reg.php'>Registrations</a>
      </div>"; 

echo "<div id='container'>";
echo '<label class="labels" for="target"><div class="sub_heading">
        Search registrations
    </div></label>';

echo '<div>
        <form method="post">
            <input id="target" type="number" name="srcroll"  class="inputs inputs_focus" placeholder="Enter Roll Number" size="20"required>
            <input type="submit" name="src" id="sub" class="buttons buttons_hover" value="Take Action">
        </form>
    </div>';       //search bar closed

//response on search
if(isset($_POST['src'])) 
{
    echo "<div id='src_result'>";
    
        extract($_POST);
        $query = "select * from registrations where R_roll='$srcroll'";
        $srcreg = mysqli_query($conn , $query);

            if(mysqli_num_rows($srcreg) > 0)
            {   
                $record = mysqli_fetch_array($srcreg);
                extract($record);

                echo "
                <div class=table>
                    <div class='head'>
                        <div class='item m'>Registration Date</div>
                        <div class='item'>Roll No.</div>
                        <div class='item'>Name</div>
                        <div class='item'>Class</div>
                        <div class='item'>Email</div>
                        <div class='item l'>Action</div>
                    </div>";
                 $R_Date_dmy = date("d-m-Y H:i:s", strtotime($R_Date));
                 echo "
                    <div class='row'>
                        <div class='item m'>$R_Date_dmy</div>
                        <div class='item'>$R_Roll</div>
                        <div class='item'>$R_Name</div>
                        <div class='item'>$R_Class</div>
                        <div class='item'>$R_Mail</div>
                        <div class='item l'><form method='post'>
                            <input type='submit' name='subacc' value='Accept' class='smol_btns green_bg' class='buttons_hover'>
                            <input type='submit' name='subrej' value='Reject' class='smol_btns red_bg' class='buttons_hover'>
                            <input type='hidden' name='rh_Roll' value='$R_Roll'>
                            <input type='hidden' name='rh_Pass' value='$R_Pass'>
                            <input type='hidden' name='rh_Name' value='$R_Name'>
                            <input type='hidden' name='rh_Class' value='$R_Class'>
                            <input type='hidden' name='rh_Mail' value='$R_Mail'>
                        </form></div>
                    </div>";
                echo '</div>'; 
            }

            else 
            {
                echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
                No request from this roll number.
                </div>";
            }
    echo '</div>';
}   //response on search closed


//response on accept/reject
if(isset($_POST['subacc']))    //when admin clicked accept
{   
    extract($_POST);
    $time = date("Y-m-d H:i:s");
    $query4 = "insert into students values('$rh_Roll','$rh_Pass','$rh_Name','$rh_Class','$rh_Mail','$time')";
    $result4 = mysqli_query($conn,$query4);   
        if($result4)
        {   
            //deleting from the registration list
            $query5="delete from registrations where R_Roll='$rh_Roll'";
            $result5 = mysqli_query($conn,$query5);
            echo "<div class='success'><i class='fa fa-check-circle icon'></i>
            Registration accepted</div>";
        }
        else
        {
            echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
            Already accepted / ".mysqli_error($conn)."</div>"; 
        }
}

else if(isset($_POST['subrej']))   //when admin clicked reject
{   
    extract($_POST);
    $query6="delete from registrations where R_Roll='$rh_Roll'";
    $result6 = mysqli_query($conn,$query6);

        if($result6)
        { 
            echo "<div class='success'><i class='fa fa-check-circle icon'></i>
            Registration rejected</div>";
        }
        else 
        {
            echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
            Already rejected / ".mysqli_error($conn)."</div>"; 
        }  
}

if(!isset($srcreg))
{
  echo '<div id="home_table">
    <div class="sub_heading">Recent registrations</div>';

    $query = "select * from registrations order by R_Date desc limit 6";
    $result = mysqli_query($conn , $query);

    if(mysqli_num_rows($result) > 0)
    {
        echo "<div class=table>
            <div class='head'>
              <div class='item m'>Registration Date</div>
              <div class='item'>Roll No</div>
              <div class='item'>Name</div>
              <div class='item'>Class</div>
              <div class='item'>Email</div>
            </div>";

        while($record = mysqli_fetch_array($result))
        {
        extract($record);
        $R_Date_dmy = date("d-m-Y H:i:s", strtotime($R_Date));
        echo "
        <div class='row'>
          <div class='item m'>$R_Date_dmy</div>
          <div class='item'>$R_Roll</div>
          <div class='item'>$R_Name</div>
          <div class='item'>$R_Class</div>
          <div class='item'>$R_Mail</div>
        </div>";
        }
    echo '</div>';  //table closed 
    }
    else 
    {
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        No requests found</div>"; 
    }
  echo '</div>';  //home_table  closed
}

echo '</div>';  //container closed
mysqli_close($conn);
?>

</body>
</html>