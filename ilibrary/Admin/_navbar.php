<?php 
    session_start(); 
    date_default_timezone_set("Asia/Kolkata");
    if(!isset($_SESSION["admin_user"]))
    {   
    header("Location: /ilibrary/_Login/Home.php");
    exit();   
    }
?>

<nav id="navbar">

    <!------------------------------ logo and home ----------------------------->
    
    <div class="navitems" id="nav_home">
        <a id="home_link" href="/ilibrary/Admin/Home.php" title="Home">
            <img id="nav_img" src="/ilibrary/xtra/logo.png" alt="Logo">
            iLibrary 
        </a>
    </div> 

    <!--------------------------------- search --------------------------------->
    
    <form class="navitems" id="nav_search" action="/ilibrary/Admin/Home.php" method="post" autocomplete="off">
        <button id="src_btn" type="submit" name="subsrc" title="Search">
            <img src="/ilibrary/xtra/src.png" alt="Src">
        </button>
        <input id="input_src" type="text" name="src" placeholder="Search for Books" size='30' required>
    </form>

  <div id="matching_width">

       <!--------------------------------- dashboard --------------------------------->

    <div id="nav_dash" onclick="dash_menu()">
        <img id="dash_icon" onclick="highlightd()" onmouseover="lowlightd()" onmouseout="nolightd()" title="Dashboard" src="/ilibrary/xtra/dash.png" alt="Dash">
        <div id="ddropdown" class="dropdown_menu"> 
            <a class="dd_buttons" href="/ilibrary/Admin/Pages/Add.php">Add Books</a>
            <a class="dd_buttons" href="/ilibrary/Admin/Pages/Issue.php">Issue Books</a>
            <a class="dd_buttons" href="/ilibrary/Admin/Pages/Return.php">Return Books</a>
            <a class="dd_buttons" href="/ilibrary/Admin/Pages/Std_Reg.php">Registrations</a>
            <a class="dd_buttons" href="/ilibrary/Admin/Pages/Student.php">Students</a>
        </div>  
    </div>

    <!--------------------------------- account --------------------------------->

    <div id="nav_profile" onclick="profile_menu()">
        <img id="profile_icon" onclick="highlight()" onmouseover="lowlight()" onmouseout="nolight()" title="Account" src="/ilibrary/xtra/user.png" alt="Acc">
        <div id="dropdown" class="dropdown_menu"> 
            <a class="dd_buttons" href="/ilibrary/Admin/Pages/Account.php">Account</a>
            
            <!------------------------------ logout ----------------------------->
            <form class="dd_buttons" method="post" action="/ilibrary/_Login/Home.php">
                <button id="logout_btn" type="submit" name="admin_out">Logout</button>
            </form> 
        </div>  
    </div>

</div>
</nav>

<script>

function dash_menu()
{
    ddropdown.classList.toggle("show");
    dropdown.classList.remove('show');

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(eventd) 
    {
        if(!eventd.target.matches('#dash_icon')) 
        {

            if (ddropdown.classList.contains('show')) 
            {
                ddropdown.classList.remove('show'); 
            }
        }   
    }
} 

function profile_menu()
{ 
    dropdown.classList.toggle("show");
    ddropdown.classList.remove('show');

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(eventc) 
    {
        if(!eventc.target.matches('#profile_icon')) 
        {
            
            if (dropdown.classList.contains('show')) 
            {
                dropdown.classList.remove('show');
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


function nolightd()
{
    if(!ddropdown.classList.contains('show'))
    {
    dash_icon.style.backgroundColor = 'transparent';
    }
}
function lowlightd()
{
    if(!ddropdown.classList.contains('show'))
    {
    dash_icon.style.backgroundColor = '#f1f3f4';
    dash_icon.style.borderRadius  = '25px';
    }
}
function highlightd()
{
    if(!ddropdown.classList.contains('show'))
    {
    dash_icon.style.backgroundColor = '#d9dadb';
    dash_icon.style.borderRadius  = '25px';
    }
    else
    {
    dash_icon.style.backgroundColor = '#f1f3f4';
    dash_icon.style.borderRadius  = '25px';
    }
}
document.addEventListener('click', function(event2)
{
    if (!dash_icon.contains(event2.target)) 
    {
    dash_icon.style.backgroundColor = 'transparent';    //nolight dash icon
    }
});

</script>