<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="icon" type="image/x-icon" href="/iLibrary/xtra/favicon.ico">
    <link rel="stylesheet" href="/iLibrary/Admin/_navbar.css">
    <link rel="stylesheet" href="/iLibrary/Admin/Pages.css">
    <link rel="stylesheet" href="/iLibrary/Shared/Profile.css">
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
        <a href='/ilibrary/Admin/Pages/Student.php'>Students</a>
      </div>"; 

echo "<div id='container'>";
    echo '<label class="labels" for="target"><div class="sub_heading" id="sub_head">Search students</div></label>';


echo '<script>document.styleSheets[2].disabled=true;</script>
    <div id="searchbar">
        <form method="post">
            <input id="target" type="number" name="srcroll" class="inputs inputs_focus" placeholder="Enter Roll Number" size="20"required>
            <input type="submit" name="src" id="sub" class="buttons buttons_hover" value="View Profile">
        </form>
    </div>';    //search bar closed here

//response on search
if(isset($_POST['src'])) 
{
extract($_POST);
$query1 = "select * from students where S_Roll = '$srcroll'";
$srcstd = mysqli_query($conn , $query1);

   if(mysqli_num_rows($srcstd) > 0) 
    {
        $record = mysqli_fetch_array($srcstd);
        extract($record);
        
        //student profile
        echo "<div id='search_result'>Searched profile ($srcroll)</div>";
        include '../../Shared/Profile.php';
        echo "
        <script>
            document.styleSheets[1].disabled=true;
            document.styleSheets[2].disabled=false;
            searchbar.style.display = 'none';
            sub_head.style.display = 'none';
            main_std_heading.style.display = 'none';
        </script>";
    }

    else 
    {
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        No student found with this roll number.
        </div>";
    }
}       //response on search closed here


if(!isset($srcstd))
{
echo '<div id="home_table">
    <div class="sub_heading">Recently approved students</div>';

    $query_s = "select * from students order by S_AccDate desc limit 6";
    $result_s = mysqli_query($conn , $query_s);

    if(mysqli_num_rows($result_s) > 0)
    {
    echo "<div class=table>
        <div class='head'>
            <div class='item'>Roll No</div>
            <div class='item'>Name</div>
            <div class='item'>Class</div>
            <div class='item'>Email</div>
            <div class='item m'>Approval Date</div>
        </div>";

        while($record_s = mysqli_fetch_array($result_s))
        {
        extract($record_s);
        $S_Date_dmy = date("d-m-Y H:i:s", strtotime($S_AccDate));
        echo "
        <div class='row'>
            <div class='item'>$S_Roll</div>
            <div class='item'>$S_Name</div>
            <div class='item'>$S_Class</div>
            <div class='item'>$S_Mail</div>
            <div class='item m'>$S_Date_dmy</div>
        </div>";
        }
    echo '</div>';  //table closed 
    }
    else 
    {
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        No registered students</div>"; 
    }
echo '</div>';  //home_table  closed
}

echo '</div>';  //container closed
mysqli_close($conn);
?>
</body>
</html>