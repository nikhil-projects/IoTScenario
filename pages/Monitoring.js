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
	setInterval(ajaxExec, 2000); //Consultamos la base de datos cada segundo
	function ajaxExec(){        
		$.ajax({
            type: "POST",
        	url: directoryPath,
        	data: {message: mesGetSensValue},
            success : function (data) {
                //$(".main").html(data);
                plotSensorValues(data);
           		updateChart(data);
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


	//Actualiza los valores del gráfico (Se lanzan en el php de chartObj.php)
	function updateChart(dataX){
		var data=[];
		var data = JSON.parse(dataX);
		for (var i = data.length - 1; i >= 0; i--){
			chartdata.datasets[i].data.push(data[i].value);
			chartdata.datasets[i].data.shift();
		}//for
		var  myLineChart = new Chart(ctx , {
    		  type: "line",
    		  data: chartdata,
    		  options: {
    			animation: {
        			duration: 0
    			}
				} 
			});
	}//End function

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

