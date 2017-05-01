<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
-->
<?php 
	if (session_status() != PHP_SESSION_ACTIVE) {
		session_start();
	} 
	include '../includes/GUIStructures/navbar.php'; //Navbar main file library
	include '../includes/GUIStructures/dropDownList.php'; //Drop down main file library
	include '../ComDB/DBManager.php'; //Manage the DB connections
?>
<script src="pages/Inventory.js"></script>
<?php 
	/**
	* Inventory
	*/
	class Inventory
	{
		var $DeviceList; //Stores de devices showed in the screen

		function __construct()
		{	 
			$this->Loader();	
		}
		
		function mainPage(){
			//We add a navbar
			new navbarItem('Inventory',['New Device', 'Delete Device']);
			//We load the New Device form page
 			include '../includes/forms/forms.php';

 			//We add the devices storeds in the database
			//$dbm = new SDBManager()	

		}//EndMainPage

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
				$DeviceList[$count] = new dropDownItem('Inventory',$count,$device->DeviceIcon, $device->DeviceType, $device->StageName,$device->dispActuatorList(), $device->dispSensorList(), $device->alarms, $device->StageName,$device->sensorsID,$device->actuatorsID, ['Stage' => $device->IdStage,'Device' => $device->DeviceId]);
				$count+=1;
			}

 			//We save the array as session variable in the server for possible future acces
			 $_SESSION['DeviceList'] = json_encode($DeviceList); 

			 //End if empty
			}else{
				echo "<br><br><h1 align='center' style='color:var(--defaultColor);  font-weight: 300; '>No devices available in the database. <br><br> Create some device :)</h1>";
			}
		}


 		//------------------------- USO LÓGICO O DE COMUNICACIÓN ----
 		//Se encarga de ir cargando las partes de la página en función de las peticiones del cliente
 		//If(jqueryCall)do: ... else do ...
 		//INICIO DE LA PÁGINA
		function Loader(){
			if( !isset( $_SESSION ) ) {
    			session_start();
			}
			//SEGURIDAD Revisar ¿Podría un usuario foraneo acceder a la información?. 
			if (isset($_POST['message']) && !empty($_POST['message'])) {
	    		$msg = $_POST['message'];
	    		$msg = json_decode($msg,true);

	    		$objectID = $msg['object']; 
	    		$funcName = $msg['function']; 
	    		$this->Switcher($objectID, $funcName);   	
			}else{
				$this->mainPage();
				$this->LoadDevices();
			}

		}//End LOADER

		//Carga asincrona de elementos de pantalla 
		function Switcher($id, $func){
			//We load the device array from session variable	
			$data = json_decode($_SESSION['DeviceList']);
			switch ($func) {
				case 'showJSON':
					$IDGeneral = (array)$data[strval($id)]->IDGeneral;
					$IDSensorArray = (array)$data[strval($id)]->IDSensorArray;
					$IDActuatorArray = (array)$data[strval($id)]->IDActuatorArray;

					$js = new JSONDisplayer($IDGeneral,$IDSensorArray,$IDActuatorArray);
					$js->showJSON();
				break;
			
			default:
				echo "LOAD FAILED";
				break;
		}//End Switch
		}//End Switcher

		

	
	}//ClassEnd
 ?>


 <?php 
 	new Inventory();
  ?>
	



