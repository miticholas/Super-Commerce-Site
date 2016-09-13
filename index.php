<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

getWebsiteHeader("SuperHero Center!"); //tab title input and rest of html before specific page content

//specific page content
checkURL();

echo <<<BODY
 		<img src="Imgs/superteam.png" alt="SUPER SQUAD">
BODY;

getWebsiteFooter(outputGreeting());
?>