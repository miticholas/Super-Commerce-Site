<?php
define ("FILENAME", "../secure_files/passwords.txt");
define ("USERHASH", "//ecommerce.pugetsound.edu/~wing/"); //to insure hash of user is unique
require '../secure_files/PasswordHash.php'; //for creating protected password storage

/* STRUCTURE FUNCTIONS */

/* code that makes up the header for every page. $title input allows for
unique title for each page */
function getWebsiteHeader($title)
{
  echo <<<HEADER
        <!DOCTYPE html>
        <html>
         <head>
          <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
          <meta name="Keywords" content="super hero center, costume, 
          collectible, product, store, batman, superman, green lantern, 
          captain america, hulk, MARVEL, DC, shopping">
          <meta name="Description" content="Super Hero Center is dedicated 
          to bringing you the highest quality superhero costumes and 
          accessories. Whether you're a D.C.™ fan and can't get enough 
          of the Caped Crusader™, or a MARVEL™ fan just in from New York 
          and you need a new red, white, and blue shield, we have what 
          you're looking for.">
          <title>$title</title>
          <link rel="shortcut icon" href="favicon.ico">
          <link rel="icon" href="favicon.ico">
          <link rel="stylesheet" type="text/css" href="splash.css">
         </head>
        <body>
       <div id="container">
          <div id="header">
            <a href="index.php"><img class="head" src="Imgs/heroHeader.png" 
            alt="SUPER HERO CENTER"></a>

            <h3 class="yellow outline">
             <a class="head" href="index.php">Home</a>
             | <a class="head" href="about.php">About Us</a>
             | <a class="head" href="productList.php">Products</a>
             | <a class="head" href="privacy.php">Privacy Policy</a>
             | <a class="head" href="contact.php">Contact Us</a>
             <br><a class="head" href="discount.php">Member Discounts</a>
             | <a class="head" href="cart.php">Shopping Cart</a></h3>
          </div>
          <div id="body">
HEADER;
}

/* code that makes up the footer for every page */
function getWebsiteFooter($greeting)
{
    echo <<<FOOTER
     </div>
     <div id='footer'>
      <a href='http://validator.w3.org/check?uri=referer'><img class='valid'
      src='http://www.w3.org/Icons/valid-html401' alt='Valid HTML 4.01 Strict'>
      </a>
      $greeting <span class='small'> Copyright © SuperHero Center, 2014 </span>
     </div>
    </div>
    </body>
    </html>
FOOTER;
}

/* SESSION FUNCTIONS */

/* when a user clicks to make a new account, checks if the username 
and password are valid and stores them safely in the database */
function createUser($username, $password)
{ //checks if the name was already taken
	if (userAlreadyExists($username))
    	return "$username already taken, please try again";
  else
  {  //checks that username is purely alphanumerical and over 7 characters
	  if(ctype_alnum($username) && strlen($username) > 7)
	  { //checks that the password is over 7 characters long
	  	if(strlen($password) > 7)
	  	{ //finally hash and store in password.txt file, making account
	  		$hasher = new PasswordHash(8,false) or die("unable to hash PW");
	  		$hash = $hasher->HashPassword($password);
	  		if (strlen($hash) < 20) die("Invalid hashed PW");
	  			$file = fopen(FILENAME, "a") or die("Unable to open pw file");
	  		fputs($file, $_POST['username'] . "," . $hash . "\n")
	  			or die("Unable to update pw file");
	  		fclose($file) or die("Unable to update pw file");
	  		loginUser($username, $password); //log them in, inform that is was a success
	  	}
	  	else { return "Password invalid: Must Be 8 or More Characters"; } }
	  else  { //otherwise username was invalid
	  	return "Username Invalid: Must Be 8 or More Characters 
	  			and Include Only Numbers and Letters";
		}
	}
}

/* retrieves the hashed password associated with the username
  returns false if none found by end of file */
function getHashedPW($username)
{
    $file = fopen(FILENAME, "r") or die("Unable to open pw file");
    while (!feof($file))
    {
        $user = fgetcsv($file);
        if ($user[0] == $username)
        {
            fclose($file);
            return $user[1];
        }
    }
    fclose($file);
    return false;
}

/* returns the username if user is logged in */
function getUsername()
{
    if (!userIsLoggedIn()) return false;
    return $_SESSION['user'];
}

//head function to log a user in, relocates user to previous webpage
function loginUser($username, $password)
{
	if (userIsLoggedIn()) session_destroy();
	if(validateCredentials($username, $password) == false)
		return "Invalid Username or Password";
  else
  {
      markUserLoggedIn($username);
      if (isset($_GET['dest']))
      {
          $dest = $_GET['dest'];
          header("Location: $dest");
      }
      else header("Location: index.php");
  }
}

/* If user is not logged in, redirect to login page
  user GET dest param to redirect here after successful login */
function mustBeLoggedIn()
{
    $dest = htmlspecialchars($_SERVER['REQUEST_URI']);
    if (!userIsLoggedIn()) 
    {
        header("refresh: 0.01; url=login.php?op=login&dest=$dest");
        exit();
    }
}

/* redirects the webpage so it goes through https */
function mustBeSecure()
{
    if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
    {
        $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header("Location: $redirect");
        exit();
    }
}

/* changes session variables to register a user as logged in */
function markUserLoggedIn($user)
{
    if (session_id() == '') session_start();
    $_SESSION['user'] = $user;
    $_SESSION['hash'] = md5("$user" . USERHASH);
}

/* output a greeting if a user is logged in */
function outputGreeting()
{
  $dest = htmlspecialchars($_SERVER['REQUEST_URI']);
    if (userIsLoggedIn())
    {
        $username = getUsername();
        return "<span class='login outline'>Welcome $username!</span>
        <a class='head' href=logout.php>LOG OUT</a>";
    }
    return "<span class='login outline'>Welcome Guest!</span>
        <a class='head' href=login.php?op=login&dest=$dest>LOG IN</a>";
}

/* returns whether the username has already been taken or not */
function userAlreadyExists($user)
{
    $hash = getHashedPW($user);
    if ($hash === false) return false;
    return true;
}

/* returns whether a user is marked as logged in or not */
function userIsLoggedIn()
{
    if (session_id() == '') return false;
    if (!isset($_SESSION['user'])) 
    {
        session_destroy();
        return false;
    }
    $user = $_SESSION['user'];
    if (!isset($_SESSION['hash']))
    {
        session_destroy();
        return false;
    }
    $hash = $_SESSION['hash'];
    if ($hash != md5($user . USERHASH))
    {
        session_destroy();
        return false;
    }
    return true;
}

/* code for pages and tasks that require a log in to use */
function userChecker()
{
  if(!userIsLoggedIn())
  {
  echo <<<ALERT
    <script type="text/javascript">
    alert('You must log in to have access to this feature');
    </script>
ALERT;
  mustBeLoggedIn();
  }
}

/* checks whether the username and password combo are correct */
function validateCredentials($username, $password)
{
    $hashed_pw = getHashedPW($username);
    if ($hashed_pw == false) //if no password is returned
        return false; //then username is nonexistent so invalid
    $hasher = new PasswordHash(8,false) or die("unable to hash PW");
    if ($hasher->CheckPassword($password, $hashed_pw)) 
        return true; //if password matches username we're golden
return false; //if we get to here we're not
}


/* DATABASE FUNCTIONS */

/* creates the connected database object */
function ConnectToDatabase()
{
    try
    {
        $db = new PDO('mysql:host=localhost;dbname=wing;charset=utf8',
             'wing', 'ihatesnakes', array(PDO::ATTR_EMULATE_PREPARES => false, 
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $db;

    }
    catch (PDOException $e)
    {
      echo "Error establishing a connection!";
    }
}

/* returns all the different heroes as a list of links */
function getCategories()
{
  $db = ConnectToDatabase();
  $query="SELECT DISTINCT HeroId FROM Products ORDER BY HeroId;";
  $result = $db -> query($query);

  echo "<div>
      <p class='center yellow outline bold'> CHOOSE YOUR HERO </p>
      <ul class='categories'>";
  echo "<li> <a class='nav' href='/~wing/productList?heroId=all'>
         All Heroes </a></li>";
  while ($row = $result->fetch(PDO::FETCH_ASSOC))
  {
    $hero = $row['HeroId'];
      echo <<<ITEM
          <li>
          <a class='nav' href='/~wing/productList?heroId=$hero'>
            $hero </a>
          </li>
ITEM;
  }
  echo "</ul></div>";
  $db = null;
}

/*  returns a tabled list of products that matches the
    the input category and sorting order */
function getProducts($category, $sort)
{
  $db = ConnectToDatabase();
  try //must use due to possibility of user abuse (because of the GETS in url)
  {
    if($category=="all") //special case where every product is returned
      $query="SELECT * FROM Products, Imgs WHERE PId=ProductId ORDER BY $sort";
    else
      $query="SELECT * FROM Products, Imgs WHERE PId=ProductId AND HeroId='$category' ORDER BY $sort";
    $result = $db -> query($query);
    $rows = $result -> rowCount();
    if($rows == 0) //if no products were returned, then we'll want to say so
    {
      echo "<h3 class='center green bigOutline'>
            No products found under that category </h3>";
    }
    echo "<table id='productTable'>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC))
    {//iterates through and creates row with info for each product
      $image = "/~wing/".$row['ImgFile'];
      $pid = $row['PId'];
      $product = $row['Product'];
      $hero = $row['HeroId'];
      $price = $row['Price'];
      $short = $row['ShortDescr'];
        echo <<<ITEM
            <tr><td>
             <a class='center product' href='/~wing/productDetail?pid=$pid'>
              <img class='product' src="$image" alt='product Image'> </a></td>
            <td>
             <a class='center green bigOutline' style="font-size: 30px" 
             href='/~wing/productDetail?pid=$pid'> $product </a><br>
             <a class='center blue outline' href='/~wing/productDetail?pid=$pid'>
              $hero </a></td>
            <td>
              <div class='black'> $short </div>
              <form class="center" action="" method="post">
                <p class='red bold italics'> \$$price <br>
                <input type="Submit" value="Add to Cart">
                <input type="hidden" name="pID" value="$pid"></p>
              </form></td>
            </tr>
ITEM;
    }
    echo "</table>";
  }
  catch (PDOException $e) //if mySQL breaks its because users did unexpected things
  {
    echo "<h3 class='center green bigOutline'>
         Improper search performed.<br> Please stick to the navigation bar </h3>";
  }
  $db = null;
}

function getProductNav($hero)
{
  echo "<div id='productNav'>";
  getCategories();
  echo <<<NAV
  <div class='center'>
    <form action="" method='GET'>         
        <p class='yellow outline bold'> ORDER BY </p>
        <p><select name='order'>
         <option value='Price'>Price: Low to High</option>
         <option value='Price DESC'>Price: High to Low</option>
         <option value='Product'>Alphabetical A-Z</option>
         <option value='Product DESC'>Alphabetical Z-A</option>
       </select>
        <input type='hidden' name='heroId' value="$hero">
        <input type='submit' value='VIEW RESULTS'>
        </p>
    </form>
  </div>
</div>
NAV;
}

function getDetailPage($quantity, $pid, $hero)
{
  $db = ConnectToDatabase();
  try
  {
    $query="SELECT * FROM Products, Imgs WHERE PId=ProductId AND PId=$pid;";
    $result = $db->query($query);
    $rows = $result->rowCount();
    if($rows == 0) //if no products were returned, then we'll want to say so
    {
      echo "<h3 class='center green bigOutline'>
            Hmm... no product here it seems </h3>";
    }
    else
    {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      $image = "/~wing/".$row['ImgFile'];
      $pID = $row['PId'];
      $product = $row['Product'];
      $hero = $row['HeroId'];
      $price = $row['Price'];
      $long = $row['LongDescr'];

      echo <<<ITEM
        <table class='product'>
          <tr><td colspan='2'><h2 class='yellow bigOutline'> $product </h2>
            <h3 class='blue bigOutline'> $hero </h3></td></tr>
          <tr><td class='description'>
            <img class='detail' src="$image" alt='BIG PRODUCT IMAGE'></td>
            <td class='description'><br>
              <div class='black'> $long </div>
              <div class='italics bold red'> \$$price </div>
              <form class="center" action="" method="post">
                <div class="blue outline"><br>
                  Quantity:<br> <input type="number" name="quantity" max="10" min="1" value="1">
                  <input type="Submit" value="Add to Cart">
                  <input type="hidden" name="pID" value="$pid">
                </div>
              </form><br>
              <a class='yellow bigOutline center' href="productList.php?heroId=$hero">
                BACK to Product List </a>
            </td>
          </tr>
        </table>
ITEM;
    }
  }
  catch (PDOException $e) //if mySQL breaks its because users did unexpected things
  {
    echo "<h3 class='center green bigOutline'>
         Hmm... no products found here it seems </h3>";
  }
  $db = null;
}

function getHero($pid)
{
  $db = ConnectToDatabase();
  try
  {
    $query="SELECT HeroId FROM Products WHERE PId=$pid;";
    $result = $db->query($query);
    while ($row = $result->fetch(PDO::FETCH_ASSOC))
    {
        $hero = $row['HeroId'];
        $db = null;
        return $hero;
    }
  }
  catch (PDOException $e) //if mySQL breaks its because users did unexpected things
  {
    $db = null;
  }
}

function getProductName($pid)
{
  $db = ConnectToDatabase();
  $query="SELECT Product FROM Products WHERE PId=$pid;";
  $result = $db->query($query);
  while ( $row = $result->fetch(PDO::FETCH_ASSOC))
  {
      $product = $row['Product'];
      $db = null;
      return $product;
  }
}
function showAddMessage($pid, $quantity)
{
  $name = getProductName($pid);
  echo "<fieldset class='smallBox center blue outline'> You now have $quantity of the \"$name\" in your cart </fieldset>";
}

function addToCart($user, $pID, $quantity)
{
  $db = ConnectToDatabase();
  try
  {
    $query = $db->prepare("SELECT Num FROM Cart WHERE UserId=? AND ProductId=?;");
    $query->execute(array($user, $pID));
    $rows = $query->rowCount();
    if($rows == 0) //if product doesn't already exist in cart, we'll insert new row
    {
      $query = $db->prepare("INSERT INTO Cart(UserId, ProductId, Num) VALUES(?, ?, ?);");
      $query->execute(array($user, $pID, $quantity));
      $currentAmount = 0;
    }
    else
    {
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $currentAmount = $row['Num'];
      $query=$db->prepare("UPDATE Cart SET Num=? WHERE UserId=? and ProductId=?;");
      $query->execute(array($currentAmount+$quantity, $user, $pID));
    }
    $product = getProductName($pID);
    showAddMessage($pID, $currentAmount+$quantity);
  }
  catch (PDOException $e) {echo "there was an error along the way";}
  $db = null;
}

function getUsersCart($user)
{
  $db = ConnectToDatabase();
  $query = $db -> prepare("SELECT Product, Num, Price, PId FROM Cart, Products WHERE ProductId=PId and UserId=?;");
  //$result=$db->query($query);
  $query->execute(array($user));

  echo <<<TABLE
    <table class="checkout center">
    <tr class="green outline"><th style="width:30%">Item Name</th>
    <th style="width:25%">Quantity</th>
    <th style="width:15%">Item Cost</th>
    <th style="width:20%">Total Cost</th></tr>
TABLE;

    $total = 0;
  while ($row = $query->fetch(PDO::FETCH_ASSOC))
  {
    $product = $row['Product'];
    $quantity = $row['Num'];
    $price = $row['Price'];
    $pTotal = $price*$quantity;
    $total += $pTotal;
    $pid = $row['PId'];
    echo <<<ROW
    <tr><td>$product</td>
    <td>
      <form action="" method="POST">
      <div class='center'>
       <input type="hidden" name="pid" value="$pid">
       <input type="hidden" name="user" value="$user">
       Quantity:<br> <input type="number" name="quantity" max="10" min="1" value="$quantity">
       <input type="Submit" name="submit" value="Update">
      </div>
      </form></td>
    <td>\$$price</td>
    <td>
      <form class='center' action='' method='POST'>
      <div>
       \$$pTotal
       <input type='hidden' name='pid' value="$pid">
       <input type='hidden' name='user' value="$user">
       <input type='submit' name='submit' value='REMOVE'>
      </div>
      </form>
    </td>
ROW;
  }
  echo <<<TABLE
  <tr><td colspan='3'></td><td>Total: \$$total</td>
  </table>
  
  <p><form class='center' action='order.php' method='POST'>
    <input type='submit' name='submit' value='Proceed to Checkout'>
    <input type='hidden' name='amount' value='$total'>
  </form></p>
TABLE;

   $db = null;
}

function removeFromCart($user, $pID)
{
  $db = ConnectToDatabase();
  $query = $db -> prepare("DELETE FROM Cart WHERE UserId=? and ProductId=?;");
  if ($query === false)
  {
    $error = $query->errorInfo();
    echo 'MySQL Error: ' . $error[2];
  }
  else $query -> execute(array($user, $pID));
  $db = null;
}

function removeAllFromCart($user)
{
  $db = ConnectToDatabase();
  $query = $db -> prepare("DELETE FROM Cart WHERE UserId=?;");
  if ($query === false)
  {
    $error = $query->errorInfo();
    echo 'MySQL Error: ' . $error[2];
  }
  else $query -> execute(array($user));
  $db = null;
}


function updateCart($user, $pID, $quantity)
{
  $db = ConnectToDatabase();
  try
  {
    $query = $db->prepare("SELECT Num FROM Cart WHERE UserId=? AND ProductId=?;");
    $query->execute(array($user, $pID));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $query=$db->prepare("UPDATE Cart SET Num=? WHERE UserId=? and ProductId=?;");
    $query->execute(array($quantity, $user, $pID));
    $product = getProductName($pID);
    showAddMessage($pID, $quantity);
  }
  catch (PDOException $e) {echo "there was an error along the way";}
  $db = null;
}
?>