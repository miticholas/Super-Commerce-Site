<?php
session_start(); //needed to check session variables
require '../secure_files/sitefunctions.php'; //lets page use website-wide functions

setcookie (session_id(), "", time() - 3600);
session_destroy();

if (isset($_GET['dest']))
{
    $dest = $_GET['dest'];
    header("Location: $dest");
}
else header("Location: index.php");
?>