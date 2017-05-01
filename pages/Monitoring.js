/*
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
*/
$(document).ready(function(){
	
	var directoryPath = 'ComDB/Rx.php';
	var mesGetSensValue = getSensorMessage();
	

	//Se encarga de actualizar los valores de los displayers.
	setInterval(ajaxExec, 1000); //Consultamos la base de datos cada segundo
	function ajaxExec(){        
		$.ajax({
            type: "POST",
        	url: directoryPath,
        	data: {message: mesGetSensValue},
            success : function (data) {
                //$(".main").html(data);
                plotSensorValues(data);
            }
        });
	}//Fin AJAX exec

	//Obtenemos el identificador de cada sensor para confeccionar
	//un mensaje de petición al sensor
	function getSensorMessage(){
		var mes = {"data":[],"function":"GetLastSample"};
		$(".display").each(function() {
    			mes.data.push(this.id); 
		});//End each
		return mes;
	}//End get sensors ids


	//Recorre la recepción del servidor y coloca el valor de 
	//cada sensor en su espacio en el DOM.
	function plotSensorValues(dataX){
		//console.log("Llega; " + dataX);
		var data=[];
		var data = JSON.parse(dataX);
		
		for (var i = data.length - 1; i >= 0; i--) {
			
			var id = data[i].id;
    		var value = data[i].value;
    		//console.log("Id: " + id + "Value: " + value);
    		$('#SensorVal'+id).html(value);
		}//for
	}//End plot sensor

});//Fin jquery

