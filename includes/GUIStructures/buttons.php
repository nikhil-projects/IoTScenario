<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
Example:
<?php 
new displayItem('uno', 'Sensor1', '0.0', 'cm', 'termometro','var(--deepRed)' ,'var(--red)');

new displayItem('uno', 'Sensor2', '0.0', 'cm', 'agua','var(--deepPurple)' ,'var(--purple)');

new displayItem('uno', 'Sensor3', '0.0', 'cm', 'metro','var(--deepBlue)' ,'var(--blue)');
 ?>
-->

<link rel="stylesheet" href="includes/GUIStructures/buttons.css">
<script src="includes/GUIStructures/buttons.js"></script>
<?php 
/**
* Display 
*/
class displayItem
{
	var $ide;
	var $name;
	var $value;
	var $unit;
	var $icon;
	var $color1;
	var $color2;

	function __construct($ide, $name, $value, $unit, $icon, $color1, $color2)
	{
		$this->ide = $ide; //The id html tag
		$this->name = $name;
		$this->value = $value;
		$this->unit = $unit; // the units cm, C ...
		$this->icon = $icon; // The image icon in .png
		$this->color1 = $color1; //The top title color
		$this->color2 = $color2; //The bottom color
		$this->make();

	}

	//Render the HTML
	function make(){
		echo ('<div class="display" id="'.$this->ide.'" style = "background:'.$this->color2.';">
	<div class="top" style="background:'.$this->color1.';"><p>'.$this->name.'</p></div>
	<div class="bottom">
		<ul>
			<li><img src="resources/icons/'.$this->icon.'"></li>
			<li id="SensorVal'.$this->ide.'">'.$this->value.'</li>
			<li>'.$this->unit.'</li>
		</ul>
	</div>
		<div class= "doptions">
			<ul>
				<li class="Matlab" id="mat'.$this->ide.'">Export to Matlab</li>
				<li class="Report" id="rep'.$this->ide.'">Make Report</li>
				<li class="Delete" id="del'.$this->ide.'">Delete samples</li>
			</ul>
		</div>
	</div>
	
	');
	}



}

?>










