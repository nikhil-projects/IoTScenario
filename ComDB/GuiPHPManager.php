<?php 
/*
—————————————— 
Autor: Alvaro Peris Zaragoza
 Ano: 2017
Descripción: 
———————————————
 //Detecting jReques pag 50

*/

/**
* dsa
*/
	//Listens JQuery requests for plot data
	function JQueryListener(){
		//SEGURIDAD Revisar ¿Podría un usuario foraneo acceder a la información?
		if (isset($_POST['message']) && !empty($_POST['message'])) {
	    	$msg = $_POST['message'];
	    	$msg = json_decode($msg,true);

	    	$objectID = $msg['object'] 
	    	$funcName = $msg['function']; 
	    	Switcher($objectID, $funcName);   	

		}//End if
	}//End JQueryListener

	function Switcher($id, $func){
		switch ($func) {
			case 'showJSON':
				$DeviceList[id]->showJSON();
				break;
			
			default:
				break;
		}//End Switch
	}//End Switcher

	JQueryListener();

 ?>
