<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Módulo para almacenar firmwares
====================
-->

<?php 

	class MyFirmwares
	{

		var $dir;
		var $fileArr;
		
		function __construct()
		{	
			$this->dir = "../DshboardModules/firmwares";
			$this->dirglob = "DshboardModules/firmwares";
			$this->readDir();
			$this->make();
			
		}
	
		function readDir(){
			//Array de los ficheros contenidos en el directorio
			$this->fileArr = scandir($this->dir);
		}

		function downLoad(){
			header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=example.csv');
header('Pragma: no-cache');
readfile("/path/to/yourfile.csv");
		}



		function make(){
			$html = '<div class="var-element" style="height: 310px;">
			<div class="title"><p>My Files</p></div>
			<div class="content" style="font-size:12px;">
				<div class="list">
					<table>'.$this->compose().'</table>
				</div>
			</div>
		</div>';
			echo $html;
		}

		function compose(){
			$html = '';
			foreach ($this->fileArr as $value) {
				if($value != '.' && $value != '..' && $value != ' .DS_Store'){
					$html .='<tr>
							<td style="width: 20%;">📋 '.$value.'</td>
							<td style="width: 70%;">'.$this->readLine($value).'</td>
							<td class="down" style="width: 10%;"><a href="'.$this->getPath($value).'" download="file">&#11015;</a></td>
						</tr>';
				}//End if
			}//End foreach
			return $html;
		}//End compose


		//Lee la primera fila de un fichero, se entiende como la 
		//descripción del documento
		function readLine($file){
			$f = fopen($this->dir."/".$file, 'r');
			$line = fgets($f);
			fclose($f);
			return $line;
		}

		function getPath($file){return $this->dirglob."/".$file;}



	}//End Class





 ?>

 <?php //new MyFirmwares();  ?>