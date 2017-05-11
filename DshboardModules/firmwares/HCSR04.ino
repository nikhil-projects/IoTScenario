// Firmware de sensor de ultrasonidos HCSR04 para placa WiFI Thing Sparkfun

#include <ESP8266WiFi.h>

const char* ssid     = "";
const char* password = "";

const char* host = "192.168.1.39";
String dato = "";
int trigPin = 15;
int echoPin = 14;
int signalID = 17; 
 
WiFiClient client;

void setup()
{
  Serial.begin(115200);
 
  //Sensor de ultra sonidos:
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  
  Serial.printf("Conectando a %s ", ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println(" Conectado :)");
  
}

void loop()
{
  Tx();
}
//Genera un valor aleatorio entre 0 y 200
String genRandomData(){
  return String(random(200));
}

String readHCSR04(){
  long duration, distance;
  digitalWrite(trigPin, LOW);  
  delayMicroseconds(2); 
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(1000);  
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);
  distance = (duration/2) / 29.1;
  Serial.print(distance);
  Serial.println(" cm");
  return String(distance);
}

void Tx(){
  Serial.printf("\n[Conectando a %s ... ", host);
  
  if (client.connect(host, 80))
  {
    Serial.println("Conectado :)]");
    
    Serial.println("[Transmitiendo petición]");
   
    dato = "{\"Sensor\": {\""+signalID+"\":"+readHCSR04()+"}, \"SKey\": \"XK3451\"}";
    String longitud = String(dato.length()); 
    String msg = String("POST /IoTServi1/ComDB/Rx.php") + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Content-Type: application/json\r\n"+
                 "Content-Length: " +  longitud + "\r\n" + "\r\n" +             
                 dato + "\r\n";
    Serial.println(msg); 
    client.print(msg);
  }
  else
  {
    Serial.println("Conexión fallida]");
    client.stop();
  }
  delay(1000);
}
