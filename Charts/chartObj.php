<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Objeto para plotear gráficos con Chart.js
====================
-->

<?php  

/**
* 
*/
include 'Buffer.php';

/*
EXAMPLE:
include '../ComDB/DBManager.php';
include '../config/config.php';
<?php 
new chartObj([14 , 17],30) ?>

*/
class chartObj
{

	//Array asociativo que almacena los valores de cada sensor, estará compuesto por instancias de 
	//sBuffer
	var $buffers = array(); 
	//Ids de los sensores asociados al gráfico
	var $ids = array();

	var $nmuestras;

	//Constructor: $sensorsID-> Array con los IDs de los sensores que se pretenden monitorizar
	// $nmuestras -> tamano del buffer 
	function __construct($sensorsIds,$nmuestras)
	{
		$this->nmuestras = $nmuestras;
		$this->ids = $sensorsIds; 

		//Creamos un buffer para cada sensor que alamacenará $nmuestras:
		foreach ($sensorsIds as $value) {
			//Iniciamos un buffer

			$this->buffer["$value"] = new SBuffer($nmuestras);


			//Realizamos una lectura inicial de la 
			//base de datos:
			$dbm = new SDBManager();
			$samples = $dbm->getNSamples($value,$nmuestras);
			$samples = array_reverse($samples);

			//Inicializamos los buffers con las últimas nMuestas 
			$this->buffer["$value"]->AddArray($samples);
		}
		$this->CreateChart();

	}//Fin constructor

	//Añade una muestra al buffer
	function AppendSample(){
		//Tomamos la última muestra que ha llegado 
		//y la añadimos al buffer.
		foreach ($this->ids as $value) {
			$dbm = new SDBManager();
			$sample = $dbm->getNSamples($value,1);

			$this->buffer["$value"]->addElement($sample);
		}
	}//End getSample


	//Pone un color a cada gráfico
	function setColor($val){
		$turq = "rgba(26, 188, 156,";
		$blue = "rgba(52, 152, 219,";
		$purp = "rgba(155, 89, 182,";
		$orange = "rgba(230, 126, 34,";
		$yell = "rgba(241, 196, 15,";
		$grey = "rgba(189, 195, 199,";

		switch ($val) {
			case 1:
				return $turq;
				break;
			case 2:
				return $blue;
				break;
			case 3:
				return $purp;
				break;
			case 4:
				return $orange;
				break;
			case 5:
				return $yell;
				break;
			default:
				return $grey; 
				break;
		}//End switch

	}//End set color

	//Crea un marco para los gráficos:
	function getInitialValues(){
		$cadena ="";
		$first = true;
		$struct = "";
		$strucCount = true;
		$count = 1;
		//Plot de datos para cada sensor que tenga
   		//el dispositivo
   		foreach ($this->buffer as $value) {
	   		//$valuep = array_reverse($value,true);

	   		foreach ($value as $val) {
	   			if($first){
	   				$cadena = "[";
	   				$cadena .= strval($val["value"]);
	   				$first = false;
	   			}else{
	   				$cadena.= ",". strval($val["value"]);
	   			}
	   		}//End foreach
	   		$first = true;
	   		$cadena .="]";

	   		if($strucCount) $strucCount = !$strucCount;
			else $struct .=",";
			

	   		$struct .="{
	   			label: 'Sensor".strval($count)."',
				fillColor: '".$this->setColor($count)."1.0)',
				borderColor: '".$this->setColor($count)."1.0)',
				backgroundColor: '".$this->setColor($count)."0.1)',
    			pointColor: '".$this->setColor($count)."1.0)',
    			lineTension:.2,
                 pointRadius:3,
				data: ".$cadena."
	   		}";
	   		$count++;
		}//End foreach
		return $struct;
	}

	//retorna valores para el eje X del gráfico para un intervalo [1,n];
	function genLabels(){
		$cad = "[";
		$first =  true;
		for($i=0; $i<$this->nmuestras; $i++){

			if($first){
				$cad .= strval($i + 1);
				$first = !$first; 
			}else{
				$cad.=','.strval($i + 1);
			}//if
		}//for
		return $cad."]";
	}//GenLabels

	function CreateChart(){
		//Añadimos la libreria
		echo "<script>
   				var chartdata = {
   					labels: ".$this->genLabels().",
   					datasets:[";
   		//-----Datos
   		echo $this->getInitialValues();
		//final del script			
   		echo "//Fin--Datos
   				]};</script>";

   		echo '<canvas id="myChart"></canvas>';

   		//echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>';

		echo "
			<script>
			var ctx = document.getElementById('myChart').getContext('2d');
				ctx.canvas.width = 300;
				ctx.canvas.height = 70;
		
			</script>";

		echo '
			<script>
			var  myLineChart = new Chart(ctx , {
    		  type: "line",
    		  data: chartdata,
    		  options: {
    		  animation: {
        			duration: 0
    		  }
				} 
			});</script>';
	
	}//End CreateChart


}//Class end
?>

<?php //new chartObj([14 , 17],30) ?>

