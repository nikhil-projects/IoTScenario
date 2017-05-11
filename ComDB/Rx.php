
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
		
		var $ip;
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
						//Registramos la IP del cliente
						$ip = $this->getClientIP(); //<-----IP
						$this->storeSampleValue($msg,$ip);
						echo "ok";
					}//CheckPassword
				}//msg != ""
			}//request != ""
		}//End RecData		

		//Almacena la muestra que ha llegado en la base de datos.
		function storeSampleValue($obj,$ip){
			$db = new SDBManager();

			//Para cada sensor del dispositivo almacenamos.
			foreach ($obj->Sensors as $key => $value) {
				//Almacenamos las muestras del sensor en la base de datos
				$db->Insert('Sample',['Sensor_idSensor','value'],[$key,$value]);
				$ide = $key;
				//Almacenamos la IP en la dB
				$this->SaveIP($ip,$ide);
			}//Fin del foreach	
		}//End store Sample value

		//Registramos la IP del cliente con fines de geolocalización
		function getClientIP(){
			$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = $_SERVER['REMOTE_ADDR'];

			if(filter_var($client, FILTER_VALIDATE_IP))
			 $ip = $client;
			 elseif(filter_var($forward, FILTER_VALIDATE_IP))
			 $ip = $forward;
			 else
			 $ip = $remote;
			 return $ip;
		}//End getClientIP

		//Almacena el valor IP de un dispositivo en la base de datos a partir del
		// id del sensor que transmite la muestra
		function SaveIP($ip,$key){
			$db = new SDBManager();
			//Obtención de la id del dispositivo a partir del valor
			// de la muestra.
			$sql='SELECT d.idDevices as sid
			FROM Sensor s 
			LEFT JOIN Devices d ON s.Devices_idDevices = d.idDevices
			WHERE s.idSensor ='.$key; 
			$row = $db->Read($sql);
			
			//$this->store($row["sid"]);
			//Almacenamos el valor de la IP:
			
			$sql="UPDATE Devices d
				  SET IP = '".$ip."'
				  WHERE d.idDevices =".$row["sid"];
			$db->rawSql($sql);
		}//End save IP

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

		function store($txt){
			$txt = "Entrada registrada->     ".$txt."\n";
			file_put_contents('debug.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
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
