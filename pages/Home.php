<?php 
	include '../ComDB/DBManager.php'; //Manage the DB connection
	include '../DshboardModules/DashModules.php'; //Modulo de mapas
?>

<link rel="stylesheet" href="pages/HomePlat.css">


<!--Módulo de contadores -->
<?php  new CounterModule(); ?>

<div class="grida-vert">
	<div class="left-bar">
		
		<?php new MyFirmwares();  ?>		
		
		
		<div class="var-element">
			<div class="title"><p>Example Empty Module</p></div>
			<div class="content">
				<br>
				<h1 align="center">Hello</h1>
				<h2 align="center">I'm an empty  <br> module example</h2>
			</div>
		</div>

			<div class="var-element">
			<div class="title"><p>Example Empty Module</p></div>
			<div class="content">
				<br>
				<h1 align="center">Hello</h1>
				<h2 align="center">I'm an empty left bar  <br> module </h2>
			</div>
		</div>

		<!--<div class="var-element">
			<div class="title"><p>Hola</p></div>
			<div class="content"></div>
		</div>-->

	</div><!--Fin Barra izquierda-->
	
	<div class="right-bar">
				
		<?php new DeviceGeo("minimap","Devices GeoLocation","AIzaSyCi-u22RsFHd_p3HjEoZbeTy820pCChWlU"); ?>
		<!--<?php //new MapGeo('SIP', 'Server GeoLocation','asd'); ?>-->


		

		
		
	</div>
</div>