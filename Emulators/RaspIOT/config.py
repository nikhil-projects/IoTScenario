#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
Constantes del dispositivo
———————————————
'''

#Nombre del dispositivo
#------------------------
DEVICE_NAME = 'MiDispositivo'
DEVICE_ID = '12345'
SERVER_KEY = 'XK3451'


SIGNAL_ID = 14

#Red
#------------------------
#Path donde se ejecuta el interprete de informacion
SERVER_PATH = '/IoTServi1/ComDB/Rx.php'

#IP del servidor y puerto
SERVER_IP = '192.168.1.131'
SERVER_PORT = 80

#Tiempo de envio.Tiempo que el cliente espera antes de realizar 
# un nuevo envio medido en SEGUNDOS
TIME_DELAY = 1.5

#Debug del cliente
DEBUG_MODE = True