/*
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
*/
$(document).ready(function(){

var deleter = false;
//Permitir el borrado de elementos de la barra de navegacion
$('#DeleteDevice').click(function(event) {
	if(!deleter){
		$('.deleter').show();
		deleter = !deleter;
	}else{
		$('.deleter').hide();
		deleter = !deleter;
	}
});



});

