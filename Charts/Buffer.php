<?php 
/*
—————————————— 
Autor: Álvaro Peris Zaragozá 
Ano: 2017
Descripción: 
Clase que actúa como buffer de la información que le llega.
Es una extensión de la clase SplQueue. Los métodos de 
esta clase se pueden encontrar en:

SplQueue |-> http://php.net/manual/es/class.splqueue.php

//Ejemmplo de uso:
$cola = new SBuffer(5);

$cola->addElement(1.1);
$cola->addElement(2.1);
$cola->addElement(5.1);
$cola->addElement(11.1);
$cola->addElement(20.1);
$cola->addElement(20.12);
$cola->addElement(50.1);

$cola -> showElements();


print $cola->GetAsMatVar('Sensor1'); 
———————————————
*/

class SBuffer extends SplQueue{
	//Tamaño del buffer. Si el tamaño es 0 actua como buffer "infinito"
	var $buffer_size; 

	//Constructor de clase
	function __construct($buffer_size){
		$this->buffer_size = $buffer_size;
	}

	//Añade un elemento a la cola teniendo en cuenta el tamaño de buffer
	function addElement($element){
		//Si el tamaño de buffer es 0 este será "infinito"
		if($this->count() != 0){
			// Miramos si se ha alcanzado el tamaño del buffer
			if($this->count() >= $this->buffer_size){
				//Eliminamos el elemento más antiguo
				$this->dequeue();}
		}
		//Añadimos el elemento nuevo
		$this->push($element);
	}

	//Elimina todos los valores del buffer
	function flushBuffer(){
		while ( $this->count() != 0) {
				$this->pop();}	
	}

	//Muestra los elementos que hay en la cola ->paraDEBUG
	function showElements(){
		$this->setIteratorMode(SplQueue::IT_MODE_KEEP);
		echo '<ul>'; 
		foreach ($this as $valor) {
			echo '<li>'. $valor . '</li>';}
		echo '</ul>';}

	
	//Retorna la información contenida en el buffer en formato array
	function GetArray(){
		$arr = array();
		$i = 0; 
		$this->setIteratorMode(SplQueue::IT_MODE_KEEP);
		foreach ($this as $valor) {
			$arr[$i] = floatval($valor);
			$i++;
		}
		return $arr;
	}

	//Permite que se pueda añadir un array al buffer
	function AddArray($arr){
		foreach ($arr as $value){
			$this->addElement($value);		
		}//foreach
	}

	//Retorna el valor del primer elemento (El LAST INPUT)
	function GetTop(){ return $this -> top();}

}

?>



