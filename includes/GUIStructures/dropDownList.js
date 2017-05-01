/*
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
*/
$(document).ready(function(){
var state = false;
var degree = 0;

//Generador de JSON
$('html').on('click', '.genMessage', function(event) {
    event.preventDefault();
    var id = $(this).attr('id');
 
    var selector = id.substring(0,4);

    if(selector == "Inve"){
    id = id.replace('Inve', '');
    var directoryPath = 'pages/Inventory.php';
    //We get the value of JSON file calling JSONgenerator of PHP. Where 
    // objectt is the instance of device that we want to
    //obtain the value and function is the function 
    // that we want to exec in this case showJSON.
    var mes = '{"object":"'+Number(id)+'","function":"showJSON"}';   
    $.ajax({
        type: "POST",
        url: directoryPath,
        data: {message: mes},
        complete: function(res) {    
            $('html').append(res.responseText);
        }
    });
    //End selector GenMessage
    }else if (selector == "Moni") {
        id=id.replace('Moni', '');
        var directoryPath = 'pages/Monitoring.php';
        var mes = '{"object":"'+Number(id)+'","function":"MonitoringDevice"}'; 
        $.ajax({type: "POST",url: directoryPath,data: {message: mes},
        complete: function(res) {
            $('.main').html("");    
            $('.main').append(res.responseText);
        }
    });
    //End gen Monitoring
    }


});//End click gen message
$('.cross').click(function(event) {
    $('.messageGenerated').remove();
});


//$('.mssg').draggable();




//Interfaz gráfica y Eventos de interfaz
 $('.droplist .dropItem').click(function(event) {
 	rotate('.dropItem');
 $('.options').slideToggle("fast");

 });
function rotate(element){

 	if(!state){
    	$(element).css({
    		transform: 'rotate(180deg)',
    		transition: '.3s'
    	});
    	state = !state;	
    }else{
    	state = !state;
    	//$(element).toggleClass('rotate-reset');
    	$(element).css({
    		transform: 'rotate(0deg)',
    		transition: '.3s'
    	});
    }}





//Delete element from device list
$('.deleter').click(function(event) {
    //We get the id
    //Tomamos el id del dispositivo para poder borrarlo de la 
    //base de datos.
    var id = $(this).attr('id');

    //Nos comunicamos con la página que contiene el php 
    //que borra el elemento de la base de datos
      var directoryPath = 'ComDB/Handler.php';
      var mes = {"data":id,"function":"DeleteDevice"};
      $.ajax({
        type: "POST",
        url: directoryPath,
        data: {message: mes},
        complete: function(res) {   
            console.log(res.responseText);
            //Confirmamos que se ha borrado el elemento
            alert(res.responseText);
            //Recargamos la página
            ReloadInventory();
        }
    });
});//Fin deleter
function ReloadInventory(){
    var directoryPath = 'Pages/Inventory.php';
    $.ajax({
        type: "POST",
        url: directoryPath,
    });
}


});//Fin jquery
