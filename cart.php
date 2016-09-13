<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

	userChecker(); //in order to view the cart user must be logged in
	checkURL();
	getWebsiteHeader("Super eCart Processing!");
	//specific page content 
	if(!empty($_GET['checkout']))
		echo "<fieldset class='box center blue outline'>
				Whoops! There's nothing in your cart to check out
				</fieldset>";
	if(!empty($_POST))
	{
		if($_POST['submit'] == "REMOVE")//removes items from the cart
			removeFromCart($_POST['user'], $_POST['pid']);
		if($_POST['submit']=="Update")//changes number of items in cart
			updateCart($_POST['user'],$_POST['pid'], $_POST['quantity']);
	}
	getUsersCart(getUsername());
	getWebsiteFooter(outputGreeting());
?>