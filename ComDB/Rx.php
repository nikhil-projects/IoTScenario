
<?php 
/*
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
Example:
*/
	include '../config/config.php';
	include 'DBManager.php';
?>
<?php 

	/**
	* 
	*/
	class Rx
	{
		
		function __construct()
		{
			$this->Loader();
		}//construct



		function checkPassword($key){return ($key == DEVICE_KEY);}

		//Esta función se encarga de la recepción de la información, decidir si es correcta y proceder a su
		//almacenamiento
		function RecData(){
			//Leemos el cuerpo de de la petición.
			$request_body = file_get_contents('php://input');
			if($request_body!=""){
				
				$msg = new Request((String)$request_body);
				if($msg->sKey != ""){
					if($this->checkPassword($msg->sKey)){
						$this->storeSampleValue($msg);
					}//CheckPassword
				}//msg != ""
			}//request != ""
		}

		//Almacena la muestra que ha llegado en la base de datos.
		function storeSampleValue($obj){
			$db = new SDBManager();
			foreach ($obj->Sensors as $key => $value) {
				//Almacenamos las muestras del sensor en la base de datos
				$db->Insert('Sample',['Sensor_idSensor','value'],[$key,$value]);
			}
		}//End store Sample value

		//Retorna la id del sensor y el valor de la última muestra 
		//almacenada en este
		function sendValueToDevice($ids){
			$db = new SDBManager();
			$ret = array();
			$count = 0;
			foreach ($ids as $value) {
				$ret[$count]=array("id"=>$value ,"value" => $db->getLastSample($value));
				$count+=1;
			}	
			return json_encode($ret);
		}//End sendValueToDevice

		//PARA COMUNICACIONES AJAX=========================
		//Se encarga de discernir si la petición que llega es
		//de un cliente dispositivo o un cliente usuario que
		// quiere realizar una lectura de información 
		//empleando Ajax.
		function Loader(){
			if (isset($_POST['message']) && !empty($_POST['message'])) {
	    		$msg = $_POST['message'];
	    		$objectID = $msg['data']; 
	    		$funcName = $msg['function']; 
	    		$this->Switcher($objectID, $funcName);   	
			}else{
				//Peticiones de dispositivos.
				$this->RecData();
			}
		}//End LOADER

		//Interpreta la peticiones que llegan mediante AJAX
		function Switcher($ids, $func){	
			switch ($func) {
				case 'GetLastSample':
					echo $this->sendValueToDevice($ids);
				break;
			
			default:
				echo "LOAD FAILED";
				break;
		}//End Switch
		}//End Switcher
		//===================================================

		//For debug ==================================
		function StoreInTxt($request_body){
			$baseDatos = fopen("baseDatos.txt", "a") or die("No se ha podido abrir el documento :(");
			fwrite($baseDatos, "->".$request_body."\n");
			fclose($baseDatos);
		}
		//============================================


	}//End Rx


	/**
	* Stores the request credentials.
	*Almacena las credenciales de la petición para un mejor manejo
	*/
	class Request
	{
		var $sKey;
		var $Stage;
		var $Device;
		var $Sensors;
		var $Actuators;
		var $Alarms;
		function __construct($pe)
		{	
			//Decodificamos el json

			$req = json_decode($pe, true);
			$this->sKey = "";
			$this->LoadData($req);
		}

		function LoadData($req){
			 $this->sKey = $req["SKey"];
			 $this->Stage = $req["Stage"];
			 $this->Device = $req["Device"];
			 $this->Sensors = $req["Sensor"];
			 $this->Actuators = $req["Actuators"];
			 $this->Alarms = $req["Alarms"];
		}

		//Comprueba la sintaxis del mensaje
		function checkMsg($req){
			$tmp = array_keys($req);
			
			foreach ($tmp as $value) {
				if($value == "SKey")
					return true;
			}
			return false;
		}
			//For debug
		function StoreInTxt($request_body){
			$baseDatos = fopen("baseDatos.txt", "a") or die("No se ha podido abrir el documento :(");
			fwrite($baseDatos, "->".$request_body."\n");
			fclose($baseDatos);
		}
	}//End class


 ?>

<?php 
	new Rx();
 ?>
