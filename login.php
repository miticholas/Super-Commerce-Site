<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

//function to output login form with whatever message is desired as add on
function loginForm($message)
{
    if (isset($_GET['dest']))
    {
        $destination = $_GET['dest'];
        $dest = "?dest=$destination";
    }
    else
        $dest = "";
  echo <<<FORMSTUFF
    <form class='box center' action='login.php$dest' method='post'>
     <div class="warning noOutline"> $message </div>
     <p class="login bigOutline">
     username: <input type=text name=username><br>
     password: <input type=password name=password><br>
     <input type=submit name="submit" value="LOG IN">
     <input type=submit name="submit" value="CREATE NEW ACCOUNT"><br><br><br>

     <span class="black bold noOutline"> If you're a returning Super&nbspMember™ </span><br>
     please enter your username and password and click the "log in" button <br><br>
     <span class="black bold noOutline"> If this is your first time shopping with SuperHero&nbspCenter™ </span><br>
     Please enter your desired username and password and click the "Creat New Account" button<br></p>
     <p class="warning"> • Usernames and passwords must be 8 characters or longer and 
     contain only numbers or letters •<br>
    </form>
FORMSTUFF;
}
if(isset($_SESSION['user']))
{
  mustBeSecure(); //but make it secure, as we don't want snoopers
  getWebsiteHeader("Super Login Page!");  //tab title input and rest of html before specific page content
  echo "<h3 class='center green bigOutline'> 
        You are already logged in! <br>
        <a class='blue nav' href=logout.php> LOG OUT </a>
        </h3>";
}
else
{
  if (empty($_POST)) //if nothing has been submitted show empty form
  {
    mustBeSecure(); //but make it secure, as we don't want snoopers
    getWebsiteHeader("Super Login Page!");  //tab title input and rest of html before specific page content
    loginForm(""); //function to output specific page content, no message needed
  }
  else //otherwise they submitted something
  {
    getWebsiteHeader("Super Login Page!"); //tab title input and rest of html before specific page content
  
    $username = htmlspecialchars($_POST['username']); //do it once
    $password = htmlspecialchars($_POST['password']); //so never again

    //if they're attempting to create an account
    //use the createUser method and display the results on the form
    if ($_POST['submit'] == 'CREATE NEW ACCOUNT')
      loginForm(createUser($username, $password));
    //otherwise they're attempting to log in
    //so use the loginUser method and display those results	
    else
      loginForm(loginUser($username, $password, "?log=in"));
  }
}
getWebsiteFooter(outputGreeting());
?>