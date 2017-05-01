<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
-->

<link rel="stylesheet" href="includes/forms/forms.css">
<script src="includes/forms/forms.js"></script>
<?php 
	
	function LoadImages(){
		//Load images from folder
		$dirname = "../resources/icon-black/"; //Referencia del php
		$dir2 = "resources/icon-black/"; //Referencia del html

		$images = glob($dirname."*.png");
		$opendir = opendir($dirname);
		while (false !== ($filename = readdir($opendir))) {
			if($filename != '.' && $filename != '..'){
	    		$files[] = $dir2.$filename;
	    	}
		}
		return $files;
	}

	function displayIcons($files, $idName){
		$ic = 0;
		echo '<ul class="icons" id="icon'.$idName.'">';	
		foreach($files as $image) {
    		echo '<li id="icn'.$idName.$ic.'"><img src="'.$image.'"></li>';
    		$ic += 1;
		}
		echo '</ul>';
	}

	function displayColors($idName){
		echo '<select class="color" id="'.$idName.'"> 
				<option selected value="red">Red</option>
				<option value="blue">Blue</option>
				<option value="lgreen">Green</option>
				<option value="cyan">Cyan</option>
				<option value="purple">Purple</option>
				<option value="pink">Pink</option>
				<option value="turc">Teal</option>
				<option value="orange">Orange</option>
				<option value="black">Black</option>
			</select>';
	}
	$files = LoadImages(); //We load the images when the page is called.


?>


<!-- ====================HTML =======================-->
<!-- ============>LEFT SIDE =============-->
<!--<div class="but"></div>-->
<div class="screen">
	<div class="closescreen">&#10005;</div>
	<h1>> New Device Type</h1>
	<div class="body">
	
	<div class="iz">
	<form action="">
		<ul>
			
	<br>
		<li><h2>Device credentials</h2></li>
		<li><input type="text" placeholder="Device name" id="deviceName"></li>
		<li><input type="text" placeholder="Developer Name" id="devName"></li>
		<li><textarea name="description" id="description" cols="30" rows="10" placeholder="Description"></textarea></li>
		<li>
		<p>Icon</p>
		<br>
			<!--Cargamos las imagenes de icono -->				
			<?php displayIcons($files,'0');?>
			<!--Fin de imagenes de icono-->
		</li>
		<li>
			<p>Id Color</p><br>
			<?php displayColors('formDevices'); ?>
		</li>
			<li><div class="subBtn" value="Create Device" class="subimt"><p>Create Device</p></div></li>
		</ul>
	</form>
	</div>
	<!--Fin izquierda-->

<!--==================RIGHT SIDE =============-->
	<!--Derecha-->
	<div class="drch">
	<br><br>
		<ul>
		<li><div class="ic" id="addSensor">+</div><h2>Sensors</h2> 

		<form action="" id='SensorsForm'>
				<ul class='sensorTag0' id='sensor'>
				<div class="deleteSensor" id="sensorTag0"><p>&#10005;

</p></div>
				<?php  displayColors('color0');?>
				<input type='text' name='sensorName' placeholder='Sensor Signal Name' id="sensorName0">
				<input type='text' id="sensorUnits0" name='sensorUnits' placeholder='Samples units cm,C...'>
				<?php displayIcons($files,'1'); ?>
			   </ul>	
			   <!--Aqui van los sensores-->
		</form>

		</li>
		<li><div class="ic">+</div><h2>Actuators</h2></li>
	
		
		</ul>
	</div>
	<!--Fin derecha-->

</div>
</div>
