<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iLibrary</title>
  <link rel="icon" type="image/x-icon" href="/iLibrary/xtra/favicon.ico">
  <link rel="stylesheet" href="_navbar.css">
  <link rel="stylesheet" href="/iLibrary/Shared/Home.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
</head>
<body>

<?php
include '../Shared/Home.php';	//including shared home page

// updating fine table
$today = date("Y-m-d H:i:s");
$t = new DateTime($today);
$query = "select * from issue";
$issued = mysqli_query($conn , $query);

if(mysqli_num_rows($issued) > 0)
{
  while($record = mysqli_fetch_array($issued))
  {
    extract($record);
    //calculating return before and fine
    $R_Before = date('Y-m-d H:i:s', strtotime($I_Date. ' + 21 days'));
    $r = new DateTime($R_Before);
    $days_after = $r->diff($t)->format("%r%a");	//%r(relative) -sign, $a difference in days

    if($days_after > 0)   
    { 
      $fine=$days_after;   //fine = 1rs * no.ofdays after return before date

      //check if student roll and book sno already exists in fine table
      //to do:- add new column I_Sno in issue table so that fine doesnt get overwritten if same student issues same book again and doesn't return in 20 days again

      $query_cf = "select * from fine where F_BSno ='$I_BSno' AND F_Roll='$I_Roll'";
      $result_cf = mysqli_query($conn , $query_cf);
      
      if(mysqli_num_rows($result_cf)>0)   //record already exists, just need to update fine
      {
      $query_uf = "update fine set F_Fine='$fine' where F_Sno = (select MAX(F_Sno) from fine WHERE F_Roll='$I_Roll' AND F_BSno = '$I_BSno')";  //incase of reissue of same book, update the most recently issued
      $result_uf = mysqli_query($conn , $query_uf);
      }

      else                          //record doesnt exist so inserting a fresh record
      {
      $query_f = "insert into fine (F_BSno, F_Roll, F_IDate, F_Fine) values ('$I_BSno','$I_Roll','$I_Date','$fine')";
      $result_f = mysqli_query($conn , $query_f);
      }
    }   //child if 
  }   //while
}   //parent if

?>

</body>
</html>