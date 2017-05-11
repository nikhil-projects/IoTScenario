<?php
/*
Módulo de LOGIN de usuarios. Comprueba si un usuario existe en la base de datos.

*/
 include 'config/config.php'; 
 include 'ComDB/DBManager.php';
 define("DEBUG", false);

class APLogin
{
	var $ErrorMsg; //Mensaje que sale si el usuario o la contraseña son erroneos String
	var $CorrectMsg; //Mensaje que aparece si el acceso es correcto String

	var $userDB; //Campo de usuario en la base de datos.
	var $userPW; //Campo de contraseña en la base de datos.

	var $user;
	var $pass;
	var $page;
	//Parte lógica:
	//===============================
	//Se pasa como parámetro el name de user, el de pass y la página que se carga en caso de que la 
	//petición sea correcta.
	function __construct($userID, $passID, $page, $userDB, $userPW)
	{
		$this->user = $this->pass = "";
		$this->page = $page;

		 $this->userDB = $userDB;
		 $this->userPW = $userPW;

		//Leemos el formulario
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		  $this->user = $this->checkSintax($_POST[$userID]);
		  $this->pass = $this->checkSintax($_POST[$passID]);
		  $this->checkUSER();
		}
	}

	//Mira que no se introduzcan valores exreaños que puedan inducir a crackeos
	function checkSintax($data) {
 		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}

	function checkUSER(){
		//Comprobamos las credenciales en la base de datos:
		$dbm = new SDBManager();
		$exist = $dbm->checkUser($this->user, $this->pass, $this->userDB, $this->userPW);
		if($exist){
			$this->plotCorrect();
		}else{
			if(DEBUG) echo "Aquiiiiiiiii";
			$this->plotError();
		}
	}

	//Muestra un mensaje de que la información entrada es correcta
	function plotCorrect(){
		header('Location: '.$this->page);
	}

	//Muestra un mensaje de que la información entrada es incorrecta
	function plotError(){
		$error =" Usuario o constraseña incorrecto :(";
		echo "Alerta<script>alert(".$error.");</script>";

	}

}//Fin clase

?>