<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Módulo de geolocalización de dispositivos a partir de
la IP. No surtirá ningún efecto con un sistema de ips locales.
====================
-->

<?php  

/**
* 
*/
//include '../ComDB/DBManager.php';
include '../config/config.php';
class DeviceGeo
{
	
	var $GoogleApiKey="AIzaSyDGRX-r3gNTr6cGQihOrFEI7l5TtzcQSko";
	var $html;
	var $Locations; //Almacena las latitudes y longitudes
	var $CenterLat;
	var $CenterLng;
	var $mapID;
	var $mapTitle;
	
	function __construct($mapID, $mapTitle, $GoogleApiKey)
	{	
		$this->GoogleApiKey = $GoogleApiKey;
		$this->mapID = $mapID;
		$this->mapTitle = $mapTitle;
		$this->Locations = [];
		
		//Programa
		$this->Make();
	}

	//Obtiene información de esta IP a través de una base de datos externa
	// llamada API-IP
	function getCredentials($ip,$deviceName){
		$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
		if($query && $query['status'] == 'success') {
	  		$cred["Pais"] = $query['country']; 
	  		$cred["Ciudad"] = $query['city'];
	  		$cred["latitud"] = $query['lat'];
	  		$cred["longitud"] = $query['lon'];
	  		$cred["nombre"] = $deviceName;
	  		return $cred;
	  		
		} else {
			return $this->getLocal($deviceName);
		}
		
	}//End credentials

	//1.-Lee las IPs de los dispositivos de la base de datos y compone 
	// un array con las credenciales de esta IP
	function getIPs(){
		$db = new SDBManager();

		//Tomamos el valor de la ip y del nombre de dispositivo
		$sql = 'SELECT d.IP as ip, dt.Name as name
				FROM Devices d
				JOIN DeviceType dt ON d.DeviceType_idDeviceType = dt.idDeviceType';
		
		$ipName = $db->ReadRecursive($sql);
		foreach ($ipName as $val){
			//Localizamos la ip
			if($val["ip"] != "" && $val["ip"] != "0.0.0.0"){
				$inf = $this->getCredentials($val["ip"],$val["name"]);
				echo "<br>";
				array_push($this->Locations, $inf);
			}//if
		}//foreach


	}//get ip

	//2.-Crea un string con la información del dispositivo para pasarlo al JS
	function CreateArray(){
		$in = $this->Locations;
		$strArr = 'var locations = [';
		$first = true;
		foreach ($this->Locations as $va) {
			if(!$first) $strArr.=',';
			$strArr.="['".$va['nombre']."','".$va['Ciudad']."',".$va['latitud'].",".$va['longitud']." ]";
			$first=false;
		}

		$this->CenterLat = $va['latitud'];
		$this->CenterLng = $va['longitud'];
		
		$strArr.="];";

		return $strArr;
	}//End createArray

	function Cities(){
		$html = '';
		foreach ($this->Locations as $va) {
			$html.="<tr>";
			$html.='
				<td>'.$va["nombre"].'</td>
				<td>'.$va["Ciudad"].'</td>
				<td>'.$va["latitud"].'</td>
				<td>'.$va["longitud"].'</td>
			';
			$html.="</tr>";
		}//End foreach
		return $html;
	}//End cities

	function Script(){
		$str="
			<script>
			function initDevices() {
        		".$this->CreateArray()."

        		var map = new google.maps.Map(document.getElementById('".$this->mapID."'), {
          		zoom: 10,
          		center: {lat: ".$this->CenterLat.", lng: ".$this->CenterLng."}
        	});

        	for (var i = locations.length - 1; i >= 0; i--) {
        		var marker = new google.maps.Marker({
          			position: {lat: locations[i][2],lng:locations[i][3]},
          			map: map,
          			title: 'IoTScenario Server'
        		});
        		marker.setMap(map);
        	}
      		}//Final
			</script>

		  <script async defer
    		src='https://maps.googleapis.com/maps/api/js?key=".$this->GoogleApiKey."&callback=initDevices'>
   			 </script>
		";
		return $str;
	}//End script

	//En caso de que la IP corresponda a una red de área local 
	// Tomaremos la de area local externa.
	function getLocal($deviceName){
		$externalContent = file_get_contents('https://www.google.es');
		preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
		$this->ServerIP = $m[0];
		$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

		if($query && $query['status'] == 'success') {
  			$cred["Pais"] = $query['country']; 
	  		$cred["Ciudad"] = $query['city'];
	  		$cred["latitud"] = $query['lat'];
	  		$cred["longitud"] = $query['lon'];
	  		$cred["nombre"] = $deviceName;
		} 
		return $cred;
	}

	function CHtml(){
		$html = '<div class="var-element">
			<div class="title"><p>'.$this->mapTitle.'</p></div>
			<div class="content">
				<div class="mapi" id="'.$this->mapID.'"></div>
				
				<div class="ubications">
					<table style="text-align: center;">
						<tr>
							<td><b>Device</b></td>
							<td><b>City</b></td>
							<td><b>Lat</b></td>
							<td><b>Lon<b></td>
						</tr>
						<!--Elements-->
						'.$this->Cities().'
						<!--End Elements-->
					</table>
				</div><!--Ubicaciones-->
			</div>
		</div><!--End-->';
		return $html;
	}//End chtml


	function make(){
		$this->getIPs();////1.-Lee las IPs de los dispositivos de la base de datos y compone 
	// un array con las credenciales de esta IP
		$this->CreateArray();//2.-Crea un string con la información del dispositivo para pasarlo al JS

		echo $this->CHtml();//Hacemos un plot del html
		echo $this->Script();//Ploteamos el script
	}//End make

	function debug($str){
		$fichero = '../DashboardModules/test.txt';
		$actual = file_get_contents($fichero);
		$actual .= "\n".$str."\n";
		file_put_contents($fichero, $actual);
	}

}//End class

?>

<?php //new DeviceGeo("minimap","Devices GeoLocation","AIzaSyDGRX-r3gNTr6cGQihOrFEI7l5TtzcQSko"); ?>