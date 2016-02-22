<!DOCTYPE html>
<html class="no-js">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" type="text/css" href="css/lsl.css">
    <link rel="icon" type="image/png" href="./images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="./js/jquery-ui-1.11.4.custom/jquery-ui.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
    <script type="text/javascript" src="./js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.ui.timepicker.addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
	<title>LSL</title>
</head>
<script type="text/javascript">$(document).ready(function(){ $('.no-js').removeClass( "no-js" ).addClass( "js" );});</script>


<noscript>
 Sur votre navigateur, JavaScript est DÉSACTIVÉ.
 Ce site utilise JavaScript pour vous proposer la meilleure expérience du Web.
 Suivez ces <a href="http://javascripton.com/" target="_blank">instructions pour activer JavaScript dans votre navigateur</a>
</noscript>



<?php 

include_once('./includes/utils.php');

if(check()){
	include_once("./pages/main.php");
}
else{
	include_once("pages/connection.php");
}

?>