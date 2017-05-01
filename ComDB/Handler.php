<?php 
/*
—————————————— 
Autor: Alvaro Peris Zaragoza
 Ano: 2017
Descripción: 
———————————————


*/
/**
* 
*/

include 'DBManager.php';
include '../config/config.php'; //Incluimos las constantes

class DataHandler
{
	var $data;
	var $function;	
	function __construct(){
		$this->data = "";
		$this->function = "";
		$this->comWithJQuery();
	}

	//Mira que exista un mensaje. En caso de que exista procede a comprobar de que tipo es el mensaje
	function comWithJQuery(){
		if (isset($_POST['message']) && !empty($_POST['message'])) {
	    	$msg = $_POST['message'];
	    	$this->data = $msg['data']; 
	    	$this->function = $msg['function']; 	
	    	$this->Switcher();   
			}else{
				//Si la petición no es de jQuery ...
				echo "";
			}
	}

	//El switch se encarga de seleccionar un procedimiento dependiendo de el identificador que llegue. Esto en PHP
	//Podria resolverse de otra manera pero se opta por el switch
	//para ser mas globales.
	function Switcher(){
			//echo $this->function;
			switch ($this->function) {
				//Carga los dispositivos que vienen del formulario de
				//NewDevice
				case 'LoadDeviceForm':
					$this->readNewDeviceForm();
				break;

				//Viene de dropDownList.js
				case 'DeleteDevice':
				//Deletes a device from database
				//Elimina un dispositivo de la base de datos 
				   echo "Deleting element: ->  ". $this->data;
					$db = new SDBManager();
					$db->DeleteDevice($this->data);
				break;

			default:
				
				break;
		}//End Switch
		}//End Switcher

	function readNewDeviceForm(){
		$d = $this->data;
		//Creamos un gestor de bases de datos
		$db = new SDBManager();

		//Insertamos un nuevo tipo de dispositivo en la tabla de nuevos dispositivos y obtenemos el id de este
		$id = $db->Insert('DeviceType',['Color', 'Developer', 'Icon', 'Name'],['"'.$d['Color'].'"','"'.$d['DevName'].'"','"'.$d['DeviceIcon'].'"','"'.$d['DeviceName'].'"']);

		
		//Creamos un nuevo dispositivo del tipo anterior 
		$id = $db->Insert('Devices',['Stage_idStage','DeviceType_idDeviceType'], [1,$id]);

		//Insertamos las señales de los sensores de este dispositivo
		foreach ($d['Sensors'] as $v) {
			$nam = $v['SName'];
			$su = $v['SUnits'];
			$co = $v['Color'];
			$ic = $v['Icon'];

			$id = $db->Insert('Sensor',['Name','Color','Icon','Devices_idDevices'], ['"'.$nam.'"','"'.$co.'"','"'.$ic.'"',$id]);
			//Inicializamos una entrada para alamcenar las muestras  de los sensores.
			$db->Insert('Sample',['Sensor_idSensor','value'],[$id,0.0]);
		}

		echo "<script>alert('Device Created !');</script>";
	}//End readDeviceForm

	

}//Fin de clase

 ?>

 <?php 
 	new DataHandler();
  ?>
