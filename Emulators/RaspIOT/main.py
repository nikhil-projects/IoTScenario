#!/usr/bin/env python
# -*- coding: utf-8 -*

'''
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: Emulador de firmware de un dispositivo para IoTScenario

Cliente Python
———————————————
'''
import sys
sys.path.append('core/')
sys.path.append('sensor/')

import TXBlock, JSONCoder
import time
import config #Variables de conexion IP, puerto ...
import RandomSensor


#Solo es un feedback para el usuario para ver que el cliente
# esta operando
def printConf():
	print'\n\n'
	print('\033[92m' + config.DEVICE_NAME + "  Client running ...")
	print('Server Target: ' + config.SERVER_IP + ' : '+ str(config.SERVER_PORT) + ' ' + config.SERVER_PATH)
	print('\033[0m')

#--------- DECLARACIONES setup-------
printConf()

#Anadir aqui otros sensores
#Instanciamos los sensores 
sensorTmp = RandomSensor.RandomSensor(config.SIGNAL_ID);

#Instanciamos el codificador
coder = JSONCoder.JSONCoder()

# Instanciamos el transmisor.
path = config.SERVER_PATH #Ruta de la plataforma
clave = config.SERVER_KEY #Autorizacion del dispositivo para acceder a la plataforma
Tx = TXBlock.Comunicacion(config.SERVER_IP,config.SERVER_PORT,path,clave)


#--------- PROGRAMA loop -------

# Creamos un bucle que ejecutara indefinidamente el programa
while True:
	#Obtenemos y codificamos en JSON el valor de los sensores
	m1 = sensorTmp.genValue()
	coder.addValue(sensorTmp.id, m1)


	#Obtenemos el mensaje a enviar
	msg = coder.GetMessage()

	# Enviamos la informacion al servidor
	Tx.enviar(msg)

	time.sleep(config.TIME_DELAY) #Esperamos un poco para repetir el proceso

