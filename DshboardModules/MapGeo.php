<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Módulo de geolocalización, incluye las siguientes funciones:
- Geolocalizar el servidor a través de su IP
====================
-->
<?php 
	// Reportamos los errores pero no las notis
	error_reporting(E_ALL ^ E_NOTICE); 
	/**
	* 
	*/
	class MapGeo
	{
		var $ServerIP;
		var $Ciudad;
		var $Pais;
		var $longitud;
		var $latitud;
		var $GoogleApiKey = 'AIzaSyCJF6mSeFb7rkWbxitlXM6f02EB9Om7bkY';//Clave que proporciona Google para emplear sus plataformas

		var $getIt;
		var $mapID;
		var $mapTitle;

		//El parámetro mapID solo sirve como ID en el DOM
		function __construct($mapID, $mapTitle, $GoogleApiKey)
		{

			$this->GoogleApiKey = $GoogleApiKey;
			$this->mapID = $mapID;
			$this->mapTitle = $mapTitle;
			$this->MakeDashServer();		
		}


		//Obtiene la IP externa del servidor realizando una consulta
		// una web aleatoria y analizando la IP del receptor, osease,
		// la del servidor
		function getServerIP(){
			$externalContent = file_get_contents('https://www.google.es');
			preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
			$this->ServerIP = $m[0];
		}


		//Obtiene información de esta IP a través de una base de datos externa
		// llamada API-IP
		function getCredentials(){
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

		if($query && $query['status'] == 'success') {
  			$this->Pais = $query['country']; 
  			$this->Ciudad = $query['city'];
  			$this->latitud = $query['lat'];
  			$this->longitud = $query['lon'];
  			$this->getIt = true;
		} else $this->$getIt = false;

			return $this->getIt;
		
		}//End credentials


		//Confecciona el script que ejecutará el mapa. Se podría hacer mediante AJAX.
		//De este modo simplificamos las cosas.
		function Script(){
			$script = "<script>
			function initMap() {
        		var myLatLng = {lat: ".$this->latitud." , lng: ".$this->longitud."};

        		var map = new google.maps.Map(document.getElementById('".$this->mapID."'), {
          		zoom: 14,
          		center: myLatLng
        	});

        	var marker = new google.maps.Marker({
          		position: myLatLng,
          		map: map,
          		title: 'IoTScenario Server'
        	});
      		}
    		</script>";
    		$script.=' <script async defer
    		src="https://maps.googleapis.com/maps/api/js?key='.$this->GoogleApiKey.'&callback=initMap">
   			 </script>';

   			 return $script;
		}//End script

		function DashMapHtml(){
			$html = '<div class="var-element" style="height:350px;">
			<div class="title"><p>'.$this->mapTitle.'</p></div>
			<div class="content" id="'.$this->mapID.'"></div>
			</div>';
			return $html;
		}//End DashMapHtml


		//Se encarga de plotear el mapa del servidor
		function MakeDashServer(){
			$this->getServerIP();
			if($this->getCredentials()){
				echo $this->DashMapHtml();
				echo $this->Script();
			}else{
				echo "Unable to load the map :(";
			}//End else

		}//End make dash Server;


	}//Final de la clase
?>

<?php //new MapGeo('SIP', 'Server GeoLocation') ?>