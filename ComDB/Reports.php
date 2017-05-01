<?php 
require('fpdf16/fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('logoIoT.png',10,6,20);
    // Arial bold 15
    $this->SetFont('Helvetica','B',15);
    // Move to the right
    $this->Cell(50);
    // Title
    $this->Cell(100,10,'Sensor Report',0,1,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Helvetica','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

function SCredentials($arr){
	
	$this->ChapterTitle('1', 'Credentials');
	$this->SetFont('Helvetica','',9);
	$this->Cell(0,10,'> Device Name: '.$arr["DName"],0,1);
	$this->Cell(0,10,'> Sensor Name: '.$arr["SName"],0,1);

	$this->ChapterTitle('2', 'Statistics');

	$this->SetFont('Helvetica','',9);

	$this->Cell(0,10,'> First sample: '.$arr["Start"],0,1);
	$this->Cell(0,10,'> Last sample: '.$arr["End"],0,1);

	$this->Ln(3);
	$this->Cell(0,10,'> Total samples: '.$arr["Total"],0,1);
	$this->Cell(0,10,'> Samples Average: '.$arr["Media"],0,1);
	$this->Cell(0,10,'> Max Value: '.$arr["Max"],0,1);
	$this->Cell(0,10,'> Min Value: '.$arr["Min"],0,1);

	
}

function plotSamples($arr){
	$this->ChapterTitle('3', 'Samples');
	$this->SetFont('Helvetica','',9);
	foreach ($arr as $va) {
		$this->Cell(0,10,$va["time"].'<--->'.$va["value"],0,1);
	}
}


function ChapterTitle($num, $label)
{
    // Arial 12
    $this->SetFont('Helvetica','',12);
    // Background color
    $this->SetFillColor(128,128,128);
    // Title
    $this->Cell(0,6," $num : $label",0,1,'L',true);
    // Line break
    $this->Ln(4);
}

}//End Class

?>


	

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
	* Se encarga de generar documentos
	*/
	class Report
	{
		
		function __construct()
		{
			$this->Loader();
		}//construct
		
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

		function GenerateReport($id){
			$pdf = new PDF();
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Helvetica','',12);
			
			//Tomamos el valor de las medias
			$db = new SDBManager();
			$arr = $db->GetReportData($id);
			$pdf->SCredentials($arr);

			//Tomamos el valor de todas las muestras
			$arr = $db->getAllSensorSamples($id);
			$pdf->plotSamples($arr);

			$pdf->Output('IoTScenarioReport.pdf','F');
		}

		function GenerateMatlab($ids){
			$db = new SDBManager();
			$txt = '%IoTScenario Sensor Samples'.PHP_EOL ;

			$arr = $db->GetReportData($ids);
			$txt .= '%Record Begin: '.$arr["Start"].PHP_EOL;
			$txt .= '%Record End: '.$arr["End"].PHP_EOL;
			$txt .= 'samples = [ ';
		
			$arr = $db->getAllSensorSamples($ids);
			
			
			$count = 0;
			foreach ($arr as $va) {
				if($count == 0){
					$txt .= $va["value"];
					$count ++;
				}else{
					$txt .= ','.$va["value"];
				}
			}
			$txt.='];';
			
			$myfile = fopen("IoTScenario.m", "w") or die("Unable to open file!");
			fwrite($myfile, $txt);
			fclose($myfile);
		}

		function DeleteSamples($id){
			$db = new SDBManager();
			$arr = $db->deleteAllSamples($id);
		}

		//Interpreta la peticiones que llegan mediante AJAX
		function Switcher($ids, $func){	
			switch ($func) {
				case 'GenMatlab':
					$this->GenerateMatlab($ids);
				case 'GenReport':
					$this->GenerateReport($ids);
				case 'DeleteSamples':
					$this->DeleteSamples($ids);
				break;
			
			default:
				echo "LOAD FAILED";
				break;
		}//End Switch
		}//End Switcher
		//===================================================

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

			//For debug
		function StoreInTxt($request_body){
			$baseDatos = fopen("baseDatos.txt", "a") or die("No se ha podido abrir el documento :(");
			fwrite($baseDatos, "->".$request_body."\n");
			fclose($baseDatos);
		}
	}//End class


 ?>

<?php 
	new Report();
 ?>
