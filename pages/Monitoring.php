<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
Example:

include '../includes/GUIStructures/buttons.php';
	new displayItem('uno', 'Senso1', '0.0', 'cm', 'termometro','var(--deepRed)' ,'var(--red)');

new displayItem('uno', 'Sensor2', '0.0', 'cm', 'agua','var(--deepPurple)' ,'var(--purple)');

new displayItem('uno', 'Sensor3', '0.0', 'cm', 'metro','var(--deepBlue)' ,'var(--blue)');
new displayItem('uno', 'Sensor3', '0.0', 'cm', 'metro','var(--deepBlue)' ,'var(--blue)');
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<?php 
	if (session_status() != PHP_SESSION_ACTIVE) {
		session_start();
	} 
	include '../includes/GUIStructures/navbar.php'; //Navbar main file library
	include '../includes/GUIStructures/dropDownList.php'; //Drop down main file library
	include '../ComDB/DBManager.php'; //Manage the DB connection
	include '../includes/GUIStructures/buttons.php'; //Display de sensor value
?>



<?php 
	/**
	* 
	*/
	class Monitoring
	{
		
		function __construct()
		{
			$this->Loader();
		}

		function LoadDevices(){
			//Stores the devices where the devices are arranged in the
			//class dropDownItem
			$dbm = new SDBManager(); //Creamos un gestor de bases de datos.
			$MyInventory = array(); //Alamcenaremos aqui los dispositivos retornados de la base de datos.

			$MyInventory = $dbm->ReadInventory();//Leemos los dispositivos almacenados en la base de datos y los cargamos en myInventory.

			if(!empty($MyInventory)){
			//Hacemos un plot de cada dipositivo almacenado en la base de datos maquetándo la información segun dropDownItem.
			$count =0;
			$DeviceList = array();

			foreach ($MyInventory as $device) {
				$DeviceList[$count] = new dropDownItem('Monitoring',$count,$device->DeviceIcon, $device->DeviceType, '',$device->dispActuatorList(), $device->dispSensorList(), $device->alarms, $device->StageName,$device->sensorsID,$device->actuatorsID, ['Stage' => $device->IdStage,'Device' => $device->DeviceId]);
				$count+=1;
			}

 			//We save the array as session variable in the server for possible future acces
			 $_SESSION['DeviceList'] = json_encode($DeviceList); 

			 //End if empty
			}else{
				echo "<br><br><h1 align='center' style='color:var(--defaultColor);  font-weight: 300; '>No devices available in the database. <br><br> Create some device :)</h1>";
			}
		}//End load devices

		//------------------------- USO LÓGICO O DE COMUNICACIÓN ----
 		//Se encarga de ir cargando las partes de la página en función de las peticiones del cliente
 		//If(jqueryCall)do: ... else do ...
 		//INICIO DE LA PÁGINA
		
		function Loader(){
			//SEGURIDAD Revisar ¿Podría un usuario foraneo acceder a la información?. 
			if (isset($_POST['message']) && !empty($_POST['message'])) {
	    		$msg = $_POST['message'];
	    		$msg = json_decode($msg,true);

	    		$objectID = $msg['object']; 
	    		$funcName = $msg['function']; 
	    		$this->Switcher($objectID, $funcName);   	
			}else{
				$this->LoadDevices();
			}

		}//End LOADER

		//Carga asincrona de elementos de pantalla 
		function Switcher($id, $func){
			//We load the device array from session variable	
			$data = json_decode($_SESSION['DeviceList']);
			switch ($func) {
				case 'MonitoringDevice':
					$IDGeneral = (array)$data[strval($id)]->IDGeneral;
					$IDSensorArray = (array)$data[strval($id)]->IDSensorArray;
					$IDActuatorArray = (array)$data[strval($id)]->IDActuatorArray;

					//We generate a new page for monitoring the device
					$this->GenerateMonitoringPage($IDGeneral,$IDSensorArray,$IDActuatorArray);
				break;
			
			default:
				echo "LOAD FAILED";
				break;
		}//End Switch
		}//End Switcher


		//================================
		//Genera la página de los sensores 
		//================================

		function GenerateMonitoringPage($IDGeneral,$IDSensorArray,$IDActuatorArray){
			//HTML
			echo '
			<!--We load the page style-->
			<link rel="stylesheet" href="includes/MonitoringPage.css">
			<script src="includes/parallax.js"></script>
			<script src="pages/Monitoring.js"></script>
			<div class="monDevice">';
			
			new Device($IDGeneral);

			echo'<!--Cargamos los sensores-->
			<br><br><br>
			<h2>Device Sensors</h2>
			<br><br>
			';
			foreach ($IDSensorArray as $id) {
				new Sensor($id);
			}//End foreach
			echo'</div>';

			//Generación de los graficos:
			include '../Charts/chartObj.php';
			echo "<br><br><h3>Charts</h3>";
			$numMuestras = 30;
			new chartObj($IDSensorArray, $numMuestras);
  

		}
	}//End class

?>

<?php 
	/**
	* Stores sensor information and displays the information in HTML if it is needed
	*/
	class Sensor
	{
		var $idSensor;
		var $Name;
		var $Color;
		var $Description;
		var $Icon;
		var $ID_Device;

		function __construct($id)
		{
			$this->idSensor = $id;
			$dbm = new SDBManager();
			//Cargamos las credenciales del sensor de la base de datos
			$credentials = $dbm->readSensor($this->idSensor);
			$this->Name = $credentials["Name"];
			$this->Color = $credentials["Color"];
			$this->Description = $credentials["Description"];
			$this->Icon = $credentials["Icon"];
			$this->ID_Device = $credentials["Devices_idDevices"];

			//Mostramos la informacióm por pantalla con la clase displayItem
			$this->make();
		}

		function make(){
			$darkColor = 'var(--deep'.$this->Color.')';
			$lightColor = 'var(--'.$this->Color.')';
			new displayItem($this->idSensor, $this->Name, '0.0', '', $this->Icon, $darkColor ,$lightColor);
		}
	}
 ?>

 <?php 
 		/**
 		* Stores the device information and displays the information in HTML if it is needed
 		*/
 		class Device
 		{
 			var $idDevices;
 			var $idStage;
 			var $icon;
 			var $name;
 			var $color;	
 			function __construct($id)
 			{
 				$this->idStage = $id["Stage"];
 				$this->idDevices = $id["Device"];

 				$dbm = new SDBManager();
				//Cargamos las credenciales del sensor de la base de datos
				$credentials = $dbm->readDevice($this->idDevices);
 				$this->name = $credentials["Name"];
 				$this->icon = $credentials["Icon"];
 				$this->color = $credentials["Color"];
 				$this->make();
 			}

 			function make(){
	 			echo '<!--Cargamos las credenciales-->
				<div class="monitorBackTitle" style="background: var(--'.$this->color.'); background-image: url(resources/backgroundImg/lowpoly.png);">
				<div class="info">
				 <img  style="background: var(--deep'.$this->color.');height:105px;
	width: 105px;" src="resources/icons/'.$this->icon.'"> 
					<div class="credentials">
						<ul>
							<li><h1>'.$this->name.'</h1></li>
							<li></li>
							<li><h3>PlayGround</h3></li>
						</ul>
					</div>
				</div>
				</div>';
 			}
 		}//End device Class

 ?>


<?php new Monitoring(); ?>