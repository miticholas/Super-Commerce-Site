<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

getWebsiteHeader("Product Details"); //tab title input and rest of html before specific page content
checkURL();
if(isset($_GET['pid']))
{
	$pid = htmlspecialchars($_GET['pid']);
	$hero = getHero($pid);
	getProductNav($hero);

	if(!empty($_POST))
	{
		userChecker(); //in order to add to cart user must be logged in
		$quantity = htmlspecialchars($_POST['quantity']);
		addToCart(getUsername(), $pid, $quantity);
		getDetailPage($quantity, $pid, $hero);
	}
	else
	{
		getDetailPage("", $pid, $hero);
	}
}
else
{
	echo "<h3 class='center green bigOutline'>
        Hmm... no product here it seems<br>
        Please go back to our products listing
        and use the links there </h3>";
}


getWebsiteFooter(outputGreeting());
?>