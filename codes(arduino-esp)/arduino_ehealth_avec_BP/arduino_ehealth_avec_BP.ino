#include <Wire.h>
#include "MAX30105.h"
#include "spo2_algorithm.h"
#include <eHealth.h>
#include <SoftwareSerial.h>

SoftwareSerial mySerial(2, 3);
float amplifiedECG;
MAX30105 particleSensor;

#define MAX_BRIGHTNESS 255

uint16_t irBuffer[32];
uint16_t redBuffer[32];
int32_t bufferLength;
int32_t spo2;
int8_t validSPO2;
int32_t heartRate;
int8_t validHeartRate;

byte pulseLED = 11;
byte readLED = 13;
byte buzzer = 10; // Buzzer pin
byte redLED = 9;  // Red LED pin
byte greenLED = 8; // Green LED pin
byte yellowLED = 7; // Green LED pin
byte orangeLED = 6; // Green LED pin
byte buttonPin = 11; // Bouton poussoir pin
unsigned long lastZeroTime = 0;
const unsigned long apneaDuration = 10000;
const float R1 = 10000;
const float R2 = 10000;
const float C1 = 0.00001;

const float alphaQRS = 0.02;
float moyenneMobileQRS = 0.2;
float seuilQRS = 0;
bool qrsDetecte = false;
unsigned long derniereDetection = 0;
const unsigned long periodeDetection = 300;

unsigned long lastApneaTime = 0; // Variable pour stocker le dernier temps d'apnée
bool surveillanceDemarree = false; // Variable pour suivre l'état de démarrage de la surveillance

void setup() {
    Serial.begin(115200);
    mySerial.begin(115200); 
    pinMode(pulseLED, OUTPUT);
    pinMode(readLED, OUTPUT);
    pinMode(buzzer, OUTPUT); // Configuration du buzzer en sortie
    pinMode(redLED, OUTPUT); // Configuration de la LED rouge en sortie
    pinMode(greenLED, OUTPUT); // Configuration de la LED verte en sortie
    pinMode(yellowLED, OUTPUT); // Configuration de la LED rouge en sortie
    pinMode(orangeLED, OUTPUT); // Configuration de la LED verte en sortie
    noTone(buzzer);          // le buzzer est éteint au démarrage
    pinMode(buttonPin, INPUT_PULLUP); // Configuration du bouton poussoir en entrée avec résistance pull-up
    
    while (!particleSensor.begin()) {
        
    }
}

void loop() {
    if (!surveillanceDemarree && digitalRead(buttonPin) == HIGH) {
        surveillanceDemarree = true;
        const byte ledBrightness = 60;
        const byte sampleAverage = 4;
        const byte ledMode = 2;
        const byte sampleRate = 100;
        const int pulseWidth = 411;
        const int adcRange = 4096;

        particleSensor.setup(ledBrightness, sampleAverage, ledMode, sampleRate, pulseWidth, adcRange);
    }

    if (surveillanceDemarree) {
        int air = eHealth.getAirFlow();
        boolean apnea = false;

        mySerial.print("Flux d'air: ");
        mySerial.println(air);

        if (air <= 10) {
            unsigned long currentTime = millis();
            if (currentTime - lastZeroTime >= apneaDuration) {
                apnea = true;
                mySerial.println("crise d'apnee !");
                tone(buzzer, 1000); // Activer le buzzer avec une fréquence de 1000 Hz pour signaler l'apnée
                digitalWrite(redLED, HIGH); // Allumer la LED rouge
                digitalWrite(greenLED, LOW); // Éteindre la LED verte
                digitalWrite(yellowLED, LOW);
                digitalWrite(orangeLED, LOW);
                delay(500);
                noTone(buzzer); // Éteindre le buzzer après 1 seconde
            }
        } else {
            lastZeroTime = millis();
            digitalWrite(redLED, LOW); // Éteindre la LED rouge
            digitalWrite(greenLED, HIGH);
            digitalWrite(orangeLED, LOW);
            digitalWrite(yellowLED, LOW);// Allumer la LED verte
        }

        if (apnea) {
            float rawECG = eHealth.getECG();
            float filteredECG = rawECG * (R1 + R2) / R1;
            amplifiedECG = filteredECG * 1.5;
            detecterQRS(amplifiedECG);
            bufferLength = 32;

            for (byte i = 0; i < bufferLength; i++) {
                while (particleSensor.available() == false)
                    particleSensor.check();

                redBuffer[i] = particleSensor.getRed();
                irBuffer[i] = particleSensor.getIR();
                particleSensor.nextSample();
            }

            maxim_heart_rate_and_oxygen_saturation(irBuffer, bufferLength, redBuffer, &spo2, &validSPO2, &heartRate, &validHeartRate);

            mySerial.print(F("SPO2: "));
            mySerial.println(spo2);
            
            mySerial.print(F("HR: "));
            mySerial.println(heartRate);

            if (spo2 < 90 || heartRate < 24) {
                mySerial.println("spo2: Apnea!");
                tone(buzzer, 1500); // Activer le buzzer avec une fréquence de 1500 Hz pour signaler l'apnée détectée par le capteur spo2
                delay(500);
                digitalWrite(redLED, HIGH); // Allumer la LED rouge
                digitalWrite(greenLED, LOW); // Éteindre la LED verte
                digitalWrite(yellowLED, HIGH);
                digitalWrite(orangeLED, LOW);
                delay(500);
                noTone(buzzer); // Éteindre le buzzer après 1 seconde
            } else if (spo2 < 50) {
                mySerial.println("spo2:Hypopnea !");
                tone(buzzer, 1500); // Activer le buzzer avec une fréquence de 1500 Hz pour signaler l'apnée détectée par le capteur spo2
                 digitalWrite(redLED, HIGH); // Allumer la LED rouge
                digitalWrite(greenLED, LOW); // Éteindre la LED verte
                digitalWrite(orangeLED, HIGH);
                digitalWrite(yellowLED, LOW);
                delay(500);
            }

            mySerial.print("Valeur ECG  : ");
            mySerial.print(amplifiedECG, 2);
            mySerial.println(" V");
            sendSensorData();
        }
    }
    
    delay(1000);
}
void sendSensorData() {
    
   
  
    Serial.println(spo2);
    Serial.println(heartRate);
    Serial.println(amplifiedECG);


}

float filtrerECG(float rawECG) {
    float filteredECG = rawECG * (R1 + R2) / R1;
    return filteredECG;
}

float amplifierECG(float filteredECG) {
    float amplifiedECG = filteredECG * 1.5;
    return amplifiedECG;
}

void detecterQRS(float amplifiedECG) {
    moyenneMobileQRS = alphaQRS * amplifiedECG + (1 - alphaQRS) * moyenneMobileQRS;
    seuilQRS = moyenneMobileQRS * 0.8;

    if (amplifiedECG > seuilQRS && !qrsDetecte && (millis() - derniereDetection >= periodeDetection)) {
        qrsDetecte = true;
        derniereDetection = millis();
    } else if (amplifiedECG < seuilQRS) {
        qrsDetecte = false;
    }
}
