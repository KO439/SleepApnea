#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>

const char *ssid = "itel A17";  
const char *password = "20012001";

void setup() {
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    // Attendre la connexion
  }
}

void loop() {
  if (Serial.available() > 0) {
    // Lire les données depuis le port série
    String spo2 = Serial.readStringUntil('\n');
    String heartRate = Serial.readStringUntil('\n');
    String amplifiedECG = Serial.readStringUntil('\n');

    // Supprimer les espaces inutiles
    spo2.trim();
    heartRate.trim();
    amplifiedECG.trim();

    // Créer la chaîne de requête
    String data = "?spo2=" + spo2 + "&heartRate=" + heartRate + "&amplifiedECG=" + amplifiedECG;

    // Créer l'URL complète
    String link = "http://192.168.1.20:8080/telediagnostic/donnees_physiologiques.php" + data;

    // Effectuer la requête HTTP
    HTTPClient http;
    http.begin(link);
    int httpCode = http.GET(); // Envoyer la requête et obtenir le code de statut
    http.end(); // Terminer la requête

    delay(5000); // Attendre 5 secondes avant d'envoyer la prochaine donnée
  }
}
