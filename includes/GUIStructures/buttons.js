
$(document).ready(function(){

 $('.display').click(function(event) {
 	var color = $(this).children(".top").css("background");
 	$(this).children(".doptions").css({
 		background: color
 	});
 	$(this).children(".doptions").slideToggle("fast");

 });

 $('.doptions li').click(function(event) {
 	var ide = $(this).attr('id');
 	ide = ide.slice(3);
 	var req = $(this).attr('class');

 	switch (req) {
 		case 'Matlab':
 			var mes = {"data":ide,"function":"GenMatlab"};
 			ajaxExec(mes);
            var win = window.open('ComDB/IoTScenario.m', '_blank');
            //Abrimos un tab para descargar el reporte
            OpenTab()
 			break;
 		case 'Report':
 			var mes = {"data":ide,"function":"GenReport"};
 			ajaxExec(mes);
            var win = window.open('ComDB/IoTScenarioReport.pdf', '_blank');
            //Abrimos un tab para descargar el reporte
            OpenTab()
 			break;
 		case 'Delete':
 			var mes = {"data":ide,"function":"DeleteSamples"};
 			ajaxExec(mes);
 			break;
 		default:
 			alert("Bad value");
 			break;
 	}
 	

 	//alert("Función:" + req + " id: " + ide);
 });

function OpenTab(win){
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
            } else {
            //Browser has blocked it
            alert('Please allow popups for get the report');
        }    
}

 var directoryPath = 'ComDB/Reports.php';
//Se encarga de actualizar los valores de los displayers.
	function ajaxExec(mess){        
		$.ajax({
            type: "POST",
        	url: directoryPath,
        	data: {message: mess},
            success : function (data) {
            	console.log('Llega: '+data);
            }
        });
	}//Fin AJAX exec

	function OpenNewTab(){
		var win = window.open('http://stackoverflow.com/', '_blank');
		if (win) {
    	//Browser has allowed it to be opened
    		win.focus();
		} else {
    		//Browser has blocked it
    		alert('Please allow popups for this website');
		}
	}//End OpenNewTab

});//End jQuery