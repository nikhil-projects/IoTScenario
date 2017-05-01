<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
====================
-->
<link rel="stylesheet" href="includes/left-bar/leftbar.css">
<script src="includes/left-bar/leftbar.js"></script>

<?php 
	$leftbar = ['Home','Stages', 'Inventory', 'Monitoring', 'DataBase','Options'];
	// The icons showed in the left bar path
	$iconpath = "resources/icons/"
?>

<div class="leftbar">
	<ul>
		<?php foreach ($leftbar as $value) {
			print '<li id="'.$value.'"><div id="bardiv"</div> <img src="'.$iconpath.$value.'w.png"><p>'.$value.'</p></li>';
		} ?>
	</ul>
</div>