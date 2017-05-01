/*
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
*/
$(document).ready(function(){

// Events
//=================================
 $('.main').hide().fadeIn(500);

$(".but").click(function () {
          $('.screen').slideDown();
    });

$(".closescreen").click(function(event) {
	  $('.screen').slideUp();
});

$('.iz .color').click(function(event) {
	var name = $('.iz .color :selected').val();
	$(this).css({
		background: 'var(--'+ name + ')'
	});
	$('.screen .ic').css({
		border:'solid 2px var(--'+ name + ')'
	});
	$('.screen h1').css({
		color: 'var(--'+ name + ')'
	});
});

//Color picker de la derecha, el de los sensores
$('.screen').on('click','.drch .color',function(event) {
	var id = $(this).attr('id');
	var name = $('#'+id+' :selected').val();
	$(this).css({
		background: 'var(--'+ name + ')'
	});
});

// Functions
//=================================
//Cargamos los iconos del DOM para emplearlos posteriormente
// sin necesidad de volver a realizar una petición al servidor

var numIconos = 0; //El numero total de iconos que hat
function getImages(){
	$('#icon0  img').each(function(n) {
		files[n] = $(this).attr('src');	
		numIconos +=1;	
	});
}
var files = new Array;
getImages();

//Componemos los iconos en html
var idCon = 2;
function plotIcons(){
	var img ="";
	var ic = 0;
	img += "<ul class='icons'id='icon"+ idCon +"'>";
	files.forEach( function(element, index) {
		img += "<li id='icn"+idCon+ic+"'><img src='"+ element + "'></li>";
		ic+=1;
	});
	idCon += 1;
	img += "</ul>";
	return img;
}
//Selector de iconos
$('.screen').on('click','ul.icons li',function(event) {
	event.preventDefault();
	var id = $(this).attr('id');

	//Este id es de la forma icn01 donde la primera cifra
	// es el conjunto y el segundo el id del icono.
	var idsel = id[0] +  id[1] + id[2] + id[3];
	//Quitamos la clase de los demás elementos
	for(var i=0;i<=numIconos;i++){
		$('#'+idsel+i+'>img').removeClass('selected');
	}
	//Añadimos el selected al que se clicko
	$('#'+id+'>img').addClass('selected');

});//End Selector icono


//Maquetamos el sensor
var itemCount = 1;
function addSensor(){

		var ret = "<ul class='sensorTag"+itemCount+"' id='sensor'"+itemCount+">";
		ret += "<div class='deleteSensor' id='sensorTag"+itemCount+"'><p>&#10005;</p></div>";
		ret += plotColors();
		ret += "<input type='text' id='sensorName"+itemCount+"' placeholder='Sensor Signal Name'>";
		ret += "<input type='text' id='sensorUnits"+itemCount+"' placeholder='Samples units cm,C...'>";
		ret += plotIcons();
		ret += "</ul>";	
		itemCount +=1;
		return ret;
}



var colorIter = 1;
function plotColors(){

	var colorstr = '<select class="color" id="color' + colorIter +'">';
	colorstr += '<option value="red">Red</option>';
	colorstr +=' <option value="blue">Blue</option>';
	colorstr +=' <option value="lgreen">Green</option>';
	colorstr +=' <option value="cyan">Cyan</option>';
	colorstr +=' <option value="purple">Purple</option>';
	colorstr +=' <option value="pink">Pink</option>'
	colorstr +=' <option value="turc">Teal</option>';
	colorstr +=' <option value="orange">Orange</option>';
	colorstr +=' <option value="black">Black</option>';
	colorstr +='</select>';
	colorIter += 1;
	return colorstr;
}
//Añadimos un nuevo sensor al DOM
$('#addSensor').click(function(event) {
	var sensorr = addSensor();
	$('#sensor').hide();
	$('#SensorsForm').append(sensorr);
	$('#sensor').show('slow');
});

//Eliminación de sensores
$('.screen').on('click', '.deleteSensor', function(event) {
	event.preventDefault();
	var id = $(this).attr('id');
	$('.'+id).remove();
});


//Gestión de la barra de navegación
//=================================
$('#NewDevice').click(function(event) {
	$('.screen').slideDown();
});

//Obtención y envío de formularios.
$('.screen').on('click','.subBtn',function(event) {
	var formOK = true; //Controla que todo esté correcto en el formulario
	//Obtenemos el valor de las credenciales principales.
	var DeviceName = $('#deviceName').val();
	if(DeviceName == ''){ formOK = false; alert("The field 'Device Name' is mandatory!"); return;}

	var DevName = $('#devName').val();

	var Description = $('#description').val();
	
	var Color = $('#formDevices').find(":selected").val();
	//Para obtener el icono buscamos el que tenga la clase selected.
	
	var Icon = $('#icon0').find('.selected').attr('src');
	if(Icon == ''){ formOK = false; alert("Choose an icon!"); return;}

	//Quitamos el valor del path, solo nos quedamos con imagen.png como referencia.
	Icon = Icon.replace('resources/icon-black/','');
	
	var myJ= {
  		"DeviceName":DeviceName,
  		"DevName":DevName,
  		"Description":Description,
  		"Color":Color,
  		"DeviceIcon":Icon,
  		"Sensors":[]
  		};
  	
  	$('#deviceName').val("");
  	$('#devName').val("");
  	$('#description').val("");
	
	//Obtenemos el valor de los sensores estos vienen definidos
	// por el ItemCount estos se identifican como sensor ItemCount
	for(var i=0; i < itemCount; i++){
		var SN = $('#sensorName'+ i).val();
		if(SN == ''){ formOK = false; alert("The field 'Sensor Signal Name' is mandatory!"); return;}

		var SU = $('#sensorUnits'+i).val();
		if(SU == ''){ formOK = false; alert("The field 'Sample Unit' is mandatory!"); return;}

		var ColorS = $('#color'+i).find(":selected").val();

		cc = i + 1;
		var Ic = $('ul#icon' + cc).find('.selected').attr('src');
		if(Ic== ''){ formOK = false; alert("Choose an icon for sensor!"); return;}
		Ic = Ic.replace('resources/icon-black/','');
		
		//añadimos el elemento a la estructura JSON
		myJ.Sensors.push(
			{"SName":SN,
			"SUnits":SU,
			"Color":ColorS,
			"Icon":Ic}
		);
	}//Fin del for
	

	//Envio del formulario para procesarlo en PHP
	$directoryPath = 'ComDB/Handler.php'

	var mes = {
		"function":"LoadDeviceForm",
		"data":myJ
	} 
	$.ajax({
        type: "POST",
        url: $directoryPath,
        data: {message: mes},
        complete: function(res) {
        	//Texto de respuesta positiva o negativa a la petición
        	console.log('Esto llega: '+ res.responseText);
            $('html').append(res.responseText);
             $('.screen').slideUp();
        }
    });

});//End Submit button




});//End jQuery


/*
JSON DE ENVIO
{
"function":"LoadDeviceForm",
"data":{
  "DeviceName":"DeviceName",
  "DevName":"DevName",
  "Description":"Description",
  "Color":"Color",
  "DeviceIcon":"Icon",
  "Sensors":[]
}
}



*/
