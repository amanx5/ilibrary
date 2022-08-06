<?php 
include '_navbar.php';    //navbar
$conn = mysqli_connect("localhost","root","","ilibrary");
if(!$conn) die("<br>Could not connect to the server");

echo '<div id="container_cb"><div id="category_bar">';     //category_bar
echo "<form method='post'> 
<button id='viewallbtn' class='cb_item' type='submit' name='subviewall'>All</button>
</form>";

$query = "select DISTINCT B_Category from book ORDER BY B_Category";
$category_list = mysqli_query($GLOBALS['conn'], $query);

while($categ = mysqli_fetch_array($category_list))
{
  extract($categ);
  $btnname = str_replace(' ', '', $B_Category);
  echo "<form method='post'> 
  <input type='hidden' name='view' value='$B_Category'>
  <input type='hidden' name='btn' value='$btnname'>
  <button id='$btnname' class='cb_item' type='submit' name='catview'>$B_Category</button>
  </form>";

}
echo "<div id='empty_div'></div>";

echo '</div></div>';    //category_bar closed

$category = mysqli_query($conn, $query);
echo '<div id="home_books" class="allcategory">';   //home_books
while($categories = mysqli_fetch_array($category))
{
extract($categories);
categorycall($B_Category);
}

function categorycall($category)
{
 echo "<div class='category_header'>
        <p id='category_name'>$category</p>
          <form id='seemoreform'action='Home.php' method='post'> 
            <input type='hidden' name='view' value='$category'>
            <button id='seemore' type='submit' name='subview'>See more</button>
          </form>
 
      </div>";   //category_header closed

  echo "<div class='one_category'>";

      $query = "select * from book where B_Category='$category' LIMIT 10";
      $book = mysqli_query( $GLOBALS['conn'] , $query);

        if(mysqli_num_rows($book) > 0)
        {   
          while($bookinfo = mysqli_fetch_array($book))
          {
            extract($bookinfo);
            $Status = ($B_Status==0?"Available":"Issued");

            echo 
            "<div class='bookpreview'> ".
              '<img class="bookimg" src="data:image/jpeg;base64,'.base64_encode($B_Photo).'">'.
              "<p title='Book No. $B_Sno $B_Name' class='bname'>$B_Name</p>
               <p title='$B_Author' class='bauthor'>$B_Author</p>
               <p class='bstatus'>$Status</p>
            </div>";    //bookpreview closed
          }
        }

  echo "</div>";    //one_category closed
} 

echo'</div>'; //home_books closed

if(isset($_POST['subsrc']))
{
echo "<script>container_cb.style.display = 'none';
home_books.style.display = 'none';</script>";
extract($_POST);

$query = "select * from book where B_Name LIKE'%$src%' or B_Category LIKE'%$src%' or B_Sno ='$src'";
$book= mysqli_query($conn , $query);

echo '<div id="srcbooks" class="allcategory">';   //srcbooks

  if(mysqli_num_rows($book) > 0)
  {   
   echo "<div class='search_heading'>Results based on '$src' $subsrc</div>";
  
   echo "<div class='view_category'>";
  
    while($bookinfo = mysqli_fetch_array($book))
    {
     extract($bookinfo);
     $Status = ($B_Status==0?"Available":"Issued");
   
     echo 
     "<div class='bookpreview'>".
     '<img  class="bookimg" src="data:image/jpeg;base64,'.base64_encode($B_Photo).'">'.
       "<p title='Book No. $B_Sno $B_Name' class='bname'>$B_Name</p>
        <p title='$B_Author'class='bauthor'>$B_Author</p>
        <p class='bstatus'>$Status</p>
     </div>";
    }
    echo "</div>";
  }
  else echo "<div class='search_heading'>We couldn't find anything for your search - $src</div>"; 
}
echo '</div>';    //srcbooks closed

if(isset($_POST['subviewall']))
{
  extract($_POST);
  echo "<script>home_books.style.display = 'none';
  viewallbtn.style.backgroundColor = 'black';
  viewallbtn.style.borderColor = 'black';
  viewallbtn.style.color = 'white';
  </script>";
 
  echo '<div class="allcategory">';    //viewbooksALL
  $query_allbook= "select * from book";
  $book=mysqli_query($conn,$query_allbook);

    if(mysqli_num_rows($book) > 0)
    {  
      echo "<div class='category_header'>Books on all categories</div>";
      echo "<div class='view_category'>";
  
        while($bookinfo = mysqli_fetch_array($book))
        {
          extract($bookinfo);
          $Status = ($B_Status==0?"Available":"Issued");
          echo 
          "<div class='bookpreview'> ".
          '<img class="bookimg" src="data:image/jpeg;base64,'.base64_encode($B_Photo).'">'.
          "<p title='Book No. $B_Sno $B_Name' class='bname'>$B_Name</p>
           <p title='$B_Author' class='bauthor'>$B_Author</p>
           <p class='bstatus'>$Status</p>
          </div>";
        }
      echo "</div>";    //view_category closed
    }
  echo '</div>';    //viewbooks closed
}

if(isset($_POST['catview']))
{
  extract($_POST);
  echo "<script>home_books.style.display = 'none';
  document.getElementById('$btn').style.backgroundColor = 'black';
  document.getElementById('$btn').style.borderColor = 'black';
  document.getElementById('$btn').style.color = 'white';
  </script>";
 
  echo '<div class="allcategory">';    //viewbooks
   $query = "select * from book where B_Category='$view'";
   $book= mysqli_query($conn , $query);
 
    if(mysqli_num_rows($book) > 0)
    {   
      echo "<div class='category_header'>Books on $view</div>";
      echo "<div class='view_category'>";
  
        while($bookinfo = mysqli_fetch_array($book))
        {
          extract($bookinfo);
          $Status = ($B_Status==0?"Available":"Issued");
          echo 
          "<div class='bookpreview'> ".
          '<img class="bookimg" src="data:image/jpeg;base64,'.base64_encode($B_Photo).'">'.
          "<p title='Book No. $B_Sno $B_Name' class='bname'>$B_Name</p>
           <p title='$B_Author' class='bauthor'>$B_Author</p>
           <p class='bstatus'>$Status</p>
          </div>";
        }
      echo "</div>";    //view_category closed
    }
  echo '</div>';    //viewbooks closed
}

if(isset($_POST['subview']))
{
  extract($_POST);
  echo "<script>home_books.style.display = 'none';</script>";
 
  echo '<div class="allcategory">';    //viewbooks
   $query = "select * from book where B_Category='$view'";
   $book= mysqli_query($conn , $query);
 
    if(mysqli_num_rows($book) > 0)
    {   
      echo "<div class='category_header'>Books on $view</div>";
      echo "<div class='view_category'>";
  
        while($bookinfo = mysqli_fetch_array($book))
        {
          extract($bookinfo);
          $Status = ($B_Status==0?"Available":"Issued");
          echo 
          "<div class='bookpreview'> ".
          '<img class="bookimg" src="data:image/jpeg;base64,'.base64_encode($B_Photo).'">'.
          "<p title='Book No. $B_Sno $B_Name' class='bname'>$B_Name</p>
           <p title='$B_Author' class='bauthor'>$B_Author</p>
           <p class='bstatus'>$Status</p>
          </div>";
        }
      echo "</div>";    //view_category closed
    }
  echo '</div>';    //viewbooks closed
}

echo '<script>
  category_bar.addEventListener("wheel", (evt) => {
  evt.preventDefault();
  category_bar.scrollLeft += evt.deltaY; });
</script>';
?>

