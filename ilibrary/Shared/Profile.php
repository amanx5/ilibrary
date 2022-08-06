<?php 
$today = date("Y-m-d H:i:s");
$t = new DateTime($today);
$conn = mysqli_connect("localhost","root","","ilibrary");
if(!$conn) die("<br>Could not connect to the server");
extract($_SESSION);
$heading= (isset($_SESSION['S_Name']))?'Your Profile':'';

echo "<div class='main_heading' id='main_std_heading'>$heading</div>";   //profile 

echo "
<div id='main_container'>
  
  <div class='det_container'>
   <div class='det_heading'>Account</div>
    <div class = 'row_ac'>
      <div class='detail_type'>Username</div>
      <input class='inputs' type='text' value='$S_Roll' readonly></div>
  
    <div class = 'row_ac'>
      <div class='detail_type'>Password</div>
      <div class='inputs'>
        <input id='student_pass' type='password' value='$S_Pass' size='18'readonly><i id='view_pass' onclick='reveal_pass()' class='fa fa-eye'></i><i id='hide_pass' onclick='hide_pass()' class='fa fa-eye-slash'></i>
      </div>
    </div>
  
    <div class = 'row_ac'>
      <div class='detail_type'>Name</div>
      <input class='inputs' type='text' value='$S_Name' readonly>
    </div>

    <div class = 'row_ac'>
      <div class='detail_type'>Email</div>
      <input class='inputs' type='text' value='$S_Mail' readonly>
    </div>

    <div class = 'row_ac'>
      <div class='detail_type'>Course</div>
      <input class='inputs' type='text' value='$S_Class' readonly>
    </div>
   
  </div>

</div>";    //profile container closed

//fetching details of book(s) issued by student from issue table
$query_id = "select I_BSno,I_Date from issue where I_Roll='$S_Roll'";
$book_id = mysqli_query($conn , $query_id);

echo "
<div id='books_container'>
  <div class='det_heading'>Issued Books</div>";
  
  if(mysqli_num_rows($book_id) > 0)
  {
    while($record = mysqli_fetch_array($book_id))
    {
      extract($record);
      //fetching other book details from book table
      $query_obd = "select * from book where B_Sno='$I_BSno'";
      $result_obd = mysqli_query($conn , $query_obd);
      $otherbookdet = mysqli_fetch_array($result_obd);
      extract($otherbookdet);
      $I_Date_dmy = date("d-m-Y H:i:s", strtotime($I_Date));
      $R_Before_dmy= date('d-m-Y H:i:s', strtotime($I_Date_dmy. ' + 21 days'));
      
      $R_Before = date('Y-m-d H:i:s', strtotime($I_Date. ' + 21 days'));
      $rb = new DateTime($R_Before);
      $days_after = $rb->diff($t)->format("%r%a");
      $fine=$days_after;
      
      if($days_after > 0)  //i.e fine criteria is fulfilled
      {
      //checking whether the record exist in fine table
      $query_cef = "select * from fine where F_Sno= (select MAX(F_Sno) from fine WHERE F_Roll='$S_Roll' AND F_BSno = '$I_BSno')";
      $result_cef = mysqli_query($conn , $query_cef);

        if(mysqli_num_rows($result_cef ) == 0) // i.e record doesnt exist
        {
        $query_f = "insert into fine (F_BSno, F_Roll, F_IDate, F_Fine) values ('$I_BSno','$S_Roll','$I_Date','$fine')";
        $result_f = mysqli_query($conn , $query_f);
        }
      }
      echo "
      <div id='books'>

        <div class='header'>
          <div class='row1'>
            <div class='headings'>ISSUE DATE</div>
            <div class='headings'>RETURN BEFORE</div>
          </div>

          <div class='row2'>
            <div>$I_Date_dmy</div>
            <div>$R_Before_dmy</div>
          </div>
        </div>

        <div class='details'>

          <div><img id='bookimg' src='data:image/jpeg;base64,".base64_encode($B_Photo)."'></div>
          <div class='column'>
            <div class='bname'>$B_Name</div> 
            <div>$B_Author</div> 
            <div>Book No: $B_Sno</div> 
          </div>
        </div>

      </div>";
    }
  }
  else { echo "None"; }

echo "</div>";   //books container closed

//fetching details from fine table
$query_sf = "select * from fine where F_Roll='$S_Roll'";    //query_sf
$result_sf = mysqli_query($conn , $query_sf);

echo "
<div id='fine_container'>
  <div class='det_heading'>Fine Details</div>"; 

  if(mysqli_num_rows($result_sf) > 0)
  { 
    echo "
    <table id='fine_table'>
      <tr>
      <th>Reference No.</th>
        <th id='head_book_num'>Book Number</th>
        <th>Issue Date</th>
        <th>Return Date</th>
        <th id='head_fine'>Fine</th>
      </tr>";

      while($book_fine= mysqli_fetch_array($result_sf))
      {
        extract($book_fine);
        $F_IDate_dmy = date("d-m-Y H:i:s", strtotime($F_IDate));
        echo "
        <tr>
          <td>$F_Sno</td>
          <td>$F_BSno</td>
          <td>$F_IDate_dmy</td>";

        if(is_null($F_RDate))     //update fine as per today's date(fine = today date - return before)
        {
          $R_Before = date('Y-m-d H:i:s', strtotime($F_IDate. ' + 21 days'));
          $rb = new DateTime($R_Before);
          $days_after = $rb->diff($t)->format("%r%a");
          $fine=$days_after;
          $query_uf = "update fine set F_Fine='$fine' where F_Sno = (select MAX(F_Sno) from fine WHERE F_Roll='$F_Roll' AND F_BSno = '$F_BSno')";  //incase of reissue of same book, update the most recently issued
          $result_uf = mysqli_query($conn , $query_uf);

          echo "<td>Not returned</td>
          <td id='detail_fine'>Rs. $fine</td>
        </tr>";   //using fine here as cant use F_Fine (as its value is from query_sf which is not updated)
        }
        else
        {
          $F_Date_dmy = date("d-m-Y H:i:s", strtotime($F_RDate));
        echo "<td>$F_Date_dmy</td>
          <td id='detail_fine'>Rs. $F_Fine</td>
        </tr>";
        }
      } //while closed
    echo '</table>';
  }

  else { echo "None"; }

echo "</div>";    //fine container closed

echo '<script>
function reveal_pass(){
  let pass = document.getElementById("student_pass");
  
  if (pass.type === "password") {
    pass.type = "text";
  } 
  else {
    pass.type = "password";
  }

  let show_b = document.getElementById("view_pass");
  show_b.style.display = "none";

  let hide_b = document.getElementById("hide_pass");
  hide_b.style.display = "inline";
}

function hide_pass(){
  let pass = document.getElementById("student_pass");
  pass.type = "password";
  
  let show_b = document.getElementById("view_pass");
  show_b.style.display = "inline";

  let hide_b = document.getElementById("hide_pass");
  hide_b.style.display = "none";
}
</script>';
?>