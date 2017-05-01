<?php 
/*
—————————————— 
Autor: Alvaro Peris Zaragoza
 Ano: 2017
Descripción: Establece las conexiones y peticiones con la base de datos, inserción deelementos, lectura de elementos ...
———————————————
TEST
_________________________

$dbm = new SDBManager();
if ($dbm->checkUser('root','root'))
	print "Entra"; 


$dbm = new SDBManager();
$dbm->Insert('DeviceType',['Color', 'Developer', 'Icon', 'Name'],['"color"', '"Desarrollador"', '"MiIcono"', '"DispositivoX"']);

*/

//include '../config/config.php'; //Incluimos las constantes


class SDBManager
{

	var $servername;
	var $username;
	var $password;
	var $DB;
	var $dk;
	//Datos de la conexión
	var $connection;

	function __construct()
	{
		$this->servername = SERVER_NAME;
		$this->username = ADMIN_NAME;
		$this->password = ADMIN_PASSWORD;
		$this->DB = DATABASE_NAME;
		$this->dk = DEVICE_KEY;
	}
	
	//Establece la conexión con la base de datos
	function connect(){
		//Conectamos con la dB
		$conn = mysqli_connect($this->servername, $this->username, $this->password, $this->DB);
		// Comprobamos que la conexión se haya realizado correctamente.
		if (!$conn) {
			if(DEBUG_MODE == "True")
	    		die("<h4 style='background:black;color:red;'>Connection to dB failed: :(</h4>" . mysqli_connect_error());
		}else{
			if(DEBUG_MODE == "True"){
				echo("Connection to dB OK :)");}
			$this->connection = $conn;}}

	//Comprueba si existe un usuario, en caso de que exista crea una 
	//sesión para este
	function checkUser($user, $passwd){
		$this->connect();
		$table = USER_TABLE;
		$sql = "SELECT * FROM $table WHERE username = '$user' AND password = '$passwd'";
		$result = mysqli_query($this->connection, $sql);

		$row = $result->fetch_array(MYSQLI_ASSOC);
		if ($passwd == $row['password']) {
			//Iniciamos sesión
			session_start();
			$_SESSION["user_id"]=$user;
			$_SESSION['loggedin'] = true;
			$_SESSION['start'] = time();
			$_SESSION['expire'] = $_SESSION['start'] + (5 * 60); 

			return True;
		}else{
			//Printamos una alerta diciendo que los campos entrados son incorrectos.
			echo "<script>alert('User or Password fields are incorrect');</script>";
			return False;
			}
		mysqli_close($this->connection);
		}


	//FUNCTION FOR DEBUG COMUNICATIONS WITH DATABASE
	function plotQuery($sql){
		$this->connect();
		$cont = 0;
		$result = mysqli_query($this->connection, $sql);
		$resul = $result;
		echo "<br><br><br>";

		//Extract the columns
		
		$column = '<table><tr>';
		$vars = '';
		$raw = $resul -> fetch_array(MYSQLI_ASSOC);
		foreach ($raw as $key => $value) {
			$column .= '<th>'.$key.'</th>';
			$vars .= $value;
		}
		$column .= '</tr>';

		//Extract the values
		while($row = $result -> fetch_array(MYSQLI_ASSOC)){
			foreach ($row as $key => $value) {
				$column .= '<td>'.$value.'</td>';
			}
			$column .= '</tr>';
		}
		$column.='</table>';
		echo $column;
	}//End func

	//INSERTs a new element in the dB
	function Insert($table,$fields, $values){
		
		include '../config/config.php';
		$in = 'INSERT INTO `'.$table.'`(';
		$fc = true; //Field counter
		//Insert the values:
		foreach ($fields as $value) {
			if($fc){
				$in.='`'.$value.'`';
				$fc = false;
			}else{
				$in.=',`'.$value.'`';
			}
		}//Foreach
			$in.= ') VALUES (';
			$fc = true;
		foreach ($values as $value) {
			
			if($fc){
				$in.=$value;
				$fc = false;
			}else{
				$in.=','.$value;
			}
		}//foreach
		$in.=')';

		if(DEBUG_MODE == "True"){
			echo 'Entrada a insertar: '.$in.'\n<br>';
		}

		$this->connect();			
		mysqli_query($this->connection, $in);
		//Retornamos la ultima id insertada
		$id = $this->connection->insert_id;
		mysqli_close($this->connection);	
		return $id;
	}//End insert


		//Lee y retorna valores de la base de datos. Para
		//peticiones simples.
	//Returns the inventory values
		function ReadInventory(){
			//1.-Tomamos el identificador de cada dispositivo	
			$sql = 'SELECT idDevices FROM Devices';
		
		 	$this->connect();			
			$result = mysqli_query($this->connection, $sql);
			
			//-------------OBTENCIÓN DEL TIPO DE DISPOSITIVO
			//Extraemos los identificadores de los dispositivos 
			// que tenemos
			$c = 0;
			$ids = array();
			while($row = $result -> fetch_array(MYSQLI_ASSOC)){
					$ids[$c] = $row["idDevices"];
				    $c++;
			}
			$result->free();
			
			//Comprobamos que exista algún dispositivo almacenado
			//en la basa de datos.

			if(!empty($ids)){
			//-------------OBTENCIÓN DEL STAGE y DEVICE TYPE
			$MyInventory = array();
			$c = 0;
			foreach ($ids as $val) {
				//Obtenemos el tipo de dispositivo y el escenario.
			$sql = 'SELECT d.idDevices as DevideId, dt.Name as DeviceType, dt.Icon, st.idStage as IdStage, st.Name as StageName
					FROM DeviceType dt, Devices d, Stage st
					WHERE d.idDevices = '.$val.' and dt.idDeviceType = d.idDevices and st.idStage = d.Stage_idStage';

				$this->connect();			
				$result = mysqli_query($this->connection, $sql);
				$row = $result -> fetch_array(MYSQLI_ASSOC);
					$MyInventory[$c] = new InventoryStruct($row["DevideId"],$row["DeviceType"],$row["IdStage"],$row["StageName"], $row["Icon"] );
				 	
				 $c+=1;
			
			}// ----------------- Fin obtencion stage y devicetype
			$result->free();
			//-------------OBTENCIÓN DE LOS SENSORES DE CADA DISPOSITIVO
			//Obtención de los sensores de cada dispositivo
			
			$c=0;
			foreach ($ids as $val) {
				$sql = 'SELECT s.idSensor as sensorid, s.Color, s.Icon ,s.Name as sensorName FROM Devices d, Sensor s WHERE d.idDevices = s.Devices_idDevices and d.idDevices ='.$val;

					$this->connect();			
					$result = mysqli_query($this->connection, $sql);

					while($row = $result -> fetch_array(MYSQLI_ASSOC)){
							$MyInventory[$c]->addSensor($row['sensorid'],[$row['sensorName'],$row['Color'],$row['Icon']]);
							
					}//Fin while
					$c+=1;
			}

			//Obtención de los actuadores de cada dispositivo
			$c=0;
			foreach ($ids as $val) {
				$sql = 'SELECT a.idSensor as Actuatorid, a.Name as ActuatorName 
						FROM Devices d, Actuator a 
						WHERE d.idDevices = a.Devices_idDevices and d.idDevices = '.$val;

					$this->connect();			
					$result = mysqli_query($this->connection, $sql);

					while($row = $result -> fetch_array(MYSQLI_ASSOC)){
						$MyInventory[$c]->addActuator($row['Actuatorid'],$row['ActuatorName']);
						
					}//Fin while
					//$MyInventory[$c]->display();
					$c+=1;

			}
			//----------------------------------------------

			//Obtención de las alarmas de cada dispositivo
		
		
			//----------------------------------------------
			mysqli_close($this->connection);	
			return $MyInventory;
		//En if no empty
		}else{
			mysqli_close($this->connection);
		}
			
		}//End Inventory query
	
		function DeleteDevice($id){
			//Borraremos las dependencias del dispositivo, sensores, actuadores y alarmas asociados a este.
			//Posteriormente borraremos el dispositivo.
			//Borrar muestras
			$this->connect();			
			
			$sql = "DELETE sa.*
					FROM Sample sa
					LEFT JOIN Sensor s ON sa.Sensor_idSensor = s.idSensor
					LEFT JOIN Devices d ON s.Devices_idDevices = d.idDevices
					WHERE d.idDevices = ".$id.";";
			$result = mysqli_query($this->connection, $sql);
			//Borrar sensores
			$sql="DELETE s.*
					FROM Sensor s 
					WHERE s.Devices_idDevices = ".$id.";";
			$result = mysqli_query($this->connection, $sql);
			//Borrar alarmas
			$sql="DELETE al.*
					FROM Alarm al
					WHERE al.Devices_idDevices = ".$id.";";
			$result = mysqli_query($this->connection, $sql);
			//Borrar actuadores
			$sql="DELETE a.*
					FROM Actuator a 
					WHERE a.Devices_idDevices =".$id.";";
			$result = mysqli_query($this->connection, $sql);
			//Borrar dispositivo
			$sql="DELETE d.*
					FROM Devices d 
					WHERE d.idDevices =".$id.";";
			$result = mysqli_query($this->connection, $sql);
			
			mysqli_close($this->connection);
		}//End delete device


	//Returns a sensor credentials identified by a idDevice
	function readSensor($id){
		$sql = 'SELECT * 
				FROM Sensor s 
				WHERE s.idSensor ='.$id;
		$this->connect();			
		$result = mysqli_query($this->connection, $sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		mysqli_close($this->connection);
		return $row;
	}//End Read Sensor

	//Reads device credentials
	function readDevice($id){
		$sql = 'SELECT d.DeviceType_idDeviceType as dtID
				FROM Devices d 
				WHERE d.idDevices ='.$id;
		$this->connect();			
		$result = mysqli_query($this->connection, $sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		$id = $row["dtID"];

		$sql = 'SELECT *
				FROM DeviceType dt
				WHERE dt.idDeviceType ='.$id;

		$result = mysqli_query($this->connection, $sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);

		mysqli_close($this->connection);
		return $row;
	}//End Read Sensor


	function getLastSample($id){
		$sql = 'SELECT s.value
				FROM Sample s
				WHERE s.Sensor_idSensor = '.$id.'
				ORDER BY s.idSample DESC
				LIMIT 0,1';
		$this->connect();
		$result = mysqli_query($this->connection, $sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		$value = $row["value"];
		mysqli_close($this->connection);
		return $value;
	}

	function GetReportData($id){		
		$sql = 'SELECT COUNT(s.value) as Total, AVG(s.value) as Media, MAX(s.timestamp) as `End`, MIN(s.timestamp) as `Start`, MAX(s.value) as `Max`,MIN(s.value) as `Min`
			FROM Sample s 
			WHERE s.Sensor_idSensor ='.$id;
		
		$this->connect();
		$result = mysqli_query($this->connection, $sql);
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		mysqli_close($this->connection);
		return $row;
	}//End GetReportData

	//Retorna el valor de todoas las muestras de un determinado sensor
	function getAllSensorSamples($id){
		$sql= 'SELECT s.timestamp as `time`, s.value as `value`
			   FROM Sample s
				WHERE s.Sensor_idSensor ='.$id;

		$this->connect();
		$result = mysqli_query($this->connection, $sql);
		$count = 0;
		$ret = array();
		while($row = $result -> fetch_array(MYSQLI_ASSOC)){
			$ret[$count] = [ "time" => $row["time"], "value" => $row["value"] ];
			$count+=1;
		}	
		mysqli_close($this->connection);
		return $ret;
	}


	function deleteAllSamples($id){
		$sql='DELETE s.*
			  FROM Sample s 
			   WHERE s.Sensor_idSensor='.$id;
		$this->connect();
		$result = mysqli_query($this->connection, $sql);
	}
}//Final clase


/**
* Almacena la información de los dispositivos
*/
class InventoryStruct
{
	var $DeviceId;
	var $DeviceType;
	var $IdStage;
	var $StageName;
	var $DeviceIcon;

	var $sensors;
	var $alarms;
	var $actuators;

	var $sensorsID;
	var $actuatorsID;
	var $alarmsID;

	var $sensorIDCount;
	var $actuatorIDCount;
	var $alarmIDCount;

	function __construct($DevId, $DevType,$IdStage, $Stage, $Icon)
	{
		$this->DeviceId = $DevId;
		$this->DeviceType = $DevType;
		$this->IdStage = $IdStage;
		$this->StageName = $Stage;
		$this->DeviceIcon = $Icon;

		$this->sensors = array();
		$this->alarms = array();
		$this->actuators = array();

		$this->sensorsID = array();
		$this->alarmsID = array();
		$this->actuatorsID = array();

		$this->sensorIDCount = 0; //private
		$this->actuatorIDCount = 0;//private
		$this->alarmIDCount = 0;//private

	}

	function addSensor($id, $Name){ 
		$this->sensorsID[$this->sensorIDCount] = $id;
		$this->sensors[$id] = $Name; 
		$this->sensorIDCount +=1;
	}
	function addActuator($id, $Name){ 
		$this->actuatorsID[$this->actuatorIDCount] = $id;
		$this->actuators[$id] = $Name;
		$this->actuatorIDCount +=1;
	}

	function addAlarm($id, $Name){
		$this->alarmsID[$this->alarmIDCount] = $id;
		$this->alarms[$id] = $Name;
		$this->alarmIDCount +=1;
	}


	function dispSensorList(){
		$sensorList = array();
		foreach ($this->sensors as $key=> $value) {
			$sensorList[$key] = $value[0];
		}
		return $sensorList;
	}

	function dispActuatorList(){
		$sensorList = array();
		foreach ($this->actuators as $key=> $value) {
			$sensorList[$key] = $value[0];
		}
		return $sensorList;
	}


	//DEBUG FUNCTIONS ======================
	function display(){
		echo $this->DeviceId;
		echo $this->DeviceType;
		echo $this->IdStage;
		echo $this->StageName;
		echo "<br> SENSORS ----------------- <br>";
		foreach ($this->sensors as $key =>  $value) {
			echo "-Id: " .$key." : ";
			print_r($value);
			echo "<br>";  
		}
		echo "<br> ACTUATORS ----------------- <br>";
		foreach ($this->actuators as $value) {
			echo "-".$value."<br>"; 
		}
		echo "<br> ALARMS ----------------- <br>";
		foreach ($this->alarms as $value) {
			echo "-".$value."<br>"; 
		}

		echo "<br> ----------------- <br>";
	}

}

?>

	
<?php

//$dbm = new SDBManager();
//$dbm->ReadInventory();
 ?>
