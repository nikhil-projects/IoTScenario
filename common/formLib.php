<?php 

	function LoadImages(){
		//Load images from folder
		$dirname = "../resources/icon-black/";
		$dir2 = "resources/icon-black/";
		$images = glob($dirname."*.png");
		$opendir = opendir($dirname);
		while (false !== ($filename = readdir($opendir))) {
			if($filename != '.' && $filename != '..'){
	    		$files[] = $dir2.$filename;
	    	}
		}
		return $files;
	}

	function displayIcons($files, $idName){
		echo '<ul class="icons" id="'.$idName.'">';	
		foreach($files as $image) {
    		echo '<li><img src="'.$image.'"></li>';
		}
		echo '</ul>';
	}

	function displayColors($idName){
		echo '<select name="COLOR-THEME" class="color" id="'.$idName.'"> 
				<option SELECTED value="red">Red</option>
				<option value="blue">Blue</option>
				<option value="lgreen">Green</option>
				<option value="cyan">Cyan</option>
				<option value="purple">Purple</option>
				<option value="pink">Pink</option>
				<option value="turc">Teal</option>
				<option value="orange">Orange</option>
				<option value="black">Black</option>
			</select>';
	}
	//$files = LoadImages(); //We load the images when the page is called.

 ?>
 <script>
 	//Color picker de la derecha, el de los sensores
$('body').on('click','.color',function(event) {
	var id = $(this).attr('id');
	var name = $('#'+id+' :selected').val();
	$(this).css({
		background: 'var(--'+ name + ')'
	});
});
 </script>
