<style>
	body{background: black; color:green; font-family: monospace; font-size: 15px;}
	*{font-size: 15px;}
	table{
		
		width: 50%;
		text-align: center;
	}

	th{
		background: green;
		color: black;
	}

	table,th,tr{border: solid 1px green;}


</style>

<?php 



include 'ComDB/DBManager.php';
$dBM = new SDBManager();
//$query = 'SELECT * FROM Devices';

$query = "SELECT * FROM Stage";
$dBM->plotQuery($query);

$query = "SELECT d.idDevices as IdentificadorDispositivo, s.idSensor FROM Devices d, Sensor s WHERE d.idDevices=s.Devices_idDevices and d.idDevices = 1";
$dBM->plotQuery($query);

$query = "SELECT d.idDevices as DeviceNumber, dt.Name as DeviceName, st.Name as StageName, s.idSensor as SensorId, s.Name as SensorName FROM Devices d, DeviceType dt, Stage st, Sensor s WHERE dt.idDeviceType = d.DeviceType_idDeviceType and st.idStage = d.Stage_idStage and s.Devices_idDevices = d.idDevices";
$dBM->plotQuery($query);




?>

<br><br>

</table>