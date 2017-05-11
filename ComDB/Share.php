<?php 
/*
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción:  Se emplea para compartir información con clientes

{
  "sKey" : 1234,
  "Message":{
    "MType": "Ping"
  }
}

Example:
*/
include '../config/config.php';
include 'DBManager.php';
class Share
{
	var $msg;

	function __construct(argument)
	{
		
	}

	function RecData(){
			//Leemos el cuerpo de de la petición.
			$request_body = file_get_contents('php://input');
			//Comprobamos que no está vacia
			if($request_body!=""){
				//Decodificamos el mensaje de JSON a Array
				$msg = json_decode($request_body, true);
					//Comprobamos que la contraseña al servidor sea correcta
					if($this->checkPassword($msg["sKey"])){
						//Procedemos a interpretar el mensaje
						$this->msg = $msg;
						$this->interpret();
					}//CheckPassword
			}//request != ""
		}//Rec data

	function checkPassword($key){return ($key == DEVICE_KEY);}

	function interpret(){
		switch ($msg["MType"]) {
			case 'Ping':
					echo "Pong";
				break;
			
			default:
					echo "Bad Request! This instruction does not exists :(";
				break;
		}



	}//Interpret


}

 ?>