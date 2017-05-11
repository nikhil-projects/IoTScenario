<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
-->

<!DOCTYPE html>
<html lang="en">
<?php  
session_start();
if(!isset($_SESSION['user_id'])){
   header("Location:index.php");
}
?>

<head>
	<meta charset="UTF-8">
	<title>IoTScenario</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400" rel="stylesheet">
	<link rel="stylesheet" href="common/style.css">
	<link rel="stylesheet" href="config/colorPalette.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

</head>
<body>

<!--We include the top bar -->
<?php include 'includes/top-bar/top-bar.php'; ?>
<!--We include the option left bar -->
<?php include 'includes/left-bar/left-bar.php'; ?>
<!--We include main page -->
<script>
$(document).ready(function(){
	$.ajax({
 	 	url: "pages/" + "Home" + ".php"
	}).done(function(data) { // data what is sent back by the php page
  		$('.main').html(data); // display data
	});
});
</script>
<!--Main Div-->
<div class="main"></div>

</body>
</html>
