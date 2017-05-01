
<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
Example
<?php 
	new dropDownItem(True,'2','termometro', 'test', 'aaaa',['sensor1','sensor2'], ['sensor1', 'sensor2'], ['alarm1'], 'miStage');
		new dropDownItem(True,'2','sol', 'test', 'aaaa',['sensor1','sensor2'], ['sensor1', 'sensor2'], ['alarm1'], 'miStage');
			new dropDownItem(True,'2','gota', 'test', 'aaaa',['sensor1','sensor2'], ['sensor1', 'sensor2'], ['alarm1'], 'miStage');
	new dropDownItem(True,'2','regla', 'test', 'aaaa',['sensor1','sensor2'], ['sensor1', 'sensor2'], ['alarm1'], 'miStage');

?>
====================
-->
<link rel="stylesheet" href="common/style.css">
<link rel="stylesheet" href="includes/GUIStructures/dropDownList.css">

<!--<link rel="stylesheet" href="config/colorPalette.css">-->
<script src="includes/GUIStructures/dropDownList.js"></script>
<?php include '../config/config.php'; ?>
<?php 

	/**
	* DropDownList
	*/
	class dropDownItem
	{
		
		var $icon; //Icon name .png
		var $deviceName;
		var $deviceDescription;
		var $sensors;
		var $alarms;
		var $stage;
		var $actuators;
		var $ide;
		var $drop;
		//IDs
		var $IDGeneral;
		var $IDSensorArray;
		var $IDActuatorArray;

		function __construct($drop,$ide,$icon, $deviceName, $deviceDescription,$actuators, $sensors, $alarms, $stage, $IDSensorArray, $IDActuatorArray, $IDGeneral )
		{	
			$this->drop = $drop;
			$this->ide = $ide;
			$this->icon = $icon;
			$this->deviceName = $deviceName;
			$this->deviceDescription = $deviceDescription;
			$this->stage = $stage;
			$this->actuators = $actuators;
			$this->sensors = $sensors;
			$this->alarms = $alarms;
			$this->drop = $drop;
			$this->IDSensorArray = $IDSensorArray;
			$this->IDActuatorArray = $IDActuatorArray;
			$this->IDGeneral = $IDGeneral;
			$this->make();
		}

		//PUBLIC
		function make(){

echo '<div class="droplist" align="center" id="'.$this->IDGeneral['Device'].'">
	<ul class="Ddlist">
		<div class="deleter" id="'.$this->IDGeneral['Device'].'">&#x2715;</div>
		<li id="'.$this->ide.'">
			<img src="resources/icon-black/'.$this->icon.'">
			<div class="credentials">
				<h3>'.$this->deviceName.'</h3>
				<p style="font-weight: bold;">'.$this->stage.'</p>
				<p>'.$this->deviceDescription.'</p>
			</div>';

			//Gen message
			if($this->drop == "Inventory"){
			echo '<div class= "genMessage" id="Inve'.$this->ide.'"><p class="genMp" style="color:white;">GenMessage</p><img src="resources/icons/messageIcon.png" style="height:30px; width:30px;"></div>';

			
				echo '<img class="dropItem" src="resources/icon-black/drop.png">';
			//End for inventory
			}elseif ($this->drop == 'Monitoring') {
				echo'<div class= "genMessage" id="Moni'.$this->ide.'"><p class="genMp" style="color:white;">Start Monitor</p><img src="resources/icons/Monitoringw.png" style="height:25px; width:25px;"></div>';
			}//End for Monitoring

			
		echo '</li>
		<br>
		<div class="options">
				<h3>  Actuators </h3>
				<div class="elements">';
					
				foreach ($this->actuators as $value) {
						echo('<div class="elemento">'.$value.'</div>');
					} 
					
					
			echo '</div><h3>  Sensors</h3>
				<div class="elements">';
					
			foreach ($this->sensors as $value) {
						echo('<div class="elemento">'.$value.'</div>');
					} 
			echo '</div>
				
				<h3 >  Alarms</h3>
				<div class="elements">';

			foreach ($this->alarms as $value) {
				echo('<div class="elemento">'.$value.'</div>');
			} 
			echo'</div></div></ul></div>';

		}

		
	}//End class


?>

<?php 
/**
 	* SHOWS THE WINDOW WITH THE JSON INFO
 	*/
 	class JSONDisplayer
 	{
 		//IDs
 		var $ide;
		var $IDGeneral;
		var $IDSensorArray;
		var $IDActuatorArray;

 		function __construct($IDGeneral,$IDSensorArray,$IDActuatorArray)
 		{	
 			$this->ide = 0;
 			$this->IDGeneral = $IDGeneral;
 			$this->IDSensorArray = $IDSensorArray;
 			$this->IDActuatorArray = $IDActuatorArray;
 		}

 		//PUBLIC Displays or shows the JSON Window
		function showJSON(){
			$json = '<div class="messageGenerated" align="center" id="JSONWindow'.$this->ide.'" >
	<div class="mssg">
	<p class="cross" id ="jsonPop'.$this->ide.'" style="font-size: 20px; padding: 15px; text-align: left; cursor: pointer;">&#x2715; JSON ID</p>
	<div class="JStext" style="text-align: left; width: 100%; height:70%; overflow-y: scroll; ">
		<br>
		<p style="padding-left: 30px; font-family: monospace;">';
		$json.= $this->generateJSON();
		$json .='</p></div></div></div>';

		echo $json;
		}//End JSON ID


		//PRIVATE Generates a JSON with the stage->device->sensors->values credentials
		function generateJSON(){
			$Count = 0; //Se emplea para no colocar una ","" al final del último elemento del JSON
			$sampVar = '<span style="color:var(--defaultColor); display:inline-block; font-size:12px; font-family:monospace; font-weight:bold;">[SAMPLE VARIABLE]</span>';
			$tab2 = '<span style="display:inline-block; width: 60px;"></span>';
			$tab1 = '<span style="display:inline-block; width: 30px;"></span>';
			$js= '{<br>
			'.$tab1.'"SKey":'.DEVICE_KEY.',<br>
  			'.$tab1.'"Stage":'.$this->IDGeneral['Stage'].',<br>
  			'.$tab1.'"Device": '.$this->IDGeneral['Device'].',<br>
  			'.$tab1.'"Sensor":{<br>';
  			//Sensors
  			foreach ($this->IDSensorArray as $value) {
  				if($Count == 0){
  					$js.= $tab2.'"'.$value.'":'.$sampVar;
  					$Count +=1;
  				}
  				else
  					$js.= ',<br>'.$tab2.'"'.$value.'":'.$sampVar;
  			}
  			$Count = 0;
  			$js.='<br>'.$tab1.'},<br>'.$tab1.'
  			"Actuator":{<br>';
  			//Actuators
  				foreach ($this->IDActuatorArray  as $value) {
  					if($Count == 0){
  					$js.= $tab2.'"'.$value.'":'.$sampVar;
  					$Count +=1;
  				}
  				else
  					$js.= ',<br>'.$tab2.'"'.$value.'":'.$sampVar;
  				}
  			//
  			$js.=$tab1.'<br>'.$tab1.'}<br>
			}<br>';
			return $js;

		}//End generateJSON
 	}//End Class JSONDisplayer

?>




<!-- DROP LIST HTML
	<div class="droplist" align="center">
	<ul class="Ddlist">
		<li id="identi">
			<img src="resources/icon-black/termometro.png">
			<div class="credentials">
				<h3>Nombre del item</h3>
				<p style="font-weight: bold;">MiStage</p>
				<p>Descripción lorem ...</p>
			</div>
			
			<div class= "genMessage" id="1"><img src="resources/icons/messageIcon.png"><p>GenMessage</p></div>

			<img class="dropItem" src="resources/icon-black/drop.png">

			
		</li>
		<br>

	
		<div class="options">
				<h3>  Actuators</h3>
				<div class="elements">
					<div class="elemento">Sensor</div>
					<div class="elemento">Sensor</div>
					<div class="elemento">Sensor</div>
					<div class="elemento">Sensor</div>
				</div>

				<h3>  Sensors</h3>
				<div class="elements">
					<div class="elemento">Sensor</div>
				</div>
				
				<h3 >  Alarms</h3>
				<div class="elements">
					<div class="elemento">Sensor</div>
				</div>
			</div>
	</ul>
	</div>


<div class="messageGenerated" align="center">
	<div class="mssg">
	<p class="cross" id ="" style="font-size: 20px; padding: 15px; text-align: left; cursor: pointer;">&#x2715; JSON ID</p>
	<div class="JStext" style="text-align: left; width: 100%; height:70%; overflow-y: scroll; ">
		<br>
		<p style="padding-left: 30px; font-family: monospace;">
			
		</p>
	</div>	
	</div>
</div>-->

