<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Books</title>
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
$conn =  mysqli_connect("localhost","root","","ilibrary");
if(!$conn) die("<br>Could not connect to the server");

//fetch category list
$query = "select DISTINCT B_Category from book ORDER BY B_Category";
$category_list = mysqli_query($conn, $query);
$i=0;
$len= mysqli_num_rows($category_list);
while($category = mysqli_fetch_array($category_list))
{
  extract($category);
  $cat_array[$i] = "$B_Category";
  $i++;
}

echo "<div id='main_heading'>
        <a href='/ilibrary/Admin/Pages/Add.php'>Add Books</a>
      </div>"; 
      
echo "<div id='ab_container'>";
    
  echo '<form method="post" id="ab_form" autocomplete="off" enctype="multipart/form-data">';

    echo '
    <div id="select_container" class="ab_items">   
      <label class="labels ab_labels" for="select_div" onmousedown="sel_change_bc()" onmouseup="sel_unchange_bc()" onmouseover="sel_change_bc2(this)" onmouseout="sel_unchange_bc2(this)">Select Category</label>     
      <select id="select_div" name="cat_name" required onmouseover="sel_change_bc2(this)" onmouseout="sel_unchange_bc2(this)">
          <option disabled selected value style="display:none"> Click to view options </option>';
          for($i=0; $i<$len ; $i++ )
          { echo "<option value='$cat_array[$i]'>$cat_array[$i]</option>"; }
        echo '</select>
    </div>

    <div class="ab_items">
      <label id="label_ac" class="labels ab_labels" for="input_new_cat" onmousedown="change_bc()" onmouseup="unchange_bc()" onmouseover="change_bc2(this)" onmouseout="unchange_bc2(this)">Add Category</label>
      <div id="ctac" onclick="addnewc()" onmouseover="change_bc2(this)" onmouseout="unchange_bc2(this)" >Click to add category</div>
      <div id="inc_div">
        <input id="input_new_cat" type ="text" name="cat_name_new"  class="inputs ab_inputs inputs_focus" placeholder="Enter Category name" maxlength="100">
      </div>
    </div>

    <div class="ab_items">
      <label class="ab_labels" for="name_input">Book Name</label>
      <input id="name_input" type ="text" name="a_name" class="inputs ab_inputs inputs_focus" placeholder="Enter Book name" maxlength="100" required>
    </div>

    <div class="ab_items">
      <label class="ab_labels" for="auth_input">Book Author</label>
      <input id="auth_input" type ="text" name="a_author" class="inputs ab_inputs inputs_focus" placeholder="Enter Author name" maxlength="100" required>
    </div>

    <div class="ab_items">
      <label class="ab_labels" for="file_input">Book Photo</label>
      <input id="file_input" type="file" name="a_photo" required>
    </div>

    <div class="ab_items" id="submit_div_align_right">
      <input id="input_submit" type="submit" name="sub_add" class="buttons buttons_hover" value="Submit">
    </div>

  </form>';

if(isset($_POST['sub_add']))
{ echo "<div id='response_addbook'>";
  extract($_POST);
  $a_category = (!empty($cat_name_new)?$cat_name_new:$cat_name);
  $allowTypes = array('jpg','png','jpeg');

  if($_FILES["a_photo"]["size"] <= 62000)
  {
    if($_FILES["a_photo"]["type"] == "image/jpg" or $_FILES["a_photo"]["type"] == "image/jpeg" or $_FILES["a_photo"]["type"] == "image/png")
    {
      $imgData = addslashes(file_get_contents($_FILES['a_photo']['tmp_name']));
      // Insert image file name into database
      $query_i = "INSERT into book (B_Name,B_Author,B_Category,B_Photo) VALUES ('$a_name','$a_author','$a_category','$imgData')";
      $insert = mysqli_query($conn, $query_i);
      if($insert)
      {
        echo "<div class='success'><i class='fa fa-check-circle icon'></i>
        Book added successfully.</div>";
      }
      else
      {
        echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
        Failed / ".mysqli_error($conn).
        "</div>";
      } 
    }
    else
    {
      echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
      Only jpg, jpeg or png file types are allowed.</div>";
    }
  }
  else
  {
    echo "<div class='fail'><i class='fa fa-exclamation-circle red_icon'></i>
    Maximum file size allowed is 62KB</div>";
  }
  echo "</div>";
}

echo '</div>';  //container closed

mysqli_close($conn);
?>

<script>
  function addnewc()
  {
    select_container.style.display = 'none';
    select_div.removeAttribute("required");
    ctac.style.display ='none';
    label_ac.style.cursor ='pointer';
    inc_div.style.display = 'inline-block';
    input_new_cat.setAttribute("required", "");
  }
  function change_bc2(x)
  {
    ctac.style.backgroundColor = '#d0d0d7';
  }
  function unchange_bc2(x)
  {
    ctac.style.backgroundColor = '#e9e9ed';
  }
  function change_bc()
  {
    ctac.style.backgroundColor = '#b1b1b9';
  }
  function unchange_bc()
  {
    ctac.style.backgroundColor = '#e9e9ed';
  }
  // select_div
  function sel_change_bc2(x)
  {
    select_div.style.backgroundColor = '#d0d0d7';
  }
  function sel_unchange_bc2(x)
  {
    select_div.style.backgroundColor = '#e9e9ed';
  }
  function sel_change_bc()
  {
    select_div.style.backgroundColor = '#b1b1b9';
  }
  function sel_unchange_bc()
  {
    select_div.style.backgroundColor = '#e9e9ed';
  }

</script>
</body>
</html>