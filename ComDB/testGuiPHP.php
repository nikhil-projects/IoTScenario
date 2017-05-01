<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

$(document).ready(function(){

	$('html').on('click', '.genMessage', function(event) {
    event.preventDefault();
    var id = $(this).attr('id');
    var directoryPath = 'GuiPHPManager.php';
    
    //We get the value of JSON file calling JSONgenerator of PHP. Where 
    // objectt is the instance of device that we want to
    //obtain the value and function is the function 
    // that we want to exec in this case showJSON.
    id = 12;
    var mes = '{"object":"'+id+'","function":"showJSON"}';


    $.post({
   		url: 'GuiPHPManager.php',
   		data: {message: mes},
   		complete: function(res) {
   			console.log(res.responseText);
   			$('.msg').append('<br>'+ res.responseText);
 		}
 	});
   
    
});


	});
</script>


<style>
	.genMessage{
		width: 100px;
		height: 100px;
		background: blue;
	}
</style>

<div class="genMessage">Enviar datos</div>
<div class="msg"></div>