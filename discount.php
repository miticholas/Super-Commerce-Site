<?php
  session_start(); //needed to check session variables
  require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

  userChecker(); //in order to view this page user must be logged in
  getWebsiteHeader("Save Mr. Money!"); //tab title input and rest of html before specific page content

  //specific page content
  checkURL();
  echo <<<PAGE
    <div class = 'box'>
      <h2 class="outline"> Discount Codes </h2>
      <p class="blue bigOutline">Thank you for choosing to be a Super Member!™ <br>
      As a token of our gratitude, we proudly present these discount codes below 
      that will take
      <br><em class="red">15% off</em><br>
      your next order upon use!</p>
      <fieldset><legend class="black center">MEMBER CODES</legend>
      <h3 class="green outline"> 7h38aTC4v3iSNdRW4yNeM^n0r </h3>
      <h3 class="red outline"> p4sW0R9=|{r0No$ </h3>
      <h3 class="yellow outline"> 0z7h3gr3a74ndp0w3rfu1 </h3>
      </fieldset>
      <br>
      <p class="blue bigOutline">To use, enter the code in the Promotional Code
       text area during the checkout process</p>
    </div>
PAGE;

  getWebsiteFooter(outputGreeting());
?>