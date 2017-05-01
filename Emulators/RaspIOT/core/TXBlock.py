#!/usr/bin/env python
# -*- coding: utf-8 -*

'''
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 
———————————————
'''

import socket, sys
import config

# El modo DEBUG printa las operaciones que esta realizando el
# cliente
DEBUG = False

class Comunicacion(object):	
	IPServidor = '' 
	Puerto = 0 
	direccion = ''

	#Constructor de la clase
	def __init__(self, ip, port, dir,clave):
		super(Comunicacion, self).__init__()
		self.IPServidor = ip
		self.Puerto = port
		self.direccion = dir

	# Funcion publica que se emplea para ejecutar el envio
	# se le pasa el parámetro que se desea transmitir como
	# dato.
	def enviar(self,dato):
		mns = self.mensajePOST(dato)
		self.conexion(mns)

	#Compone el mensaje a enviar en HTTP
	def mensajePOST(self,dato):
		l1 = "POST "+ self.direccion +" HTTP/1.1\r\n"
		l2 = "Host: " +  self.IPServidor + "\r\n"
		l3 = "Content-Type: application/json\r\n"
		l4 = "Content-Length: " +  str(len(str(dato))) + "\r\n"
		l5 = "\r\n"
		l6 = str(dato) + "\r\n"

		return l1+l2+l3+l4+l5+l6

	#Cramos una conexión a bajo nivel para enviar el mensaje
	def conexion(self,transmision):
		# Creamos un socket TCP/IP
		sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

		# Conectamos con el servidor
		if DEBUG:
			print("COnectando al servidor ...")
		dir_servidor = (self.IPServidor, self.Puerto)
		sock.connect(dir_servidor)

		if DEBUG:
			print("Enviando información ...")
		# Enviamos informacion
		sock.sendall(transmision)

		#Cerramos el socket
		sock.close()
		if DEBUG:
			print("Conexión cerrada ...")


