<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

getWebsiteHeader("Beacon Us!"); //tab title input and rest of html before specific page content
checkURL();
//specific page content
echo <<<BODY
	<h2 class = "yellow outline"> Contact Us </h2>
    <p class="black">To contact us about the page, you can email us at</p>
    <h3 class="blue bigOutline"> ups.superherocenter@gmail.com </h3>
    <p class="black"><br>Despite the fact that this is site is purely for academic purposes, the email address is legitimate.</p>
BODY;

getWebsiteFooter(outputGreeting());
?>
