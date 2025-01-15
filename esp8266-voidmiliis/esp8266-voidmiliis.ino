#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>  // Libary untuk fungsi HTTP Client
#include <ArduinoJson.h>  // Library untuk parsing data JSON

const char* ssid = "R_Penelitian";  // SSID WiFi 
const char* password = "rumahpen123";  // Password WiFi 

//SpreedSheet
//String DATA_ID = "AKfycbx3luDYUXUtiJsghRL9yIN4nT22L_VfWzWOay2R4af82ui87pgUd6bmMtOboND2U7wCDA";
//const char* host = "script.google.com";

// Dekklarasi tipe data variabel
String suhu, tekanan, altitude, kelembaban, arusDinamis, arusStatis, teganganDinamis, teganganStatis, powerDinamis, powerStatis;
String jumlah_tip, curah_hujan_hari_ini, curah_hujan_per_menit, curah_hujan_per_jam, curah_hujan_per_hari, cuaca;
String lux, kondisiRaindrop;
String arah_angin, rps, velocity_ms, velocity_kmh;
String ldrMAA, ldrMAA7, ldrMAA6, ldrMBB, ldrMBA5, ldrMBA4; 
String ldrValueSelatan, ldrValueUtara, ldrValueTimur, ldrValueBarat;
String jam, menit, detik, nama_hari, hari, bulan, tahun;
String current_suhu, level_suhu, trend_suhu, forecast_suhu, 
       current_tekanan, level_tekanan, trend_tekanan, forecast_tekanan, 
       current_kelembaban,level_kelembaban, trend_kelembaban, forecast_kelembaban, 
       current_cahaya, level_cahaya, trend_cahaya, forecast_cahaya, 
       current_angin, level_angin, trend_angin, forecast_angin;

unsigned long weatherMillis = 0; 
const long weatherInterval = 60000; 

unsigned long powerMillis = 0; 
const long powerInterval = 60000; 

unsigned long tippingbucketMillis = 0; 
const long tippingbucketInterval = 3000; 

unsigned long windvaneMillis = 0; 
const long windvaneInterval = 3000; 

unsigned long motorMillis = 0; 
const long motorInterval = 60000; 

unsigned long forecastMillis = 0; 
const long forecastInterval = 60000; 

//unsigned long activePeriod = 5 * 60 * 1000; // 5 menit dalam milidetik

void setup() {
  Serial.begin(9600);
  wifiConnection(); // Memanggil fungsi wifiConnection() 
}

// Fungsi untuk menghubungkan esp8266 ke jaringan WiFi
void wifiConnection() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  Serial.println("");

  int attempts = 0; 
  Serial.print("Menghubungkan ke ");
  Serial.println(ssid);
  while (WiFi.status() != WL_CONNECTED && attempts < 20) { // Jika belum terhubung, maka... hingga 20
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  Serial.println("");
  Serial.print("Connected.");
  Serial.print("AP/Station: ");
  Serial.println(ssid);
  Serial.print("Local IP Address: ");
  Serial.println(WiFi.localIP()); // Jika terhubung maka tampilkan IP Address lokal untuk esp8266
  delay(2000);
}

void parseSerialData(String data) {
    int index1 = data.indexOf('#');
    if (index1 != -1) {
        suhu = data.substring(0, index1);

        int index2 = data.indexOf('#', index1 + 1);
        if (index2 != -1) {
            altitude = data.substring(index1 + 1, index2);

            int index3 = data.indexOf('#', index2 + 1);
            if (index3 != -1) {
                tekanan = data.substring(index2 + 1, index3);

                int index4 = data.indexOf('#', index3 + 1);
                if (index4 != -1) {
                    kelembaban = data.substring(index3 + 1, index4);

                    int index5 = data.indexOf('#', index4 + 1);
                    if (index5 != -1) {
                        teganganDinamis = data.substring(index4 + 1, index5);

                        int index6 = data.indexOf('#', index5 + 1);
                        if (index6 != -1) {
                            teganganStatis = data.substring(index5 + 1, index6);

                            int index7 = data.indexOf('#', index6 + 1);
                            if (index7 != -1) {
                                arusDinamis = data.substring(index6 + 1, index7);

                                int index8 = data.indexOf('#', index7 + 1);
                                if (index8 != -1) {
                                    arusStatis = data.substring(index7 + 1, index8);

                                    int index9 = data.indexOf('#', index8 + 1);
                                    if (index9 != -1) {
                                        powerDinamis = data.substring(index8 + 1, index9);

                                        int index10 = data.indexOf('#', index9 + 1);
                                        if (index10 != -1) {
                                            powerStatis = data.substring(index9 + 1, index10);

                                            int index11 = data.indexOf('#', index10 + 1);
                                            if (index11 != -1) {
                                                jumlah_tip = data.substring(index10 + 1, index11);

                                                int index12 = data.indexOf('#', index11 + 1);
                                                if (index12 != -1) {
                                                    curah_hujan_hari_ini = data.substring(index11 + 1, index12);

                                                    int index13 = data.indexOf('#', index12 + 1);
                                                    if (index13 != -1) {
                                                        curah_hujan_per_menit = data.substring(index12 + 1, index13);

                                                        int index14 = data.indexOf('#', index13 + 1);
                                                        if (index14 != -1) {
                                                            curah_hujan_per_jam = data.substring(index13 + 1, index14);

                                                            int index15 = data.indexOf('#', index14 + 1);
                                                            if (index15 != -1) {
                                                                curah_hujan_per_hari = data.substring(index14 + 1, index15);

                                                                int index16 = data.indexOf('#', index15 + 1);
                                                                if (index16 != -1) {
                                                                    cuaca = data.substring(index15 + 1, index16);

                                                                    int index17 = data.indexOf('#', index16 + 1);
                                                                    if (index17 != -1) {
                                                                        lux = data.substring(index16 + 1, index17);

                                                                        int index18 = data.indexOf('#', index17 + 1);
                                                                        if (index18 != -1) {
                                                                            kondisiRaindrop = data.substring(index17 + 1, index18);

                                                                            int index19 = data.indexOf('#', index18 + 1);
                                                                            if (index19 != -1) {
                                                                                arah_angin = data.substring(index18 + 1, index19);

                                                                                int index20 = data.indexOf('#', index19 + 1);
                                                                                if (index20 != -1) {
                                                                                    rps = data.substring(index19 + 1, index20);

                                                                                    int index21 = data.indexOf('#', index20 + 1);
                                                                                    if (index21 != -1) {
                                                                                        velocity_ms = data.substring(index20 + 1, index21);

                                                                                        int index22 = data.indexOf('#', index21 + 1);
                                                                                        if (index22 != -1) {
                                                                                            velocity_kmh = data.substring(index21 + 1, index22);

                                                                                            int index23 = data.indexOf('#', index22 + 1);
                                                                                            if (index23 != -1) {
                                                                                                ldrMAA = data.substring(index22 + 1, index23);

                                                                                                int index24 = data.indexOf('#', index23 + 1);
                                                                                                if (index24 != -1) {
                                                                                                    ldrMAA7 = data.substring(index23 + 1, index24);

                                                                                                    int index25 = data.indexOf('#', index24 + 1);
                                                                                                    if (index25 != -1) {
                                                                                                        ldrMAA6 = data.substring(index24 + 1, index25);

                                                                                                        int index26 = data.indexOf('#', index25 + 1);
                                                                                                        if (index26 != -1) {
                                                                                                            ldrMBB = data.substring(index25 + 1, index26);

                                                                                                            int index27 = data.indexOf('#', index26 + 1);
                                                                                                            if (index27 != -1) {
                                                                                                                ldrMBA5 = data.substring(index26 + 1, index27);

                                                                                                                int index28 = data.indexOf('#', index27 + 1);
                                                                                                                if (index28 != -1) {
                                                                                                                    ldrMBA4 = data.substring(index27 + 1, index28);

                                                                                                                    int index29 = data.indexOf('#', index28 + 1);
                                                                                                                    if (index29 != -1) {
                                                                                                                        ldrValueSelatan = data.substring(index28 + 1, index29);

                                                                                                                        int index30 = data.indexOf('#', index29 + 1);
                                                                                                                        if (index30 != -1) {
                                                                                                                            ldrValueUtara = data.substring(index29 + 1, index30);

                                                                                                                            int index31 = data.indexOf('#', index30 + 1);
                                                                                                                            if (index31 != -1) {
                                                                                                                                ldrValueTimur = data.substring(index30 + 1, index31);

                                                                                                                                int index32 = data.indexOf('#', index31 + 1);
                                                                                                                                if (index32 != -1) {
                                                                                                                                    ldrValueBarat = data.substring(index31 + 1, index32);

                                                                                                                                    int index33 = data.indexOf('#', index32 + 1);
                                                                                                                                    if (index33 != -1) {
                                                                                                                                        jam = data.substring(index32 + 1, index33);

                                                                                                                                        int index34 = data.indexOf('#', index33 + 1);
                                                                                                                                        if (index34 != -1) {
                                                                                                                                            menit = data.substring(index33 + 1, index34);

                                                                                                                                            int index35 = data.indexOf('#', index34 + 1);
                                                                                                                                            if (index35 != -1) {
                                                                                                                                                detik = data.substring(index34 + 1, index35);

                                                                                                                                                int index36 = data.indexOf('#', index35 + 1);
                                                                                                                                                if (index36 != -1) {
                                                                                                                                                    nama_hari = data.substring(index35 + 1, index36);

                                                                                                                                                    int index37 = data.indexOf('#', index36 + 1);
                                                                                                                                                    if (index37 != -1) {
                                                                                                                                                        hari = data.substring(index36 + 1, index37);

                                                                                                                                                        int index38 = data.indexOf('#', index37 + 1);
                                                                                                                                                        if (index38 != -1) {
                                                                                                                                                            bulan = data.substring(index37 + 1, index38);

                                                                                                                                                            int index39 = data.indexOf('#', index38 + 1);
                                                                                                                                                            if (index39 != -1) {
                                                                                                                                                                tahun = data.substring(index38 + 1, index39);

                                                                                                                                                                int index40 = data.indexOf('#', index39 + 1);
                                                                                                                                                                if (index40 != -1) {
                                                                                                                                                                    current_suhu = data.substring(index39 + 1, index40);

                                                                                                                                                                    int index41 = data.indexOf('#', index40 + 1);
                                                                                                                                                                    if (index41 != -1) {
                                                                                                                                                                        level_suhu = data.substring(index40 + 1, index41);

                                                                                                                                                                        int index42 = data.indexOf('#', index41 + 1);
                                                                                                                                                                        if (index42 != -1) {
                                                                                                                                                                            trend_suhu = data.substring(index41 + 1, index42);

                                                                                                                                                                            int index43 = data.indexOf('#', index42 + 1);
                                                                                                                                                                            if (index43 != -1) {
                                                                                                                                                                                forecast_suhu = data.substring(index42 + 1, index43);

                                                                                                                                                                                int index44 = data.indexOf('#', index43 + 1);
                                                                                                                                                                                if (index44 != -1) {
                                                                                                                                                                                    current_tekanan = data.substring(index43 + 1, index44);

                                                                                                                                                                                    int index45 = data.indexOf('#', index44 + 1);
                                                                                                                                                                                    if (index45 != -1) {
                                                                                                                                                                                        level_tekanan = data.substring(index44 + 1, index45);

                                                                                                                                                                                        int index46 = data.indexOf('#', index45 + 1);
                                                                                                                                                                                        if (index46 != -1) {
                                                                                                                                                                                            trend_tekanan = data.substring(index45 + 1, index46);

                                                                                                                                                                                            int index47 = data.indexOf('#', index46 + 1);
                                                                                                                                                                                            if (index47 != -1) {
                                                                                                                                                                                                forecast_tekanan = data.substring(index46 + 1, index47);

                                                                                                                                                                                                int index48 = data.indexOf('#', index47 + 1);
                                                                                                                                                                                                if (index48 != -1) {
                                                                                                                                                                                                    current_kelembaban = data.substring(index47 + 1, index48);

                                                                                                                                                                                                    int index49 = data.indexOf('#', index48 + 1);
                                                                                                                                                                                                    if (index49 != -1) {
                                                                                                                                                                                                        level_kelembaban = data.substring(index48 + 1, index49);

                                                                                                                                                                                                        int index50 = data.indexOf('#', index49 + 1);
                                                                                                                                                                                                        if (index50 != -1) {
                                                                                                                                                                                                            trend_kelembaban = data.substring(index49 + 1, index50);

                                                                                                                                                                                                            int index51 = data.indexOf('#', index50 + 1);
                                                                                                                                                                                                            if (index51 != -1) {
                                                                                                                                                                                                                forecast_kelembaban = data.substring(index50 + 1, index51);

                                                                                                                                                                                                                int index52 = data.indexOf('#', index51 + 1);
                                                                                                                                                                                                                if (index52 != -1) {
                                                                                                                                                                                                                    current_cahaya = data.substring(index51 + 1, index52);

                                                                                                                                                                                                                    int index53 = data.indexOf('#', index52 + 1);
                                                                                                                                                                                                                    if (index53 != -1) {
                                                                                                                                                                                                                        level_cahaya = data.substring(index52 + 1, index53);

                                                                                                                                                                                                                        int index54 = data.indexOf('#', index53 + 1);
                                                                                                                                                                                                                        if (index54 != -1) {
                                                                                                                                                                                                                            trend_cahaya = data.substring(index53 + 1, index54);

                                                                                                                                                                                                                            int index55 = data.indexOf('#', index54 + 1);
                                                                                                                                                                                                                            if (index55 != -1) {
                                                                                                                                                                                                                                forecast_cahaya = data.substring(index54 + 1, index55);

                                                                                                                                                                                                                                int index56 = data.indexOf('#', index55 + 1);
                                                                                                                                                                                                                                if (index56 != -1) {
                                                                                                                                                                                                                                    current_angin = data.substring(index55 + 1, index56);

                                                                                                                                                                                                                                    int index57 = data.indexOf('#', index56 + 1);
                                                                                                                                                                                                                                    if (index57 != -1) {
                                                                                                                                                                                                                                        level_angin = data.substring(index56 + 1, index57);

                                                                                                                                                                                                                                        int index58 = data.indexOf('#', index57 + 1);
                                                                                                                                                                                                                                        if (index58 != -1) {
                                                                                                                                                                                                                                            trend_angin = data.substring(index57 + 1, index58);
                                                                                                                                                                                                                                            
                                                                                                                                                                                                                                            forecast_angin = data.substring(index58 + 1);
                                                                                                                                                                                                                                            
                                                                                                                                                                                                                                            // All variables should be parsed now
                                                                                                                                                                                                                                            Serial.println("Data parsed successfully");
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                }
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                        }
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                }
                                                                                                                                                                                                            }
                                                                                                                                                                                                        }
                                                                                                                                                                                                    }
                                                                                                                                                                                                }
                                                                                                                                                                                            }
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                        }
                                                                                                                                                                    }
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

//void update_google_sheet()
//{
//    Serial.print("connecting to ");
//    Serial.println(host);
//  
//    // Use WiFiClient class to create TCP connections
//    WiFiClientSecure client;
//    const int httpPort = 443; // 80 is for HTTP / 443 is for HTTPS!
//    
//    client.setInsecure(); // this is the magical line that makes everything work
//    
//    if (!client.connect(host, httpPort)) { //works!
//      Serial.println("connection failed");
//      return;
//    }
//       
//    //----------------------------------------Processing data and sending data
//    String url = "/macros/s/" + DATA_ID + "/exec?temperature=";
//   
//    url += String(Temperature);   
//    url += "&altitude=";
//    url += String(altitude);
//    url += "&pressure=";
//    url += String(pressure);
//    url += "&humidity=";
//    url += String(Humidity);
//    url += "&lux=";
//    url += String(lux);
//    url += "&raindrop=";
//    url += String(rainDrop);    
//
//    Serial.print("Requesting URL: ");
//    Serial.println(url);
//  
//    // This will send the request to the server
//    client.print(String("GET ") + url + " HTTP/1.1\r\n" +
//                 "Host: " + host + "\r\n" + 
//                 "Connection: close\r\n\r\n");
//  
//    Serial.println();
//    Serial.println("closing connection");  
//}

void sendData(){
    WiFiClient client1; // WiFi Client untuk client 1
    WiFiClient client2; // WiFi Client untuk client 2
    WiFiClient client3; // WiFi Client untuk client 3
    WiFiClient client4; // WiFi Client untuk client 4
    WiFiClient client5; // WiFi Client untuk client 5
    WiFiClient client6; // WiFi Client untuk client 6
    
    HTTPClient http1; // HTTP Client untuk client 1
    HTTPClient http2; // HTTP Client untuk client 2
    HTTPClient http3; // HTTP Client untuk client 3
    HTTPClient http4; // HTTP Client untuk client 4
    HTTPClient http5; // HTTP Client untuk client 5
    HTTPClient http6; // HTTP Client untuk client 6

    StaticJsonDocument<200> weatherDoc;     // Data JSON untuk sensor bme280
    StaticJsonDocument<200> powerDoc;       // Data JSON untuk sensor tegangan dan arus
    StaticJsonDocument<200> tipbucketDoc;   // Data JSON untuk sensor TipBucket
    StaticJsonDocument<200> windDoc;        // Data JSON untuk sensor Angin
    StaticJsonDocument<200> motordriverDoc; // Data JSON untuk motor driver
    StaticJsonDocument<200> forecastDoc;    // Data JSON untuk forecast
    
    String weatherUrl, powerUrl, tipbucketUrl, windUrl, motordriverUrl, forecastUrl;
   
    unsigned long weatherCurrent = millis();
    if (weatherCurrent - weatherMillis >= weatherInterval) {
      weatherMillis = weatherCurrent;
    
      // Pemberian nilai parameter JSON untuk =====SENSOR BME280=====
      weatherDoc["suhu"] = suhu;
      weatherDoc["altitude"] = altitude;
      weatherDoc["tekanan"] = tekanan;
      weatherDoc["kelembaban"] = kelembaban;
      weatherDoc["lux"] = lux;
      weatherDoc["raindrop"] = kondisiRaindrop;
      weatherDoc["realtime"] = nama_hari + ", " + hari + "-" + bulan + "-" + tahun + " " + jam + ":" + menit + ":" + detik;
  
      weatherUrl = "http://rptugas.site/api/create.php";  // url untuk menembakkan data ke database melalui program php
      
      http1.begin(client1, weatherUrl);                   // Memulai fungsi HTTP Client 1
      http1.addHeader("Content-Type", "application/json");
  
      String jsonPayload1;                                // Deklarasi variabel untuk menyimpan payload/data json
      serializeJson(weatherDoc, jsonPayload1);
        
      // Menampilkan JSON ke Serial Monitor
      Serial.println("Data JSON 1 yang dikirim:");
      Serial.println(jsonPayload1);    

      // Pengiriman data ke database menggunakan metode POST
      int httpCode1 = http1.POST(jsonPayload1); 
  
      // Mendapatkan respon dari server
      if (httpCode1 > 0) {
        String payload1 = http1.getString();
        payload1.trim();
        if (payload1.length() > 0) {
          Serial.print("");
          Serial.println(payload1);
        }
      } else {
        Serial.print("HTTP Error Code: ");
        Serial.println(httpCode1);
      }
      http1.end();  // Mengakhiri service http client
      delay(60000);
    }

    unsigned long powerCurrent = millis();
    if (powerCurrent - powerMillis >= powerInterval) {
      powerMillis = powerCurrent;
      
      // Pemberian nilai parameter JSON untuk =====SENSOR TEGANGGAN(VOLT)dan ARUS(AMPER)=====
      powerDoc["tegangan_dinamis"] = teganganDinamis;
      powerDoc["tegangan_statis"] = teganganStatis;
      powerDoc["arus_dinamis"] = arusDinamis;
      powerDoc["arus_statis"] = arusStatis;
      powerDoc["power_dinamis"] = powerDinamis;
      powerDoc["power_statis"] = powerStatis;
      powerDoc["realtime"] = nama_hari + ", " + hari + "-" + bulan + "-" + tahun + " " + jam + ":" + menit + ":" + detik;
  
      powerUrl = "http://rptugas.site/api/create2.php";  // url untuk menembakkan data ke database melalui program php
  
      http2.begin(client2, powerUrl); // Memulai fungsi HTTP Client 2
      http2.addHeader("Content-Type", "application/json");
  
      String jsonPayload2;  // Deklarasi variabel untuk menyimpan payload/data json
      serializeJson(powerDoc, jsonPayload2);

      // Menampilkan JSON ke Serial Monitor
      Serial.println("Data JSON 2 yang dikirim:");
      Serial.println(jsonPayload2);
        
      // Pengiriman data ke database menggunakan metode POST
      int httpCode2 = http2.POST(jsonPayload2);
  
      // Mendapatkan respon dari server
      if (httpCode2 > 0) {
        String payload2 = http2.getString();
        payload2.trim();
        if (payload2.length() > 0) {
          Serial.print("");
          Serial.println(payload2);
        }
      } else {
        Serial.print("HTTP Error Code: ");
        Serial.println(httpCode2);
      }
      http2.end();  // Mengakhiri service http client
      delay(60000);
    }

    unsigned long tippingbucketCurrent = millis();
    if (tippingbucketCurrent - tippingbucketMillis >= tippingbucketInterval) {
      tippingbucketMillis = tippingbucketCurrent;
      
      // Pemberian nilai parameter JSON untuk =====SENSOR TIP BUCKET=====
      tipbucketDoc["jumlah_tip"] = jumlah_tip;
      tipbucketDoc["curah_hujan_hari_ini"] = curah_hujan_hari_ini;
      tipbucketDoc["curah_hujan_per_menit"] = curah_hujan_per_menit;
      tipbucketDoc["curah_hujan_per_jam"] = curah_hujan_per_jam;
      tipbucketDoc["curah_hujan_per_hari"] = curah_hujan_per_hari;
      tipbucketDoc["cuaca"] = cuaca;
      tipbucketDoc["realtime"] = nama_hari + ", " + hari + "-" + bulan + "-" + tahun + " " + jam + ":" + menit + ":" + detik;
  
      tipbucketUrl = "http://rptugas.site/api/create3.php";  // url untuk menembakkan data ke database melalui program php
  
      http3.begin(client3, tipbucketUrl); // Memulai fungsi HTTP Client 3
      http3.addHeader("Content-Type", "application/json");
  
      String jsonPayload3;  // Deklarasi variabel untuk menyimpan payload/data json
      serializeJson(tipbucketDoc, jsonPayload3);

      // Menampilkan JSON ke Serial Monitor
      Serial.println("Data JSON 3 yang dikirim:");
      Serial.println(jsonPayload3);
  
      // Pengiriman data ke database menggunakan metode POST
      int httpCode3 = http3.POST(jsonPayload3);
  
      // Mendapatkan respon dari server
      if (httpCode3 > 0) {
        String payload3 = http3.getString();
        payload3.trim();
        if (payload3.length() > 0) {
          Serial.print("");
          Serial.println(payload3);
        }
      } else {
        Serial.print("HTTP Error Code: ");
        Serial.println(httpCode3);
      }
      http3.end();  // Mengakhiri service http client
      delay(3000);
    }
    
    unsigned long windvaneCurrent = millis();
    if (windvaneCurrent - windvaneMillis >= windvaneInterval) {
      windvaneMillis = windvaneCurrent;

//update_google_sheet(DATA_ID, "rps", rps);
//update_google_sheet(DATA_ID, "velocity_kmh", velocity_kmh);

      // Pemberian nilai parameter JSON untuk =====SENSOR ANEMOMETER(KECEPATAN ANGIN) & ARAH MATA ANGIN=====
      windDoc["arah_angin"] = arah_angin;
      windDoc["rps"] = rps;
      windDoc["velocity_ms"] = velocity_ms;
      windDoc["velocity_kmh"] = velocity_kmh;
      windDoc["realtime"] = nama_hari + ", " + hari + "-" + bulan + "-" + tahun + " " + jam + ":" + menit + ":" + detik;
  
      windUrl = "http://rptugas.site/api/create4.php";  // url untuk menembakkan data ke database melalui program php
      
      http4.begin(client4, windUrl); // Memulai fungsi HTTP Client 4
      http4.addHeader("Content-Type", "application/json");
  
      String jsonPayload4;  // Deklarasi variabel untuk menyimpan payload/data json
      serializeJson(windDoc, jsonPayload4);


      // Menampilkan JSON ke Serial Monitor
      Serial.println("Data JSON 4 yang dikirim:");
      Serial.println(jsonPayload4);
  
      // Pengiriman data ke database menggunakan metode POST
      int httpCode4 = http4.POST(jsonPayload4);
  
      // Mendapatkan respon dari server
      if (httpCode4 > 0) {
        String payload4 = http4.getString();
        payload4.trim();
        if (payload4.length() > 0) {
          Serial.print("");
          Serial.println(payload4);
        }
      } else {
        Serial.print("HTTP Error Code: ");
        Serial.println(httpCode4);
      }
      http4.end();  // Mengakhiri service http client
      delay(3000);
    }

    unsigned long motorCurrent = millis();
    if (motorCurrent - motorMillis >= motorInterval) {
      motorMillis = motorCurrent;
    
      // Pemberian nilai parameter JSON untuk =====SENSOR LDR & ARAH PANEL SURYA=====
      motordriverDoc["ldrselatan"] = ldrValueSelatan; 
      motordriverDoc["ldrutara"] = ldrValueUtara;
      motordriverDoc["ldrtimur"] = ldrValueTimur;
      motordriverDoc["ldrbarat"] = ldrValueBarat;
      motordriverDoc["axis_a"] = ldrMAA7 + ldrMAA6 + ldrMAA ;
      motordriverDoc["axis_b"] = ldrMBA5 + ldrMBA4 + ldrMBB;
      motordriverDoc["realtime"] = nama_hari + ", " + hari + "-" + bulan + "-" + tahun + " " + jam + ":" + menit + ":" + detik;
  
      motordriverUrl = "http://rptugas.site/api/create5.php";  // url untuk menembakkan data ke database melalui program php   
      
      http5.begin(client5, motordriverUrl); // Memulai fungsi HTTP Client 3
      http5.addHeader("Content-Type", "application/json");
  
      String jsonPayload5;  // Deklarasi variabel untuk menyimpan payload/data json
      serializeJson(motordriverDoc, jsonPayload5);

      // Menampilkan JSON ke Serial Monitor
      Serial.println("Data JSON 5 yang dikirim:");
      Serial.println(jsonPayload5);
  
      // Pengiriman data ke database menggunakan metode POST
      int httpCode5 = http5.POST(jsonPayload5);
  
      // Mendapatkan respon dari server
      if (httpCode5 > 0) {
        String payload5 = http5.getString();
        payload5.trim();
        if (payload5.length() > 0) {
          Serial.print("");
          Serial.println(payload5);
        }
      } else {
        Serial.print("HTTP Error Code: ");
        Serial.println(httpCode5);
      }
      http5.end();  // Mengakhiri service http client
      delay(60000);
    }

    unsigned long forecastCurrent = millis();
    if (forecastCurrent - forecastMillis >= forecastInterval) {
      forecastMillis = forecastCurrent;
    
      // Pemberian nilai parameter JSON untuk ====FORECAST====
      forecastDoc["current_suhu"] = current_suhu; 
      forecastDoc["level_suhu"] = level_suhu;
      forecastDoc["trend_suhu"] = trend_suhu;
      forecastDoc["forecast_suhu"] = forecast_suhu;
      
      forecastDoc["current_tekanan"] = current_tekanan; 
      forecastDoc["level_tekanan"] = level_tekanan;
      forecastDoc["trend_tekanan"] = trend_tekanan;
      forecastDoc["forecast_tekanan"] = forecast_tekanan;
      
      forecastDoc["current_kelembaban"] = current_kelembaban; 
      forecastDoc["level_kelembaban"] = level_kelembaban;
      forecastDoc["trend_kelembaban"] = trend_kelembaban;
      forecastDoc["forecast_kelembaban"] = forecast_kelembaban;
      
      forecastDoc["current_cahaya"] = current_cahaya; 
      forecastDoc["level_cahaya"] = level_cahaya;
      forecastDoc["trend_cahaya"] = trend_cahaya;
      forecastDoc["forecast_cahaya"] = forecast_cahaya;
      
      forecastDoc["current_angin"] = current_angin; 
      forecastDoc["level_angin"] = level_angin;
      forecastDoc["trend_angin"] = trend_angin;
      forecastDoc["forecast_angin"] = forecast_angin;
      forecastDoc["realtime"] = nama_hari + ", " + hari + "-" + bulan + "-" + tahun + " " + jam + ":" + menit + ":" + detik;
  
      forecastUrl = "http://rptugas.site/api/create6.php";  // url untuk menembakkan data ke database melalui program php   
      
      http6.begin(client6, forecastUrl); // Memulai fungsi HTTP Client 6
      http6.addHeader("Content-Type", "application/json");
  
      String jsonPayload6;  // Deklarasi variabel untuk menyimpan payload/data json
      serializeJson(forecastDoc, jsonPayload6);

      // Menampilkan JSON ke Serial Monitor
      Serial.println("Data JSON 6 yang dikirim:");
      Serial.println(jsonPayload6);

      // Pengiriman data ke database menggunakan metode POST
      int httpCode6 = http6.POST(jsonPayload6);
  
      // Mendapatkan respon dari server
      if (httpCode6 > 0) {
        String payload6 = http6.getString();
        payload6.trim();
        if (payload6.length() > 0) {
          Serial.print("");
          Serial.println(payload6);
        }
      } else {
        Serial.print("HTTP Error Code: ");
        Serial.println(httpCode6);
      }
      http6.end();  // Mengakhiri service http client
      delay(60000);
    }  
} 


void loop() {
     if (Serial.available() > 0) {
       String data = Serial.readStringUntil('\n');  // Membaca data serial dari arduino mega wifi sampai karakter akhir '\n' atau karakter untuk fungsi break/enter
       parseSerialData(data); // Memproses data serial pada fungsi parseSerialData()
//       Serial.println("Received Serial Data: " + data);
        sendData();        
     }    
}
                       
//void loop() {
//
//  // Tanda waktu saat NodeMCU mulai aktif
//  unsigned long activePeriod = 5 * 60 * 1000; // 5 menit dalam milidetik
//  unsigned long startTime = millis();
//  unsigned long currentTime = millis();
//  
//  if (currentTime - startTime < activePeriod) {
//     // Lakukan operasi NodeMCU selama periode aktif
//     Serial.println("NodeMCU is active...");
//          
//     if (Serial.available() > 0) {
//     String data = Serial.readStringUntil('\n');  // Membaca data serial dari arduino mega wifi sampai karakter akhir '\n' atau karakter untuk fungsi break/enter
//     parseSerialData(data); // Memproses data serial pada fungsi parseSerialData()
//     //Serial.println("Received Serial Data: " + data);
//     }    
//     sendData();
//     
//     Serial.println("WiFi connected. Sending data...");
//     Serial.println("");
//     
//  } else {
//    // Setelah periode aktif berakhir, masuk ke mode deep sleep selama beberapa detik
//    Serial.println("Entering deep sleep for a few seconds...");
//    ESP.deepSleep(5e6); // Deep sleep selama 5 detik (5 * 1,000,000 mikrodetik)
//  }
//}
//   if (Serial.available() > 0) {
//   String data = Serial.readStringUntil('\n');  // Membaca data serial dari arduino mega wifi sampai karakter akhir '\n' atau karakter untuk fungsi break/enter
//   parseSerialData(data); // Memproses data serial pada fungsi parseSerialData()
//   //Serial.println("Received Serial Data: " + data);
//   }    
//   sendData();
//   Serial.println("WiFi connected. Sending data...");
//   Serial.println("");
//}

//      // Operasional NodeMCU selama periode aktif (12 jam)
//    unsigned long activePeriod = 5 * 60 * 1000; // 5 menit dalam milidetik
//    unsigned long startTime = millis();
//    unsigned long currentTime = millis();
//  
//    while (currentTime - startTime < activePeriod) {
//      // Lakukan operasi NodeMCU selama periode aktif
//      currentTime = millis();
//  
//    // Masuk ke mode deep sleep selama beberapa detik
//    Serial.println("Entering deep sleep for a few seconds...");
//    ESP.deepSleep(5e6); // Deep sleep selama 5 detik (5 * 1,000,000 mikrodetik)
