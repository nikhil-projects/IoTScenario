<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
-->
<?php //session_start();	 ?>
<link rel="stylesheet" href="includes/top-bar/top-bar.css">
<link rel="stylesheet" href="includes/GUIStructures/buttons.css">
<script src="includes/top-bar/top-bar.js"></script>

<?php $user = $_SESSION["user_id"]; 
$name = $_SESSION["name"];
?>

<div class="topbar">
	<h1><b>IoT</b>Scenario</h1>

	<div class="user">	
		<div class="fto"></div>
		<p><?php echo $user; ?></p>
	</div>

	<div class="logout" align="center">
		<div class="rec">
			<div class="fto" id="fto"></div>
		<br>
			<h3><?php echo $name; ?></h3>
			<br>
		</div>
		<br>
		<p class="btn red"> Sign Out</p>
	</div>
</div>