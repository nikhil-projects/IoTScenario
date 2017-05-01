<!--
=====================
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
Example:
 <?php 
 		new navbarItem('MiNavbar',['Añadir', 'Borrar', 'Elemento']);
  ?>
====================
-->
<link rel="stylesheet" href="includes/GUIStructures/navbar.css">
<link rel="stylesheet" href="common/style.css">
<link rel="stylesheet" href="config/colorPalette.css.css">
<script src="includes/GUIStructures/stage.js"></script>

<?php 
	/**
	* navbarItem
	*/
	class navbarItem
	{
		var $name; //The name displayed as navbar title
		var $elements;// The items that the nav contains. 
		function __construct($name,$elements)
		{
			$this->name = $name;
			$this->elements = $elements; 
			$this->make();
		}

		function make(){
			echo ' <div class="navbarItem">
 	<h1>'.$this->name.'</h1> <ul class="nav">';
 			foreach ($this->elements as $value) {
 				//We strip the spaces for create a id in order to the item can be selected in jQuery
 				$id = str_replace(' ', '', $value);
 				echo '<li class="nav" id="'.$id.'">'.$value.'</li>';
 			}
 			echo '<ul></div>';

		}
	}
 ?>



