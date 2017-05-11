<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Módulo que cuenta el 
número de dispositivos, sensores ... registrados
en la plataforma
====================
-->

<?php 

/**
* 
*/
//include '../ComDB/DBManager.php';
include '../config/config.php';
class CounterModule
{
	/*
		Cantidad de sensores: SELECT COUNT(s.idSensor) as c FROM Sensor s
		Cantidad de dispositivos: SELECT COUNT(d.idDevices) c nd FROM Devices d
		Cantidad de actuadores: SELECT COUNT(a.idSensor) as c FROM Actuator a
	*/

	var $nDevices; 
	var $nSensors;
	var $nActuators;
	function __construct()
	{
		$this->make();
		
	}

	//Obtiene el valor de  numero de sensores ...
	function getValues(){
		$db = new SDBManager();
		$nD = $db->Read('SELECT COUNT(d.idDevices) as c FROM Devices d');
		$ns = $db->Read(' SELECT COUNT(s.idSensor) as c FROM Sensor s');
		$na = $db->Read('SELECT COUNT(a.idSensor) as c FROM Actuator a');
		$nal = $db->Read('SELECT COUNT(a.idAlarm) as c
			FROM Alarm a');

		$this->nDevices = $nD["c"]; 
		$this->nSensors = $ns["c"];
		$this->nActuators = $na["c"];
		$this->nAlarms = $nal["c"];

	}//End get values

	function make(){
		$this->getValues();

		echo '<div class="grida-hor">
	<div class="hor-element">
		<div class="namebar" style="background:var(--blue);">
			<h4>Total Devices</h4>
		</div>
		<p style="color:var(--blue);">'.$this->nDevices.'</p>
	</div>

	<div class="hor-element">
		<div class="namebar" style="background:var(--orange);">
			<h4>Total Sensors</h4>
		</div>
		<p style="color:var(--orange);">'.$this->nSensors.'</p>
	</div>

	<div class="hor-element">
		<div class="namebar" style="background:var(--red);">
			<h4>Total Actuators</h4>
		</div>
		<p style="color:var(--red);">'.$this->nActuators.'</p>
	</div>
	
	<div class="hor-element">
		<div class="namebar" style="background:var(--purple);">
			<h4>Total Alarms</h4>
		</div>
			<p style="color:var(--purple);">'.$this->nAlarms.'</p>
	</div>
</div>';

	}


}

?>

<?php //new CounterModule(); ?>

