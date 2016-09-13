<?php
  session_start();
  require '../secure_files/sitefunctions.php';
  
  getWebsiteHeader('Products for Heroes');
  checkURL();
  if(isset($_GET['heroId'])) //checks and sets the hero category
    $hero = htmlspecialchars($_GET['heroId']);
  else //if not given then set to all hero categories
    $hero = 'all';
  if(isset($_GET['order'])) //checks and sets the order to view
    $order = htmlspecialchars($_GET['order']);
  else //if not given set to price cheapest first
    $order = 'price';
  
  getProductNav($hero);

  //if user has pressed the "add to cart" button a post is received
  if(!empty($_POST))
  {
    userChecker(); //in order to add to cart user must be logged in
    addToCart(getUsername(), $_POST['pID'], '1'); //if good add item to cart
  }
  getProducts($hero, $order); //if button not pressed show products
  
  getWebsiteFooter(outputGreeting());
?>