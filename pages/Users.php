<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Registra y muestra a los usuarios de la plataforma
====================
-->
<?php 
include '../ComDB/DBManager.php'; //Manage the DB connection
include '../config/config.php'; //Manage the DB connection
class UserPage
{
	function __construct(){
		$this->readForm();	
	}

	//Realiza un plot de los usuarios registrados en la plataforma
	function getUsers(){
		$sql = "SELECT u.idUsers as id, u.UserName as name , u.UserLastName as last, u.UserEmail as mail, u.Permissions as p, u.FirstConection as fc
			FROM Users u
		";
		$db = new SDBManager();
		$data=$db->ReadRecursive($sql);
		foreach ($data  as $va) {
			$this->plotUser($va);
		}//foreach
	}//getUser

	function plotUser($arr){
		$html='<tr>';
			$html.= '<td>'.$arr["id"].'</td>';
			$html.='<td>'.$arr["name"].'</td>';
			$html.='<td>'.$arr["mail"].'</td>';
			$html.='<td>'.$arr["p"].'</td>';
			$html.='<td>'.$arr["fc"].'</td>';
		$html.='</tr>';
		echo $html;
	}//End plot user

	function readForm(){
		if(isset($_POST["submit"])){
			$name = $_POST["name"];
			$lastname = $_POST["Lname"];
			$email = $_POST["Email"];
			$perm = $_POST["Permissions"];
			$paswd = $_POST["paswd"];

			//Faltaría un check de que el usuario ya se ha registrado
			if($name != "" && $lastname != "" && $email != "" && $perm != "" && $paswd !="" ){
					//Entramos el nuevo usuario en la base de datos
					$db = new SDBManager();
					$db->Insert('Users', ['UserName', 'UserLastName', 'UserEmail','Permissions', 'UserPassword'],['"'.$name.'"', '"'.$lastname.'"', '"'.$email.'"', '"'.$perm.'"', '"'.$paswd.'"']);
					header("Location: ../platform.php");
					die();
			}else{
				header("Location: ../platform.php");
					die();
				echo "<script>alert('There are empty fields! :(');</script>";
			}//End if empty field
		}//End if

		//Borrar usuario de la base de datos
		if(isset($_POST["delUser"])){
			if($_POST['deleteId']!=""){
				$sql="
					DELETE u.*
					FROM Users u
					WHERE u.idUsers = ".$_POST['deleteId'];
				$dbs = new SDBManager();
				$dbs->rawSql($sql);
				header("Location: ../platform.php");
				die();
			}else{
				header("Location: ../platform.php");
				die();
			}
		}//End isset
	}//End function
}//End class
?>

<script>
	
	/*$(document).ready(function(){
		$("#submit").click(function(event) {	
			$directoryPath = "pages/Users.php";
			var userform = document.getElementById("userForm");
	    	var fd = new FormData(userform);		
				$.ajax({
			            type: "POST",
			        	url: directoryPath,
			        	data: userform,
			            success : function (data) {
			                $(".main").html(data);
			            }
			 		});//End ajax
				});//End JQuery
		});/* End submit */

</script>

<h1>&#9776; Users</h1>
	
<table>
	<tr style="background: var(--defaultColor);">
		<td><b>ID</b></td>
		<td><b>Name</b></td>
		<td><b>Email</b></td>
		<td><b>Permission</b></td>
		<td><b>First Connection</b></td>
	</tr>
	<?php $up = new UserPage();  $up->getUsers(); ?>


</table>

<h1>&#10003; New User</h1>
<form  method="POST" id="userForm" action="pages/Users.php" >
	<table>
		<tr style="background: var(--defaultColor);">
			<td><b>Name</b></td>
			<td><b>Last name</b></td>
			<td><b>Email</b></td>
			<td><b>Permission</b></td>
			<td><b>Password</b></td>
		</tr>
		<!--Nuevo usuario-->
		<tr style="">
			<td><input type="text" name="name"></td>
			<td><input type="text" name="Lname"></td>
			<td><input type="text" name="Email"></td>
			<td><input type="text" name="Permissions"></td>
			<td><input type="password" name="paswd"></td>
		</tr>
	</table>
	<input type="submit" name="submit" id="submit" value="Register user">
</form>

<h1>&#8855; Delete User</h1>

<form  method="POST" id="userForm" action="pages/Users.php" >
	<table>
		<tr>
			<td>User Id</td>
			<td><input type="text" name="deleteId"></td>
		</tr>
	</table>
	<input type="submit" value="Delete user" name="delUser">
</form>

<!--Estilos-->
<style>
	table{
	width: 100%;
	text-align: left;
	border-collapse: collapse;
}

h1{
	padding: 30px 0px 30px 0px;
	font-size: 20px;
}

.content .list{
	height: 80%;
	width: 100%;
	overflow-y:scroll;
}

td{
	padding: 10px;
}

td.down{
	transition: all .5s;
	cursor: pointer;
}

td.down:hover{
	transform: scale(1.2);
}
tr:nth-child(odd){
    background: #ddd;
}
 
tr:nth-child(even){
    background: #FFFFFF;
}
tr{
	border-bottom: 2px solid #ddd;
}

tr:hover{
	background: var(--defaultColor);
	color: white;
}

input[type=text] {
	font-size: 17px;
    width: 100%;
    padding: 5px 5px;
    margin: 8px 0;
    box-sizing: border-box;
}

input[type=password] {
	font-size: 17px;
    width: 100%;
    padding: 5px 5px;
    margin: 8px 0;
    box-sizing: border-box;
}

input[type=submit] {
	font-size: 17px;
	cursor: pointer;
    width: 15%; 
    color: white;
    border-radius: 20px;
    border:solid 2px rgba(0,0,0,0);
    height: 30px;
    background: var(--defaultColor);
    text-align: center;

    margin: 8px 0;
    box-sizing: border-box;
}
input[type=submit]:hover{
	border:solid 2px var(--defaultColor);
	background: white;
	color: var(--defaultColor);
}
</style>