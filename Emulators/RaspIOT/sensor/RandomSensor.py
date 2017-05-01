#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''
—————————————— 
Autor: Alvaro Peris Zaragoza 
Ano: 2017
Descripción:

Simula un sensor, genera valores aleatorios. Se emplea para testear el correcto funcionamiento de
la plataforma IoTScenario 

———————————————
'''
import random
class RandomSensor(object):
	"""docstring for RandomSensor"""
	id = 0
	def __init__(self, id):
		super(RandomSensor, self).__init__()
		self.id = id

	def genValue(self):
		return random.uniform(1, 10)
		