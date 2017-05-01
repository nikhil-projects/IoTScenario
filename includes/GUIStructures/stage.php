<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
<?php 
	new stageItem('1', 'Escenario pepe', 12, 'stageDefaultItem.png');
	new stageItem('1', 'Escenario pepe', 12, 'stageDefaultItem.png');
	new stageItem('1', 'Escenario pepe', 12, 'stageDefaultItem.png');
	new stageItem('1', 'Escenario pepe', 12, 'stageDefaultItem.png');

 ?>

-->
<link rel="stylesheet" href="includes/GUIStructures/stage.css">
<link rel="stylesheet" href="common/style.css">
<link rel="stylesheet" href="config/colorPalette.css">

<script src="includes/GUIStructures/stage.js"></script>
<?php 

class stageItem
{
	var $ide; //The id html tag
	var $name; //The stage name
	var $numberdevices;  // The number of devices that contains
	var $image; //The stage image in .jpg or .png

	function __construct($ide, $name, $numberdevices, $image){
		$this->ide = $ide;
		$this->name = $name;
		$this->numberdevices = $numberdevices;
		$this->image = $image;
		$this->make();
	}

	//Render the html
	function make(){
		echo '<div class="stageItem" id="'.$this->ide.'">
		<div class="img" style="background-image: url(resources/servImages/'.$this->image.');"></div>
		<div class="data">
			<ul>
				<li>'.$this->name.'</li>
				<li>'.$this->numberdevices.' Devices</li>
			</ul>
		</div>
		</div>';

	}
}

?>







