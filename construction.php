<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

getWebsiteHeader("Heroes Needed!"); //tab title input and rest of html before specific page content

//specific page content
checkURL();
echo <<<BODY
  <h2 class="construction">
  This page is currently under construction. 
  Sorry! Please try again later.</h2>
BODY;

getWebsiteFooter(outputGreeting());
?>