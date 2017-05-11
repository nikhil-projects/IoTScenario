<!-- Álvaro Peris Zaragozá -->
<!-- Home de IoT Plarform -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>IoT platform</title>
	<link rel="stylesheet" href="home.css">
	
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="home.js"></script>
	<link rel="stylesheet" href="config/colorPalette.css">
	
</head>
<body>
	<div class="main">
		<!-- Navbar -->
		<div class="navbar">
		<!--Titulo-->
		<h1><b>IoT</b>Scenario</h1>
			<ul class="he">
				<li><a href="">Download</a></li>
				<li><a href="">Wiki</a></li>
				<li><a href="">About</a></li>
				<li><a href="">Firmware</a></li>
			</ul>
		</div>


		<!--Formulario de acceso-->
		<!-- PHP requerido para la gestión de acceso -->
		<?php include 'pages/APLogin.php' ?>
		<?php new APLogin('user', 'passwd', 'platform.php','UserEmail', 'UserPassword') ?>


		<form action="" method="post">
			<img src="img/logo.png" alt="" height="150" width="150">
			<br>
			<input type="text" placeholder="User" name="user">
			<br>
			<input type="password" placeholder="Password" name="passwd">
			<br>
			<input type="submit" name="btnEnviar" value="Enter">
		</form>

		<!--Footer -->
		<div class="footer">
		<p>IoT Server 2017 Barcelona Versión desarrollo Escola Enginyeria <b>UAB</b></p>
		</div>
	</div>
	<!--Abajo-->
	<!--Diapositiva 1-->
	<div class="abajo" align="center" id="hardware">
		<h2 class="frametitle">Open Hardware</h2>
		<p class="frametitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita quidem, vel possimus molestiae sunt, reprehenderit commodi quis nemo non velit, magnam nesciunt, eius rerum animi debitis doloremque ullam incidunt quo.</p>
		<ul class="placas">
			<li><img src="img/esp1.png" alt=""> <p>NodeMCU Lolin ESP8266</p></li>
			<li><img src="img/esp2.png" alt=""><p>NodeMCU Amica ESP8266</p></li>
			<li><img src="img/esp3.png" alt=""><p>Sparkfun Thing</p></li>
			<li><img src="img/raspi.png" alt=""><p>Raspberry Pi</p></li>

		</ul>
	</div>
	<!--Diapositiva 2-->
		<div class="abajo" align="center">
		<h2 class="frametitle">Network</h2>
		<p class="frametitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita quidem, vel possimus molestiae sunt, reprehenderit commodi quis nemo non velit, magnam nesciunt, eius rerum animi debitis doloremque ullam incidunt quo.</p>
	</div>

		<!--Escenarios-->
		<div class="abajo" align="center" id="escenarios">
		<h2 class="frametitle">Scenarios</h2>
		<p class="frametitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita quidem, vel possimus molestiae sunt, reprehenderit commodi quis nemo non velit, magnam nesciunt, eius rerum animi debitis doloremque ullam incidunt quo.</p>
	</div>

	<!--Standards-->
		<div class="abajo" align="center">
		<h2 class="frametitle">Standards</h2>
		<p class="frametitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita quidem, vel possimus molestiae sunt, reprehenderit commodi quis nemo non velit, magnam nesciunt, eius rerum animi debitis doloremque ullam incidunt quo.</p>
</body>
</html>
