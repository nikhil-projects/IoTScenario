#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción: 

Va anadiendo lo que se le pasa a una estructura del tipo JSON
———————————————
{
"SKey":XK3451,
"Stage":1,
"Device": 12,
"Sensor":{
"14":[SAMPLE VARIABLE]
},
 "Actuator":{
}
}
 json.dumps(list)
'''
import config,json

class JSONCoder(object):
	"""docstring for JSONCoder"""

	message = ''
	def __init__(self):
		super(JSONCoder, self).__init__()
		self.message ={
			'SKey':config.SERVER_KEY,
			'Sensor':{
		
			}
		}

	def addValue(self,signalName, value):
		self.message['Sensor'][signalName] = value

	def GetMessage(self):
		#if(config.DEBUG_MODE):
			#print '	..................\n \n JSONMessage: ' + json.dumps(self.message)
		return json.dumps(self.message)


