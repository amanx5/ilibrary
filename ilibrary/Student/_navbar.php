<!-------------------------- redirect to login page --------------------------->
<?php 
    session_start();  
    date_default_timezone_set("Asia/Kolkata");
    if(!isset($_SESSION["S_Roll"]))
    {   
    header("Location: /ilibrary/_Login/Home.php");
    exit();   
    }
?>

<nav id="navbar">

    <!------------------------------ logo and home ----------------------------->
    
    <div class="navitems" id="nav_home">
        <a id="home_link" href="/ilibrary/Student/Home.php" title="Home">
            <img id="nav_img" src="/ilibrary/xtra/logo.png" alt="Logo">
            iLibrary 
        </a>
    </div> 

    <!--------------------------------- search --------------------------------->
    
    <form class="navitems" id="nav_search" action="/ilibrary/Student/Home.php" method="post" autocomplete="off">
        <button id="src_btn" type="submit" name="subsrc" title="Search">
            <img src="/ilibrary/xtra/src.png" alt="Src">
        </button>
        <input id="input_src" type="text" name="src" placeholder="Search for Books" size='30' required>
    </form>  

  <div id="matching_width">
    <!--------------------------------- account --------------------------------->

    <div id="nav_profile" onclick="profile_menu()">
    <img id="profile_icon" onclick="highlight()" onmouseover="lowlight()" onmouseout="nolight()" title="Account" src="/ilibrary/xtra/user.png" alt="Acc">

        <div id="dropdown" class="dropdown_menu">
            <a class="dd_buttons" href="/ilibrary/Student/Profile.php">Profile</a>

            <!------------------------------ logout ----------------------------->
            <form class="dd_buttons" method="post" action="/ilibrary/_Login/Home.php">
            <button id="logout_btn" type="submit" name="std_logout">Logout</button>
            </form> 
        </div>  
    </div>
  </div>

</nav>

<script>
function profile_menu()
{ 
    document.getElementById("dropdown").classList.toggle("show");

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(eventc) 
    {
        if(!eventc.target.matches('#profile_icon')) 
        {
            if (document.getElementById("dropdown").classList.contains('show')) 
            {
                document.getElementById("dropdown").classList.remove('show'); 
            }
        }   
    }
} 

function nolight()
{
    if(!dropdown.classList.contains('show'))
    {
    profile_icon.style.backgroundColor = 'transparent';
    }
}
function lowlight()
{
    if(!dropdown.classList.contains('show'))
    {
    profile_icon.style.backgroundColor = '#f1f3f4';
    profile_icon.style.borderRadius  = '25px';
    }
}
function highlight()
{
    if(!dropdown.classList.contains('show'))
    {
    profile_icon.style.backgroundColor = '#d9dadb';
    profile_icon.style.borderRadius  = '25px';
    }
    else
    {
    profile_icon.style.backgroundColor = '#f1f3f4';
    profile_icon.style.borderRadius  = '25px';
    }
}
document.addEventListener('click', function(event)
{
    if (!profile_icon.contains(event.target)) 
    {
    profile_icon.style.backgroundColor = 'transparent'; //nolight profile icon  
    }
});

</script>