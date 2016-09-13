<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

getWebsiteHeader("Heroic Privacy Policy"); //tab title input and rest of html before specific page content

//specific page content
echo <<<BODY
      <div class='box'>
      <h2 class="outline"> Privacy Policy </h2>
      <h2 class="green" style="font-size:24px"> Personally Identifiable Order Information </h2>
      
      <p class="black">When you place an order for products or services we collect certain types of 
      personally identifiable information required to fulfill the product transaction, including:
      (1)&nbspcontact information for membership and shipping;
      (2)&nbsppayment information for billing;
      and (3)&nbspverification information for recovery of an account or proof of purchase.</p>
      
      <p class="black">We do not release contact information to any third parties and use contact information
      solely to communicate with the consumer in the following circumstances:
      (1)&nbspto email order confirmations; 
      (2)&nbspto respond to the consumer's inquiry;
      (3)&nbspto email signed up members product updates;
      and (4)&nbspto ship purchased goods to the proper destination.
      Members may at any time opt out of receiving marketing emails by clicking the unsubscribe 
      link at the bottom of our e-mail. </p>

      <br><h2 class="green">Links</h2>
      <p class="black">This site may contain links to other websites whose information practices may 
      be different. This Privacy Policy does not cover any third party website's use of personally identifiable 
      information and cannot be held responsible for their actions. </p>
 
      <br><h2 class="green">Legal Process/Disclaimer</h2>
      <p class="black">We may disclose personally identifiable information in response to or in 
      connection with judicial or regulatory proceedings or when we believe in good faith that the law 
      requires.</p>

      <br><h2 class="green">Updates and Notification of Changes to this Privacy Policy</h2>
      <p class="black">We reserve the right to modify the Privacy Policy at any time. Members will be notified
      of material changes to this Privacy Policy by way of email.</p>

      <br><h2 class="green">Special Rules for Children.</h2>
      <p class="black">Children under the age of 13 are not allowed to make orders from this site, nor 
      will we knowingly collect information from them.</p>

      <br><h2 class="green">Acceptance of this Privacy Policy</h2>
      <p class="black">By visiting this web site, you are accepting the practices described in this Privacy 
      Notice.</p>

      <p class="black">Last Updated April 2014</p>
      </div>
BODY;
getwebsiteFooter(outputGreeting());
?>