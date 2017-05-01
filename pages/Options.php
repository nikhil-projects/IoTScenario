<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
-->
<link rel="stylesheet" href="includes/forms/forms.css">
<link rel="stylesheet" href="pages/options.css">
<link rel="stylesheet" href="config/colorPalette.css">

<?php include '../common/formLib.php';?>
<script src="pages/Options.js"></script>

<?php
	$filepath =  '../config/CONFIG.ini';
	$filepathCSS = '../config/colorPalette.css'; 
	$filepathPHP = '../config/config.php';
	$conf = parse_ini_file($filepath,1);
	$formfields = array('SERVER_NAME' => '', 
		'ADMIN_NAME' => '', 
		'ADMIN_PASSWORD' => '', 
		'DATABASE_NAME' => '', 
		'DEVICE_KEY' => '', 
		'DEBUG_MODE' => '',
		'COLOR_THEME' => ''
		);

	 	if(isset($_POST['submit'])){
	 		$verified = checkFields($formfields);
	 		if(!$verified){
			echo '<script>alert("There are empty fields, there cant be empty fields :(")</script>';
			}else{
				//We get the field value
				foreach ($formfields as $key => $value) {
					$formfields[$key] = $_POST[$key];
					echo $formfields[$key];

				}
				update_CSS_file($formfields['COLOR_THEME'], $filepathCSS); 
				update_ini_file($formfields, $filepath); 
				update_PHPConfig_file($formfields, $filepathPHP);
				
				echo '<a href="../index.php"><h1 style="background:blue; color:white;"><-Return</h1></a>';
				echo '<script>alert("Data Updated :)")</script>';
			}
	 	}//isset

	 //Check if all fields are empty
	function checkFields($field){
		$verified = TRUE;
		foreach($field as $v => $value) {
   			if(!isset($_POST[$v]) || empty($_POST[$v])) {
   				if ($v != 'COLOR_THEME') {
   					echo "Field - > ".$v." Is Empty!!";
      				$verified = FALSE;
   				}
   				
   			}
		}
		return $verified;
	 }

	//Plot the config.ini values
	function plotConfValue($conf,$type,$field){
		if($type == 'text'){
			echo '<input name = "'.$field.'" type="text" value="'.$conf[$field].'">'; 
		}elseif ($type == 'bool') {
			if($conf[$field]){
				echo '<input type="radio" name="'.$field.'" value="True" checked>Enabled ';
				echo '<input type="radio" name="'.$field.'" value="False">Disabled';
			}else{
				echo '<input type="radio" name="'.$field.'" value="True">Enabled ';
				echo '<input type="radio" name="'.$field.'" value="False" checked>Disabled';
			}
		}

	}//End plot value


	
	//Update  ini file
	function update_ini_file($data, $filepath) { 
		$content = ""; 
		foreach($data as $key=>$value){

			$content.= $key ." = ". $value ."\n";
			echo '<li>'.$content.'</li>';
		}
		
		//write it into file
		if (!$handle = fopen($filepath, 'w')) { 
			return false; 
		}

		$success = fwrite($handle, $content);
		fclose($handle); 

		return $success; 
	}

	//Update Color file
	function update_CSS_file($data, $filepath) {
		if($data == 'agreen' || $data =='BlGr'){
			$content = ':root {--defaultColor:var(--algreen);';
			$content .= '--InventorygenMessage:var(--aorange);';
			$content .='--TopBarUserLogOut:(--aorange);';
			$content .='--LeftBarColor:var(--agreen);';
			$content .='--TopBarUser:var(--aorange);';
			
			$content.='}';
		}else{
		$content = ':root {--defaultColor:var(--'.$data.');}'; 
		}
		//write it into file
		if (!$handle = fopen($filepath, 'w')) { 
			return false; 
		}

		$success = fwrite($handle, $content);
		fclose($handle); 

		return $success; 
	}//End CSS
	function update_PHPConfig_file($data, $filepath){
		$content = '';
		$content.="<?php\n";
		foreach($data as $key=>$value){
			$content.='define("'. $key .'","'. $value .'");';
			$content.="\n";
		}
		$content.='?>';
		//write it into file
		if (!$handle = fopen($filepath, 'w')) { 
			return false; 
		}
		$success = fwrite($handle, $content);
		fclose($handle); 
		return $success;
	}

?>

<h1>Options</h1>

<form action="pages/Options.php" method="post">
<ul>
	<li><h2>Server Name</h2></li>
	<?php plotConfValue($conf,'text','SERVER_NAME');?>
	
	<li><h2>Database name</h2></li>
	<?php plotConfValue($conf,'text','DATABASE_NAME');?>

	<li><h2>Database name admin</h2></li>
	<?php plotConfValue($conf,'text','ADMIN_NAME');?>

	<li><h2>Admin password</h2></li>
	<?php plotConfValue($conf,'text','ADMIN_PASSWORD');?>

	<li><h2>Device key</h2></li>
   <?php plotConfValue($conf,'text','DEVICE_KEY');?>

	<li><h2>Debug Mode</h2></li>
	<?php plotConfValue($conf,'bool','DEBUG_MODE');?>

	<li><h2>Color Theme</h2></li>
	<!--<?php displayColors('ColorPicker1'); ?>-->

	<select name="COLOR_THEME" id="ColorPicker1" class="color">
		<option selected value="red">Red</option>
				<option value="blue">Blue</option>
				<option value="lgreen">Green</option>
				<option value="cyan">Cyan</option>
				<option value="purple">Purple</option>
				<option value="pink">Pink</option>
				<option value="turc">Teal</option>
				<option value="orange">Orange</option>
				<option value="black">Black</option>
				<option value="agreen">BlGr</option>
	</select>


	<br><br>
	<li> <input type="submit" value="Save preferences" name="submit"></li>
</ul>
</form>