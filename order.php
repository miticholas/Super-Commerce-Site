<?php

session_start();
require '../secure_files/sitefunctions.php';
//set up the top of the page
getWebsiteHeader("Process Order");

userchecker();
function displayForm($amount)
{
    echo <<<ENDTAG
    <div class='box'>
        <h2 class='outline'>Thank you for placing your order from Superhero Center!</h2>
        <h3 class='outline'> Please enter your information below.</h3>
    
        <form class='center' action='#' method='POST'><p>
        Name on Card: <input type='text' name='name'><br>
        Card Number: <input type='text' name='ccnumber' value=''><br>
        Exp: <input type='text' name='ccexp'></p>
        <p class='blue bigOutline'> amount: \$$amount
        <input type='hidden' name='amount' value="$amount"></p>
        <p>Shipping Information:<br>
        City:<input type='text' name='city'><br>
        State:<input type='text' name='state'><br>
        Address:<input type='text' name='addr'><br>
        <input type='submit' name='submit' value='PURCHASE'>
        </p></form>
    </div>
ENDTAG;
}

// extract data from form
if(!empty($_POST['name']) && !empty($_POST['ccnumber']) && !empty($_POST['ccexp']))
{
    $name = $_POST['name'];
    $ccnumber = $_POST['ccnumber'];
    $ccexp = $_POST['ccexp']; // URL for processing the CC transaction
    $url = 'https://secure.networkmerchants.com/gw/api/transact.php';

    // All of the parameters for the CC transaction
    // NOTE: The document lists additional params that should be used.
    $data = array('type' => 'sale', 
    'username' => 'demo', 'password' => 'password',
    'ccnumber' => $ccnumber, 
    'ccexp' => $ccexp,
    'amount' => $amount,
    'firstname' => $name);

    // Prepare the HTML request along with post data
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);    
    // issue HTML request and get the results
    // NOTE: This should have error checking. 
    // Check the documentation for file_get_contents to see what can go wrong.
    $result = file_get_contents($url, false, $context);

    // Echo the results back to the user.
    // NOTE: Real code should parse these results and do something
    // useful with them.
    //echo "<pre>\n";
    ob_start();
    var_dump($result);
    $cardInfo = ob_get_clean();
    $cardInfoArray=explode('&',$cardInfo);
    $success=$cardInfoArray[1];
    if($success=='responsetext=SUCCESS')
    {//TO DO trim down with regular expression to just success or not
       echo "<p>Your order was a success!</p>";
       removeAllFromCart(getUsername()); //remove items from cart
    }
    else
        echo "<p class='red'>Incorrect credit card information given </p>";
}

if(empty($_POST))
{
    header("Location: cart.php");
    exit();
}
if (empty($_POST['amount']))
{
    header("Location: cart.php?checkout=nope");
    exit();
}
else
{
    $amount = $_POST['amount'];
    displayForm($amount);
}
//check if stuff is empty


getWebsiteFooter(getUsername());
?>