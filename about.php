<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

getWebsiteHeader("About Us Heroes"); //tab title input and rest of html before specific page content

//specific page content
checkURL();
echo <<<BODY
    <div class='box'>
        <h2 class="yellow outline"> About Us Heroes </h2>
        <p class="large blue bigOutline">Here at SuperHero Center™, we're dedicated to bringing you the highest 
        quality superhero costumes and accessories. Whether you're a D.C.™ fan 
        and can't get enough of the Caped Crusader™, or a MARVEL™ fan just in from 
        New York and you need a new red, white, and blue shield, we have what you're 
        looking for. If you can't find what you're looking for, you can make a 
        request for the item.</p>
        <br><br><br><br>
        <p class="warning">WARNING: This is not a real ecommerce site. 
        This page is part of the "E-Commerce" Computer Science course at the 
        University of Puget Sound.</p>
    </div>
BODY;

getWebsiteFooter(outputGreeting());
?>
