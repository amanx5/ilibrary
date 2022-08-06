<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Books</title>
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
include '../_navbar.php';     //navbar
$conn = mysqli_connect("localhost","root","","ilibrary");
if(!$conn) die("<br>Could not connect to the server");

echo "<div id='main_heading'>
        <a href='/ilibrary/Admin/Pages/Issue.php'>Issue Books</a>
      </div>";

echo "<div id='container'>";

echo '<label class="labels" for="target"><div class="sub_heading">
       Issue a new book
      </div></label>';

echo '<div>
        <form method="post" autocomplete="off">
          <input id="target" type="number" name="src" class="inputs inputs_focus" placeholder="Enter the book number" required>
          <input type="submit" name="subs" class="buttons buttons_hover" value="Check Status">
        </form>
      </div>';    //search bar closed

//response on search
if(isset($_POST['subs']))
{ 
  echo "<div id='src_result'>";

    extract($_POST);
    $query = "select * from book where B_Sno = '$src'";
    $srcbook = mysqli_query($conn , $query);

    if(mysqli_num_rows($srcbook) > 0)
    {
      $srcbookinfo = mysqli_fetch_array($srcbook);
      extract($srcbookinfo);
      
      if($B_Status == 0 )
      {
        $Status="Available";
        $B_Issue = sprintf(
          "<form method='post'>  
              <input type='text' name='issue_to_roll' id='issue_input' placeholder='Enter Student Roll No.' required>
              <input type='submit' name='subi' value='Issue' class='smol_btns' class='buttons_hover' >
              <input type='hidden' name='issue_b_sno' value='$B_Sno'>
            </form>");
      }
      else
      {
        $Status="Issued";
         //find out who issued this book
          $query_who = "select I_Roll from issue where I_BSno='$B_Sno'";
          $result_who = mysqli_query($conn , $query_who);
          $who = mysqli_fetch_array($result_who);
          extract($who);
          $B_Issue = sprintf("Student Roll No. $I_Roll");
      }
      

      echo "
      <div id='src_book'>

        <div class='header'>
          <div class='row1'>
            <div class='headings'>BOOK NUMBER</div>
            <div class='headings'>STATUS</div>
          </div>

          <div class='row2'>
            <div>$B_Sno</div>
            <div>$Status</div>
          </div>
        </div>

        <div class='details'>

          <div><img class='bookimg' src='data:image/jpeg;base64,".base64_encode($B_Photo)."'></div>
          <div class='column'>
            <div class='bname'>$B_Name</div> 
            <div>$B_Author</div> 
            <div id='internal_form'>$B_Issue</div>
          </div>
        </div>

      </div>";
      
    }
    else 
    {
      echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
      Book No. $src doesn't exist in the iLibrary.
      </div>";   
    } 
  echo '</div>';
}   //response on search closed

//response on issue
if(isset($_POST['subi'])) 
{  
  extract($_POST);
  //checking roll no
  $query_cr = "select * from students where S_Roll='$issue_to_roll'";
  $result_cr = mysqli_query($conn , $query_cr);
    
  if(mysqli_num_rows($result_cr) > 0)
  {
    //checking limit of books exceeded by student or not
    $query_cl = "select * from issue where I_Roll='$issue_to_roll'";
    $result_cl = mysqli_query($conn , $query_cl);
      
      if(mysqli_num_rows($result_cl) < 2)
      {
        //changing status to 1 in book table
        $query_cs = "update book set B_Status = '1' where B_Sno = '$issue_b_sno'";
        $result_cs = mysqli_query($conn , $query_cs);
        $time = date('Y-m-d H:i:s');
        $query_i = "insert into issue values('$issue_b_sno','$issue_to_roll','$time')";
        $result_i = mysqli_query($conn , $query_i);

        if($result_i)  
        { 
        echo "<div class='success'><i class='fa fa-check-circle icon'></i>
        Book No. $issue_b_sno is successfully issued to Student Roll No. $issue_to_roll
        </div>";
        }
        else
        { 
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
              Already Issued / ".mysqli_error($conn)."</div>"; 
        }
      }

      else
      {
      echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
            Can't issue book to this student. Student has already issued ".mysqli_num_rows($result_cl)." books
            </div>";
      }
  }
  else 
  {
  echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        This student hasn't registered yet.
        </div>";
  }

}   //response on issue closed

//recently issued books
if(!isset($srcbookinfo))
{
echo '<div id="home_table">
  <div class="sub_heading">Recently issued Books</div>';

  $query = "select * from issue order by I_Date desc LIMIT 6";
  $issued = mysqli_query($conn , $query);

  if(mysqli_num_rows($issued) > 0)
  {   
    echo "<div class=table>
            <div class='head'>
              <div class='item' title='Issue Date'>Issue Date</div>
              <div class='item s'>Book No.</div>
              <div class='item extramarg'>Book Name</div>
              <div class='item s'>Student Roll No.</div>
              <div class='item'>Student Name</div>
            </div>";

      while($issbook = mysqli_fetch_array($issued))
      {
      extract($issbook);
      $I_Date_dmy = date("d-m-Y H:i:s", strtotime($I_Date));
      //fetching other details from book table
      $query2 = "select * from book where B_Sno='$I_BSno'";
      $result2 = mysqli_query($conn , $query2);
      $isbookdetail = mysqli_fetch_array($result2);
      extract($isbookdetail);

      //fetching other details from student table
      $query3 = "select * from students where S_Roll='$I_Roll'";
      $result3 = mysqli_query($conn , $query3);
      $isstudentdetail = mysqli_fetch_array($result3);
      extract($isstudentdetail);
      
      //finally printing all the details of currently fetched issued book
      echo "
      <div class='row'>
        <div class='item'>$I_Date_dmy</div>
        <div class='item s'>$I_BSno</div>
        <div class='item extramarg'>$B_Name</div>
        <div class='item s'>$S_Roll</div>
        <div class='item'>$S_Name</div>
      </div>";
      }
      
    echo "</div>";  //table closed
  }
  else 
  {
  echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        No book has been issued.</div>";   
  }
echo '</div>';  //home_table closed  
}

echo '</div>';  //container closed
mysqli_close($conn);
?>

</body>
</html>