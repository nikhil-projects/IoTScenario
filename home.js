/*
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
*/
$(document).ready(function(){

function simpleParallax() {
    //This variable is storing the distance scrolled
    var scrolled = $(window).scrollTop() + 1;

    //Every element with the class "scroll" will have parallax background 
    //Change the "0.3" for adjusting scroll speed.
    $('#escenarios').css('background-position', '0' + -(scrolled * 0.3) + 'px');
}
//Everytime we scroll, it will fire the function
$(window).scroll(function (e) {
    simpleParallax();
});

$(window).scroll(function (e) {

	 var ofset = $(this).scrollTop();
	 var mainHei = $('.main').height();

	 console.log('Ofset:' + ofset);
	 console.log('Height:' + mainHei);

    if(ofset>mainHei){
    	$('.navbar').css({
    		background: 'var(--defaultColor)'
    	});//Css
    }else if (ofset == 0) {
    	$('.navbar').css({
    		background: 'rgba(0,0,0,0)'
    	});//Css
    }//if
});//Windowscroll




});