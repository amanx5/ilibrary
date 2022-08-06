<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Books</title>
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
include '../_navbar.php';   //navbar
$conn = mysqli_connect("localhost","root","","ilibrary");
if(!$conn) die("<br>Could not connect to the server");

echo "<div id='main_heading'>
        <a href='/ilibrary/Admin/Pages/Return.php'>Return Books</a>
      </div>"; 

echo "<div id='container'>";

echo '<label class="labels" for="target"><div class="sub_heading">
        Return an issued book
      </div></label>';

echo '<div>
        <form method="post" autocomplete="off">
          <input id="target" type="number" name="src" class="inputs inputs_focus" placeholder="Enter the book number" required>
          <input type="submit" name="subs" id="sub" class="buttons buttons_hover" value="Check Status">
        </form>
      </div>';    //search bar closed

//response on search
if(isset($_POST['subs']))
{ 
  echo "<div id='src_result'>";
 
   extract($_POST);
   $query = "select * from issue where I_BSno = '$src'";
   $srcbookinfo = mysqli_query($conn , $query);

    if(mysqli_num_rows($srcbookinfo) > 0)
    {
      $record = mysqli_fetch_array($srcbookinfo);
      extract($record); 

      //fetching other book details of issued book
      $query2 = "select * from book where B_Sno='$src'";
      $result2 = mysqli_query($conn , $query2);
      $record2 = mysqli_fetch_array($result2);
      extract($record2);
    
      //fetching other student details of issued book
      $query3 = "select * from students where S_Roll='$I_Roll'";
      $result3 = mysqli_query($conn , $query3);
      $record3 = mysqli_fetch_array($result3);
      extract($record3);

      //fetching fine amount
      $query_f = "select F_Fine from fine where F_Roll='$I_Roll' AND F_BSno = '$src'";
      $result_f = mysqli_query($conn , $query_f);
      if(mysqli_num_rows($result_f) > 0)
      {
        $record_f = mysqli_fetch_array($result_f);
        extract($record_f);
      }
      $fine = (isset($F_Fine)?$F_Fine:0);

      $I_Date_dmy = date("d-m-Y H:i:s", strtotime($I_Date));
      $R_Before = date('d-m-Y H:i:s', strtotime($I_Date. ' + 21 days'));
      $Return_Form = "<form method='post'>
      <input type='text' name='return_S_Roll' id='issue_input' value='Roll No. $I_Roll' readonly>
      <input type='submit' name='subret' value='Return' class='smol_btns' class='buttons_hover'>
      <input type='hidden' name='return_B_Sno' value='$B_Sno'>
      </form>";

      echo "
      <div id='src_book'>

        <div class='header'>
          <div class='row1'>
            <div class='headings'>ISSUE DATE</div>
            <div class='headings'>RETURN BEFORE</div>
          </div>

          <div class='row2'>
            <div>$I_Date_dmy</div>
            <div>$R_Before</div>
          </div>
        </div>

        <div class='details'>

          <div><img class='bookimg' src='data:image/jpeg;base64,".base64_encode($B_Photo)."'></div>
          <div class='column'>
            <div class='bname'>$B_Name</div> 
            <div>Book No: $B_Sno</div> 
            <div>Fine: Rs $fine</div>
            <div>$Return_Form</div>
          </div>
        </div>

      </div>";

    }
    else 
    {   $query5 = "select * from book where B_Sno = '$src'";
        $result5 = mysqli_query($conn , $query5);
            if(mysqli_num_rows($result5) == 0) 
            {
              echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
              Book No. $src doesn't exist in the iLibrary.
              </div>";  
            }
            else 
            {
              echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
              Book No. $src has not been issued to anyone.
              </div>";  
            }
    }  
  echo '</div>'; 
}   //response on search closed

//response on return
if(isset($_POST['subret'])) 
{  
    extract($_POST);
    //changing status to 0 in book table
    $query1 = "update book set B_Status = '0' where B_Sno = '$return_B_Sno'";
    $result1 = mysqli_query($conn , $query1);
    if($result1) 
    {
        //deleting the returned book from issue table
        $query3 = "delete from issue where I_BSno ='$return_B_Sno'";
        $result3 = mysqli_query($conn , $query3);
        if($result3) 
        {
          echo "<div class='success'><i class='fa fa-check-circle icon'></i>
          Book No. $return_B_Sno is successfully returned from Student $return_S_Roll
          </div>";

          //feed the return date in fine table if record exists
          $time = date('Y-m-d H:i:s');
          $return_S_Roll_trim= substr($return_S_Roll,9);
          $query4 = "update fine set F_RDate = '$time' where F_Sno = (select MAX(F_Sno) from fine WHERE F_BSno = '$return_B_Sno' AND F_Roll = '$return_S_Roll_trim')";
          $result4 = mysqli_query($conn , $query4); 
        
          //feed the record in return table
          $query_fr = "insert into `return` (Ret_BSno, Ret_Roll, Ret_Date) values ('$return_B_Sno','$return_S_Roll_trim','$time');";
          $result_fr = mysqli_query($conn , $query_fr); 
        }
        else 
        {
          echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
          Already Returned / ".mysqli_error($conn)."</div>"; 
        }
    }
    else
    {
      echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
      Updating Book status Failed / ".mysqli_error($conn)."</div>"; 
    }

}   //response on return closed


if(!isset($srcbookinfo))
{
echo  '<div id="home_table">
  <div class="sub_heading">Recently returned Books</div>';
 
  $query_ret = "select * from `return` order by Ret_Sno desc limit 6;";
  $returned = mysqli_query($conn , $query_ret);
  
  if(mysqli_num_rows($returned) > 0)
  {   
    echo "<div class=table>
            <div class='head'>
              <div class='item s'>S No.</div>
              <div class='item s'>Book No.</div>
              <div class='item s'>Student Roll No.</div>
              <div class='item'>Return Date</div>
            </div>";
    
      while($retbook = mysqli_fetch_array($returned))
      {
      extract($retbook);
      echo "
      <div class='row'>
        <div class='item s'>$Ret_Sno</div>
        <div class='item s'>$Ret_BSno</div>
        <div class='item s'>$Ret_Roll</div>
        <div class='item'>$Ret_Date</div>
      </div>";
      }
    echo '</div>';  //table closed  
  }
  else
  {
  echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        No book has been returned.</div>";   
  }

echo '</div>';  //home_table  closed
}

echo '</div>';  //container closed
mysqli_close($conn);
?>

</body>
</html>