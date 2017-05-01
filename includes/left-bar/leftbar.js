/*
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
*/
$(document).ready(function(){

 $('.leftbar li').click(function(event) {
 	//We get the page name marked by the Id
 	var page = $(this).attr('id');

 	//We load the page in the main div. We use ajax because 
 	// probably the page has PHP code.
 	$.ajax({
 	 	url: "pages/" + page + ".php"
	}).done(function(data) { // data what is sent back by the php page
  		$('.main').html(data); // display data
	});
});//Click end


});