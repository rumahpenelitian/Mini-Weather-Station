#include <Wire.h>
#include <SPI.h>
#include <Adafruit_Sensor.h>  //Library BME280
#include <Adafruit_BME280.h>  //Library BME280
#include <BH1750.h>           //Library BH1750 GY-30
#include <SoftwareSerial.h>   //Library (Mungkin) Arah Angin
#include "RTClib.h"           //library RTC1307
#include <LiquidCrystal_I2C.h>//Library LCD

LiquidCrystal_I2C lcd(0x27, 16, 2); // Inisialisasi objek LCD dengan alamat 0x27, 16 kolom, dan 2 baris

#define SEALEVELPRESSURE_HPA (1013.25)  // Besar tekanan permukaan laut dalam satuan Hecto Pascal

#define acsDinamisPin A0  //Sensor amper dinamis 
#define acsStatisPin A1   //Sensor amper statis 
#define voltDinamisPin A2 //Sensor voltase Dinamis
#define voltStatisPin A3  //Sensor voltase statis 
#define ldr1Pin A4        //SELATAN motor axis A ungu
#define ldr2Pin A5        //UTARA motor axis A biru
#define ldr3Pin A6        //TIMUR motor axis B orange
#define ldr4Pin A7        //BARAT motor axis B kuning
#define sensor_hujan A8   //Sensor Rain Drop

BH1750 lightMeter; //Fungsi BH1750

//Deklarasi Module Arah Angin
SoftwareSerial dataserial(11, 12); // PIN D7=11 dan D6=D12, deklarasi arah angin
int karakterA, karakterB;
String data_angin, s_angin, arah_angin;
//==========END===========

//Deklarasi anemometer parameters
volatile byte rpmcount; // count signals
volatile unsigned long last_micros;
unsigned long timeold;
unsigned long timemeasure = 10.00; // seconds
int timetoSleep = 1;               // minutes
unsigned long sleepTime = 15;      // minutes
unsigned long timeNow;
int countThing = 0;
int GPIO_pulse = 3; //  PIN D3 Arduino
float rpm, rps;     // frequencies
float velocity_kmh; // km/h
float velocity_ms;  //m/s
float calibration_value = 5.0; //This value is obtained from comparing with the manufacturer's anemometer sensor
//==========END===========

// Deklarasi tipe data dan inisialisasi nilai awal variabel untuk menyimpan data yang akan dikirimkan ke esp8266
String sendToESP = "";

// Deklarasi tipe data dan inisialisasi nilai awal ldr dan ldr threshold
float ldrValueBarat = 0.00;
float ldrValueTimur = 0.00;
float ldrValueSelatan = 0.00;
float ldrValueUtara = 0.00;
//==========END===========

// Definisi pin untuk motor A (Axis A)
const int motorARPWM = 8; // RPWM motor A PIN D8
const int motorALPWM = 7; // LPWM motor A PIN D7 
// Definisi pin untuk motor B (Axis B)
const int motorBRPWM = 6;  // RPWM motor B PIN D6
const int motorBLPWM = 5;  // LPWM motor B PIN D5
//==========END===========

// Definisi pin untuk tombol auto dan manual            //TIDAK DIGUNAKAN
//const int autoButtonPin = 9;    // PIN D9
//const int manualButtonPin = 10; // PIN D10
//bool isAutoMode = true;         //Definisi auto on off
//bool lastAutoButtonState = HIGH;   // Menyimpan status terakhir tombol auto
//bool lastManualButtonState = HIGH; // Menyimpan status terakhir tombol manual
//==========END===========

// Meletakkan fungsi adafruit bme280 kedalam variabel "bme"
Adafruit_BME280 bme;
//==========END===========

//Waktu kirim data
unsigned long previousMillis = 0; // Reset waktu millis
const long interval = 5000;  // Interval dalam milidetik (5 detik)
//==========END===========

//Waktu tampil LCD                            //TIDAK DIGUNAKAN                         
//unsigned long previousMillisLCD = 0;   // Menyimpan waktu terakhir LCD diperbarui     
//const long intervalLCD = 1000;         // Interval pembaruan LCD dalam milidetik      
//==========END===========                                                              

//Waktu reset data tipping buckect
//unsigned long previousUpdateMillis = 0;     //TIDAK DIGUNAKAN
//const unsigned long updateInterval = 60000;
//==========END===========

// Deklarasi Jam RTC 1307
RTC_DS3231 rtc;
DateTime now;
//==========END===========

// Deklarasi Tipping Bucket
// Gunakan pin D2 pada Arduino, Tegangan 5V Kemudian upload code ini
const int pin_interrupt = 2; // Menggunakan pin interrupt https://www.arduino.cc/reference/en/language/functions/external-interrupts/attachinterrupt/
long int jumlah_tip = 0;
long int temp_jumlah_tip = 0;
float curah_hujan = 0.00;
float curah_hujan_per_menit = 0.00;
float curah_hujan_per_jam = 0.00;
float curah_hujan_per_hari = 0.00;
float curah_hujan_hari_ini = 0.00;
float temp_curah_hujan_per_menit = 0.00;
float temp_curah_hujan_per_jam = 0.00;
float temp_curah_hujan_per_hari = 0.00;
float milimeter_per_tip = 0.70;
String cuaca = "Cerah";

volatile boolean flag = false;

// Inisialisasi struktur waktu
String nama_hari, hari, bulan, tahun, jam, menit, detik;
//==========END===========

// Tentukan ambang batas hysteresis
float hysteresisThreshold = 10.0; // Sesuaikan dengan kebutuhan
String ldrMAA7, ldrMAA6, ldrMAA; //Ldr dan Motor Servo Axis A
String ldrMBA5, ldrMBA4, ldrMBB; //Ldr dan Motor Servo Axis B
//==========END===========

// Parameter Holt's Exponential Smoothing untuk masing-masing variabel
float alpha_suhu = 0.5;
float beta_suhu = 0.3;

float alpha_tekanan = 0.5;
float beta_tekanan = 0.3;

float alpha_kelembaban = 0.5;
float beta_kelembaban = 0.3;

float alpha_cahaya = 0.5;
float beta_cahaya = 0.3;

float alpha_angin = 0.5;
float beta_angin = 0.3;

// Nilai Awal
float level_suhu = 0, trend_suhu = 0;
float level_tekanan = 0, trend_tekanan = 0;
float level_kelembaban = 0, trend_kelembaban = 0;
float level_cahaya = 0, trend_cahaya = 0;
float level_angin = 0, trend_angin = 0;

bool initialized = false;
//==========END===========

//inisiasi void hitung curah hujan
void hitung_curah_hujan()
{
  flag = true;
}
//==========END===========

void setup() {
  
  // Initialize LCD
  lcd.init();
  // Nyalakan backlight LCD
  lcd.backlight();

  // Pin Mode Motor Driver 
  pinMode(motorARPWM, OUTPUT);      // RPWM motor A PIN D8
  pinMode(motorALPWM, OUTPUT);      // LPWM motor A PIN D7
  pinMode(motorBRPWM, OUTPUT);      // RPWM motor B PIN D6
  pinMode(motorBLPWM, OUTPUT);      // LPWM motor B PIN D5
  pinMode(ldr1Pin, INPUT);          // LDR untuk arah SELATAN motor axis A ungu
  pinMode(ldr2Pin, INPUT);          // LDR untuk arah UTARA motor axis A biru
  pinMode(ldr3Pin, INPUT);          // LDR untuk arah TIMUR motor axis B orange
  pinMode(ldr4Pin, INPUT);          // LDR untuk arah BARAT motor axis B kuning
//  pinMode(autoButtonPin, INPUT);  // Pin Mode untuk tombol auto       // TIDAK DIGUNAKAN
//  pinMode(manualButtonPin, INPUT);// Pin Mode untuk tombol manual     // TIDAK DIGUNAKAN
  pinMode(acsDinamisPin, INPUT);    //Pin Mode Amper
  pinMode(acsStatisPin, INPUT);     //Pin Mode Amper
  pinMode(voltDinamisPin, INPUT);   //Pin Mode Volt
  pinMode(voltStatisPin, INPUT);    //Pin Mode Volt

  //setup sensor kecepatan angin
  pinMode(GPIO_pulse, INPUT_PULLUP);
  digitalWrite(GPIO_pulse, LOW);
  
  Serial.begin(9600);     // Menampilkan pesan void setup
  Serial3.begin(9600);    // Komunikasi serial ke ESP8266 
  
  detachInterrupt(digitalPinToInterrupt(GPIO_pulse));                         // force to initiate Interrupt on zero
  attachInterrupt(digitalPinToInterrupt(GPIO_pulse), rpm_anemometer, RISING); //Initialize the intterrupt pin
  rpmcount = 0;
  rpm = 0;
  timeold = 0;
  timeNow = 0;
  //==========END===========

  // Komunikasi dataserial Arah angin
  dataserial.begin(9600); 
  //==========END===========

  // Memulai Curah Hujan setup
  pinMode(pin_interrupt, INPUT);
  attachInterrupt(digitalPinToInterrupt(pin_interrupt), hitung_curah_hujan, FALLING); // Akan menghitung tip jika pin berlogika dari HIGH ke LOW
  //==========END===========
   
  // Inisialisasi RTC
  if (!rtc.begin())
  {
    lcd.setCursor(0, 0);
    Serial.println("Couldn't find RTC");
    lcd.print("Couldn't find RTC");
    delay(5000);
    lcd.clear();
    while (1);
  }
  //Hanya dibuka komen nya jika akan kalibrasi waktu saja (hanya sekali) setelah itu harus di tutup komennya kembali supaya tidak set waktu terus menerus
  //rtc.adjust(DateTime(F(__DATE__), F(__TIME__))); // Set waktu langsung dari waktu PC
  //rtc.adjust(DateTime(2025, 1, 18, 12, 10, 0));    // Set Tahun, bulan, tanggal, jam, menit, detik secara manual
  //Cukup dibuka salah satu dari 2 baris diatas, pilih set waktu secara manual atau dari PC
  //===================================================================================================================================================================
  bacaRTC();
 //==========END===========

  // Memulai service BH1750
  Wire.begin();
  lightMeter.begin();
  Serial.println(F("BH1750 Test begin"));
  lcd.setCursor(0, 0);
  lcd.print(F("Starting BH1750"));
  delay(5000);

  // Memulai service BME280
  while (!Serial);
  lcd.setCursor(0, 1);
  Serial.println(F("Starting BME280 Service..."));
  lcd.print(F("Starting BME280 Service..."));
  delay(5000);
  lcd.clear();
  unsigned status;
  status = bme.begin(0x76);  
  if (!status) {
    lcd.setCursor(0, 0);    
    Serial.println("Could not find a valid BME280 sensor, check wiring!");
    lcd.print("Could not find a valid BME280 sensor, check wiring!");
    lcd.setCursor(0, 1);
    Serial.print("SensorID was: 0x"); 
    lcd.print("SensorID was: 0x"); 
    delay(5000);
    lcd.clear();
    Serial.println(bme.sensorID(), 16);
    while (1)delay(100);
  }  
  delay(1000);
}

void loop() {

  // ===== PEMBACAAN SENOSR VOLTASE (TEGANGAN, VOLTASE)=====  
  
  // Deklarasi tipe data dan inisialisasi nilai variabel untuk sensor tegangan
  float voltReadDinamis = 0.0; 
  float voltReadStatis = 0.0;
  float voltValueDinamis = 0.0;
  float voltValueStatis = 0.0;

  // Resistor pembagi tegangan (Voltage divider)
  float R1 = 30000.0; //30k
  float R2 = 7500.0; //7500 Ohm resistor, 

  // Kalkulasi nilai akhir tegangan dinamis
  voltReadDinamis = (analogRead(voltDinamisPin) * 5.0) / 1024.0;
  voltValueDinamis = voltReadDinamis / (R2/(R1+R2));

  // Kalkulasi nilai akhir tegangan statis
  voltReadStatis = (analogRead(voltStatisPin) * 5.0) / 1024.0;
  voltValueStatis = voltReadStatis / (R2/(R1+R2));

  //==========END===========


  // ===== PEMBACAAN SENSOR ACS712 (ARUS, AMPER) =====
  
  // Deklarasi tipe data dan inisialisasi nilai variabel untuk sensor arus
  float acsReadDinamis = 0.00;
  float acsReadStatis = 0.00;
  float acsSampleDinamis = 0.00;
  float acsSampleStatis = 0.00;
  float avrAcsDinamis = 0.00;
  float avrAcsStatis = 0.00;
  float acsValueDinamis = 0.00;
  float acsValueStatis = 0.00;

  // Penghitungan nilai arus dinamis
  int a = 0;
  for (int a = 0; a < 500; a++) { 
    acsReadDinamis = analogRead(acsDinamisPin);   
    acsSampleDinamis = acsSampleDinamis + acsReadDinamis;   // Mengambil nilai arus sebanyak 150 sampel setiap 3 milidetik
    delay (3); 
  }
  avrAcsDinamis = acsSampleDinamis / 500; // Menghitung nilai rata-rata arus berdasarkan sampel
  acsValueDinamis = (2.5 - (avrAcsDinamis * (5.0 / 1024.0)) ) / 0.185;  //0.185 = 5A 0.100 = 20A 0.66 = 30A // Penghitungan nilai akhir arus

  // Penghitungan nilai arus statis
  int b = 0;
  for (int b = 0; b < 500; b++) { 
    acsReadStatis = analogRead(acsStatisPin);    
    acsSampleStatis = acsSampleStatis + acsReadStatis;  // Mengambil nilai arus sebanyak 150 sampel setiap 3 milidetik
    delay (3); 
  }
  
  avrAcsStatis = acsSampleStatis / 500; // Menghitung nilai rata-rata arus berdasarkan sampel
  acsValueStatis = (2.5 - (avrAcsStatis * (5.0 / 1024.0)) ) / 0.185;  //0.185 = 5A 0.100 = 20A 0.66 = 30A // Penghitungan nilai akhir arus

  //==========END===========


  // ===== PEMJUMLAHAN NILAI TEGANGAN + ARUS = POWER (WATT) =====
  
  // Penghitungan nilai daya
  float powerDinamis = 0.00;
  float powerStatis = 0.00;
  powerDinamis = voltValueDinamis * acsValueDinamis;
  powerStatis = voltValueStatis * acsValueStatis;
  
  //==========END===========


  // ===== PEMBACAAN SENSOR BME/BMP280 =====
  
  float suhu = bme.readTemperature(); // Mendapatkan nilai suhu udara
  float tekanan = bme.readPressure() / 100.0F;  // Mendapatkan nilai tekanan udara
  float altitude = bme.readAltitude(SEALEVELPRESSURE_HPA);  // Mendapatkan nilai altitude (ketinggian/mdpl)
  float kelembaban = bme.readHumidity();  // Mendapatkan nilai kelembaban udara

  //==========END===========

  
  // ===== PEMBACAAN SENSOR LUX BH1750 =====
  
  float lux = lightMeter.readLightLevel();

  //==========END===========


  // ===== PEMBACAAN SENSOR RAIN DROP =====
  
  String kondisiRaindrop;
  int kondisi_sensor = analogRead(sensor_hujan); //Instruksi untuk membaca nilai digital
  if (kondisi_sensor >= 560) { //Saat hujan terdeteksi maka nilai analog = 520
    kondisiRaindrop = "Kering";
  }
  //Intruksi saat sensor mendeteksi hujan
  else {
    kondisiRaindrop = "Hujan";
  }
  
  //==========END===========


  // ===== PEMBACAAN SENSOR TIPPING BUCKET =====
  
  if (flag == true) // don't really need the == true but makes intent clear for new users
  {
    curah_hujan += milimeter_per_tip; // Akan bertambah nilainya saat tip penuh
    jumlah_tip++;
    //delay(500);
    flag = false; // reset flag
  }
  bacaRTC();
  
  //========== Step 1 ===========

  // ===== PERHITUGAN TIPPING BUCKET =====
  curah_hujan_hari_ini = jumlah_tip * milimeter_per_tip;
  temp_curah_hujan_per_menit = curah_hujan;
  
  //Probabilistik Curah Hujan https://www.bmkg.go.id/cuaca/probabilistik-curah-hujan.bmkg
  if (curah_hujan_hari_ini <= 0.00 && curah_hujan_hari_ini <= 0.50) {
    cuaca = "Cerah";
  } if (curah_hujan_hari_ini > 0.50 && curah_hujan_hari_ini <= 20.00) {
    cuaca = "Hujan Ringan";
  } if (curah_hujan_hari_ini > 20.00 && curah_hujan_hari_ini <= 50.00) {
    cuaca = "Hujan Sedang";
  } if (curah_hujan_hari_ini > 50.00 && curah_hujan_hari_ini <= 100.00) {
    cuaca = "Hujan Lebat";
  } if (curah_hujan_hari_ini > 100.00 && curah_hujan_hari_ini <= 150.00) {
    cuaca = "Hujan Sangat Lebat";
  } if (curah_hujan_hari_ini > 150.00) {
    cuaca = "Hujan ekstrem";
  }

  //==========Step 2===========

    if (now.second() == 0)
    {   
    curah_hujan_per_menit = temp_curah_hujan_per_menit; // Curah hujan per menit dihitung ketika detik 0
    temp_curah_hujan_per_jam += curah_hujan_per_menit;  // Curah hujan per jam dihitung dari penjumlahan curah hujan per menit namun disimpan dulu dalam variabel temp

    if (now.minute() == 0) // Reset tiap jam       
    { 
      curah_hujan_per_jam = temp_curah_hujan_per_jam;   // Curah hujan per jam baru dihitung ketika menit 0
      temp_curah_hujan_per_hari += curah_hujan_per_jam; // Curah hujan per hari dihitung dari penjumlahan curah hujan per jam namun disimpan dulu dalam variabel temp
      temp_curah_hujan_per_jam = 0.00;                  // Reset temp curah hujan per jam
    }
     if (now.hour() == 0 && now.minute() == 0) //Reset tiap hari
    {
      curah_hujan_per_hari = temp_curah_hujan_per_hari; // Curah hujan per jam baru dihitung ketika menit 0 dan jam 0 (Tengah malam)
      temp_curah_hujan_per_hari = 0.00;                 // Reset temp curah hujan per hari
      curah_hujan_hari_ini = 0.00;                      // Reset curah hujan hari ini
      jumlah_tip = 0;                                   // Jumlah tip di reset setap 24 jam sekali (Tengah malam)
      cuaca = "Cerah";
      curah_hujan = 0.00;
    }
    temp_curah_hujan_per_menit = 0.00;
    curah_hujan = 0.00;
    //delay(1000);
  }
 
  //==========Step 3===========
  
  // print saat sudah 1 menit
  if ((jumlah_tip != temp_jumlah_tip) || (now.second() == 0)) // Print serial setiap 1 menit atau ketika jumlah_tip berubah
  {
  Serial.println("");
  Serial.print(nama_hari);
  Serial.print(", ");
  Serial.print(hari);
  Serial.print("-");
  Serial.print(bulan);
  Serial.print("-");
  Serial.print(tahun);
  Serial.print(" - ");
  Serial.print("Waktu: ");
  Serial.println(konversi_jam(jam) + ":" + konversi_jam(menit) + ":" + konversi_jam(detik));
  Serial.print("Cuaca=");
  Serial.println(cuaca); // Print cuaca hari ini (Ini bukan ramalan cuaca tapi membaca cuaca yang sudah terjadi/ sedang terjadi hari ini)
  Serial.print("Total jumlah tip=");
  Serial.print(jumlah_tip);
  Serial.println(" kali ");
  Serial.print("Curah hujan hari ini=");
  Serial.print(curah_hujan_hari_ini, 1);
  Serial.println(" mm ");
  Serial.print("Curah hujan per menit=");
  Serial.print(curah_hujan_per_menit, 1);
  Serial.println(" mm ");
  Serial.print("Curah hujan per jam=");
  Serial.print(curah_hujan_per_jam, 1);
  Serial.println(" mm ");
  Serial.print("Curah hujan per hari=");
  Serial.print(curah_hujan_per_hari, 1);
  Serial.println(" mm ");
  }
  temp_jumlah_tip = jumlah_tip;

  //==========END===========

  
  // ===== PEMBACAAN KECEPATAN ANGIN (ANEMOMETER) (MEASURE RPM)=====
  
  if ((millis() - timeold) >= timemeasure * 1000) // Counts every 10 seconds
  {
    countThing++;
    detachInterrupt(digitalPinToInterrupt(GPIO_pulse)); // Disable interrupt when calculating
    rps = float(rpmcount) / float(timemeasure); // rotations per second
    //velocity_ms = rps * calibration_value; // m/s
    velocity_ms = rps; // m/s
    velocity_kmh = velocity_ms * 3.6; // km/h
    if (countThing == 1) // Send data per 25 seconds
    {
    //Serial.println("Anemometer Send data to server");
      countThing = 0;
    }
    timeold = millis();
    rpmcount = 0;
    attachInterrupt(digitalPinToInterrupt(GPIO_pulse), rpm_anemometer, RISING); // enable interrupt
  }
  
  //==========END===========


  // ===== PEMBACAAN SESOR ARAH MATA ANGIN ===== 
  
  if (dataserial.available()) // Jika ada data yang diterima dari sensor
    {
      data_angin = dataserial.readString(); // data yang diterima dari sensor berawalan tanda * dan diakhiri tanda #, contoh *1#
      karakterA = data_angin.indexOf("*");  // karakterA adalah index tanda *
      karakterB = data_angin.indexOf("#");  // karakterB adalah index tanda #
      s_angin = data_angin.substring(karakterA + 1, karakterB); // membuang tanda * dan # sehingga di dapat nilai dari arah angin
      if (s_angin.equals("1")) {            // jika nilai dari sensor 1 maka arah angin utara
        arah_angin = "Utara";
      }
      if (s_angin.equals("2")) {
        arah_angin = "Timur laut";
      }
      if (s_angin.equals("3")) {
        arah_angin = "Timur";
      }
      if (s_angin.equals("4")) {
        arah_angin = "Tenggara";
      }
      if (s_angin.equals("5")) {
        arah_angin = "Selatan";
      }
      if (s_angin.equals("6")) {
        arah_angin = "Barat daya";
      }
      if (s_angin.equals("7")) {
        arah_angin = "Barat";
      }
      if (s_angin.equals("8")) {
        arah_angin = "Barat laut";
      }
    }
    
  //==========END===========


// ===== FORECAST HOLT EXPONENTIAL SMOOTHING =====

// Inisialisasi variabel untuk pertama kali
  if (!initialized) {
    level_suhu = suhu; // Suhu dalam celcius
    level_tekanan = tekanan; // Tekanan udara dalam hPa
    level_kelembaban = kelembaban ; // Kelembaban dalam persen
    level_cahaya = lux ; // Intensitas cahaya dalam lux
    level_angin = velocity_ms; // Kecepatan angin dalam ms
    
    trend_suhu = trend_tekanan = trend_cahaya = trend_kelembaban = trend_angin = 0;
    initialized = true;
    
    Serial.println("Holt's Exponential Smoothing initialized.");
  }
  
  // Menghasilkan nilai acak untuk setiap parameter
  float current_suhu = suhu;
  float current_tekanan = tekanan;
  float current_kelembaban = kelembaban;
  float current_cahaya = lux;
  float current_angin = velocity_ms;

  // Holt's Exponential Smoothing untuk Suhu
  float new_level_suhu = alpha_suhu * current_suhu + (1 - alpha_suhu) * (level_suhu + trend_suhu);
  float new_trend_suhu = beta_suhu * (new_level_suhu - level_suhu) + (1 - beta_suhu) * trend_suhu;
  float forecast_suhu = new_level_suhu + 1 * new_trend_suhu;

  // Holt's Exponential Smoothing untuk Tekanan
  float new_level_tekanan = alpha_tekanan * current_tekanan + (1 - alpha_tekanan) * (level_tekanan + trend_tekanan);
  float new_trend_tekanan = beta_tekanan * (new_level_tekanan - level_tekanan) + (1 - beta_tekanan) * trend_tekanan;
  float forecast_tekanan = new_level_tekanan + 1 * new_trend_tekanan;

  // Holt's Exponential Smoothing untuk Intensitas Cahaya
  float new_level_cahaya = alpha_cahaya * current_cahaya + (1 - alpha_cahaya) * (level_cahaya + trend_cahaya);
  float new_trend_cahaya = beta_cahaya * (new_level_cahaya - level_cahaya) + (1 - beta_cahaya) * trend_cahaya;
  float forecast_cahaya = new_level_cahaya + 1 * new_trend_cahaya;

  // Holt's Exponential Smoothing untuk Kelembaban
  float new_level_kelembaban = alpha_kelembaban * current_kelembaban + (1 - alpha_kelembaban) * (level_kelembaban + trend_kelembaban);
  float new_trend_kelembaban = beta_kelembaban * (new_level_kelembaban - level_kelembaban) + (1 - beta_kelembaban) * trend_kelembaban;
  float forecast_kelembaban = new_level_kelembaban + 1 * new_trend_kelembaban;

  // Holt's Exponential Smoothing untuk Kecepatan Angin
  float new_level_angin = alpha_angin * current_angin + (1 - alpha_angin) * (level_angin + trend_angin);
  float new_trend_angin = beta_angin * (new_level_angin - level_angin) + (1 - beta_angin) * trend_angin;
  float forecast_angin = new_level_angin + 1 * new_trend_angin;

  // Memperbarui level dan trend untuk semua variabel
  level_suhu = new_level_suhu;
  trend_suhu = new_trend_suhu;

  level_tekanan = new_level_tekanan;
  trend_tekanan = new_trend_tekanan;

  level_cahaya = new_level_cahaya;
  trend_cahaya = new_trend_cahaya;

  level_kelembaban = new_level_kelembaban;
  trend_kelembaban = new_trend_kelembaban;

  level_angin = new_level_angin;
  trend_angin = new_trend_angin;

//==========END===========


//  String aktif, manual;           

//  Serial.print("Nilai LDR Selatan = ");
//  Serial.println(ldrValueSelatan);
//  Serial.print("Nilai LDR Utara = ");
//  Serial.println(ldrValueUtara);
//  Serial.print("Nilai LDR Timur = ");
//  Serial.println(ldrValueTimur);
//  Serial.print("Nilai LDR Barat = ");
//  Serial.println(ldrValueBarat);
  
//  // Baca nilai dari tombol auto dan manual
//  bool autoButtonState    = digitalRead(autoButtonPin);
//  bool manualButtonState  = digitalRead(manualButtonPin);

//  // Cek apakah tombol auto baru saja ditekan
//  if (autoButtonState == LOW && lastAutoButtonState == HIGH) {
//    isAutoMode = true; // Set mode ke otomatis
//    aktif = "aktif";
//    Serial.println("Mode Auto Aktif");
//    Serial.println("Motor akan bergerak otomatis mengikuti matahari.");
//  }
//
//  // Cek apakah tombol manual baru saja ditekan
//  if (manualButtonState == LOW && lastManualButtonState == HIGH) {
//    isAutoMode = false; // Set mode ke manual
//    manual= "Stop";
//  }

//  // Simpan status tombol untuk penggunaan berikutnya
//  lastAutoButtonState = autoButtonState;
//  lastManualButtonState = manualButtonState;


  // ===== PEMBACAAN NILAI ANALOG MENGGUNAKAN SENSOR LDR =====

  float ldrValueSelatan = map(analogRead(ldr1Pin), 0, 1023, 100, 0); // LDR untuk arah selatan
  float ldrValueUtara   = map(analogRead(ldr2Pin), 0, 1023, 100, 0); // LDR untuk arah utara
  float ldrValueTimur   = map(analogRead(ldr3Pin), 0, 1023, 100, 0); // LDR untuk arah timur
  float ldrValueBarat   = map(analogRead(ldr4Pin), 0, 1023, 100, 0); // LDR untuk arah barat

    // Perhitungan perbedaan nilai LDR antara arah timur dan barat untuk motor A (Axis A)
    float ldrDifferenceA = ldrValueBarat - ldrValueTimur;

    // Kontrol Motor A (Axis A) secara otomatis berdasarkan perbedaan nilai LDR
    if (ldrDifferenceA > hysteresisThreshold) {
      // Putar motor A ke arah timur jika nilai LDR timur lebih tinggi dari ambang batas
      digitalWrite(motorARPWM, HIGH); // RPWM motor A PIN D8
      digitalWrite(motorALPWM, LOW);  // LPWM motor A PIN D7
      ldrMAA7 = "Ke-Selatan";
      ldrMAA6 = "";
      ldrMAA = "";
      //Serial.println("Motor A berputar ke arah Selatan A7");
      
    } else if (ldrDifferenceA < -hysteresisThreshold) {
      // Putar motor A ke arah barat jika nilai LDR barat lebih tinggi dari ambang batas
      digitalWrite(motorARPWM, LOW);  // RPWM motor A PIN D8
      digitalWrite(motorALPWM, HIGH); // LPWM motor A PIN D7
      ldrMAA7 = "";
      ldrMAA6 = "Ke-Utara";
      ldrMAA = "";
      //Serial.println("Motor A berputar ke arah Utara A6");
      
    } else {
      // Berhenti motor A jika kedua nilai LDR berada dalam ambang batas hysteresis
      digitalWrite(motorARPWM, LOW); // RPWM motor A PIN D8
      digitalWrite(motorALPWM, LOW); // LPWM motor A PIN D7
      ldrMAA7 = "";
      ldrMAA6 = "";
      ldrMAA = "Berhenti-A";
      //Serial.println("Motor A berhenti");
    }

    // Perhitungan perbedaan nilai LDR antara arah timur dan barat untuk motor B (Axis B)
    float ldrDifferenceB = ldrValueUtara - ldrValueSelatan;

    // ===== STEP 1 =====

    // Kontrol Motor B (Axis B) secara otomatis berdasarkan perbedaan nilai LDR
    if (ldrDifferenceB > hysteresisThreshold) {
      // Putar motor B ke arah timur jika nilai LDR timur lebih tinggi dari ambang batas
      digitalWrite(motorBRPWM, HIGH); // RPWM motor B PIN D6
      digitalWrite(motorBLPWM, LOW);  // LPWM motor B PIN D5
      ldrMBA5 = "Ke-Timur";
      ldrMBA4 = "";  // Kosongkan variabel jika tidak digunakan
      ldrMBB = "";   // Kosongkan variabel jika tidak digunakan
      
      //Serial.println("Motor B berputar ke arah Timur A5");
    } else if (ldrDifferenceB < -hysteresisThreshold) {
      // Putar motor B ke arah barat jika nilai LDR barat lebih tinggi dari ambang batas
      digitalWrite(motorBRPWM, LOW);  // RPWM motor B PIN D6
      digitalWrite(motorBLPWM, HIGH); // LPWM motor B PIN D5
      ldrMBA5 = "";  // Kosongkan variabel jika tidak digunakan
      ldrMBA4 = "Ke-Barat";
      ldrMBB = "";   // Kosongkan variabel jika tidak digunakan
      
      //Serial.println("Motor B berputar ke arah Barat A4");
    } else {
      // Berhenti motor B jika kedua nilai LDR berada dalam ambang batas hysteresis
      digitalWrite(motorBRPWM, LOW); // RPWM motor B PIN D6
      digitalWrite(motorBLPWM, LOW); // LPWM motor B PIN D5
      ldrMBA5 = "";  // Kosongkan variabel jika tidak digunakan
      ldrMBA4 = "";  // Kosongkan variabel jika tidak digunakan
      ldrMBB = "Berhenti-B";
      //Serial.println("Motor B berhenti");
     }
//
//  if (isAutoMode) {
////  Kondisi if
//  } else {
//    // Tambahkan pesan pencetakan untuk mode manual jika diperlukan
//    Serial.println("==========================================");
//    Serial.println("Berhsil dimatikan sun trackig panel surya");
//    Serial.println("==========================================");
//  }

  //==========END===========
  
  
  /* Menggabungkan dan menyimpan data sensor ke dalam variabel yang akan dikirim ke esp8266, 
  menggunakan karakter '#' sebagai delimiter atau pembatas antar data (string) */
    sendToESP = (String() 
                 + suhu + "#" + altitude + "#" + tekanan + "#" + kelembaban + "#" 
                 + voltValueDinamis + "#" + voltValueStatis + "#" + acsValueDinamis + "#" + acsValueStatis + "#" + powerDinamis + "#" + powerStatis +  "#"
                 + jumlah_tip + "#" + curah_hujan_hari_ini + "#" + curah_hujan_per_menit + "#" + curah_hujan_per_jam + "#" + curah_hujan_per_hari + "#"
                 + cuaca + "#" + lux + "#" + kondisiRaindrop + "#" + arah_angin + "#" + rps + "#" + velocity_ms + "#" + velocity_kmh + "#"
                 + ldrMAA + "#" + ldrMAA7 + "#" + ldrMAA6 + "#" + ldrMBB + "#" + ldrMBA5 + "#" + ldrMBA4 + "#" 
                 + ldrValueSelatan + "#" + ldrValueUtara + "#" + ldrValueTimur + "#" + ldrValueBarat + "#"
                 + konversi_jam(jam) + "#" + konversi_jam(menit) + "#" + konversi_jam(detik) + "#" 
                 + nama_hari + "#" + konversi_jam(hari) + "#" + konversi_jam(bulan) + "#" + tahun + "#" 
                 + current_suhu + "#" + level_suhu + "#" + trend_suhu + "#" + forecast_suhu + "#" 
                 + current_tekanan + "#" + level_tekanan + "#" + trend_tekanan + "#" + forecast_tekanan + "#" 
                 + current_kelembaban + "#" + level_kelembaban + "#" + trend_kelembaban + "#" + forecast_kelembaban + "#" 
                 + current_cahaya + "#" + level_cahaya + "#" + trend_cahaya + "#" + forecast_cahaya + "#" 
                 + current_angin + "#" + level_angin + "#" + trend_angin + "#" + forecast_angin); 

  // Proses pengiriman data ke esp8266
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis >= interval) { // Dilakukan setiap 1 menit sekali
    previousMillis = currentMillis;
    sendToESP.trim(); // Memangkas karakter-karakter yang tidak perlu seperti \r dan \n agar tidak ikut terkirim ke esp8266
    Serial3.println(sendToESP); // Kirim ke esp8266 menggunakan Serial 3
    Serial.println(sendToESP); // Menampilkan hasil kirim data

      // Pembaruan pertama
      lcd.setCursor(0, 0);
      lcd.print("Axis ");
      lcd.print(ldrMAA);
      lcd.print(ldrMAA7);
      lcd.print(ldrMAA6);
      lcd.setCursor(0, 1);
      lcd.print("Axis ");
      lcd.print(ldrMBB);
      lcd.print(ldrMBA5);
      lcd.print(ldrMBA4);
//      lcd.clear();
  }

      // Pembaruan kedua
      lcd.setCursor(0, 0);
      lcd.print(nama_hari + " " + konversi_jam(hari) + " " + konversi_jam(bulan) + " " + tahun);
      lcd.setCursor(0, 1);
      lcd.print("    " + konversi_jam(jam) + ":" + konversi_jam(menit) + ":" + konversi_jam(detik) + "    ");
//      lcd.clear();

  // Membaca kembali data yang diterima dari esp8266
  if (Serial3.available() > 0) {
    String msg = Serial3.readStringUntil('\n');
    Serial.println("");
    Serial.print("Data msg: ");
    Serial.println(msg);  // Tampilkan di serial monitor Arduino Mega WiFi
  }
  // Panggil fungsi pembaruan LCD pada interval tertentu
}


// ===== MERESET KECEPATAN ANGIN (ANEMOMETER) =====

void rpm_anemometer()
{
  if (long(micros() - last_micros) >= 5000) // If the magnet hits the sensor more than 5000 us / 5 mS
  { // time to debounce measures
    rpmcount++;
    last_micros = micros();
  }
// Serial.println("***** detect *****");
}

//==========END===========


// ===== MENAMBAHKAN ANGKA 0 PADA LCD =====

String konversi_jam(String angka) // Fungsi untuk supaya jika angka satuan ditambah 0 di depannya, Misalkan jam 1 maka jadi 01 pada LCD
{
  if (angka.length() == 1)
  {
    angka = "0" + angka;
  }
  else
  {
    angka = angka;
  }
  return angka;
}

//==========END===========


// ===== FUNGSI PEMBACAAN RTC UNTUK MENENTUKAN WAKTU =====

void bacaRTC()
{
  // Mendapatkan data jam, menit, dan detik
  now = rtc.now(); // Ambil data waktu dari DS3231
  jam = String(now.hour(), DEC);
  menit = String(now.minute(), DEC);
  detik = String(now.second(), DEC);

  // Mendapatkan data tanggal, bulan, dan tahun
  hari = String(now.day(), DEC);
  bulan = String(now.month(), DEC);
  tahun = String(now.year(), DEC);

  // Mendapatkan indeks hari dalam seminggu
  int indeks_hari = now.dayOfTheWeek(); 

  // Membuat peta nama hari berdasarkan indeks
  switch (indeks_hari) {
    case 0:
      nama_hari = "Minggu";
      break;
    case 1:
      nama_hari = "Senin";
      break;
    case 2:
      nama_hari = "Selasa";
      break;
    case 3:
      nama_hari = "Rabu";
      break;
    case 4:
      nama_hari = "Kamis";
      break;
    case 5:
      nama_hari = "Jumat";
      break;
    case 6:
      nama_hari = "Sabtu";
      break;
    default:
      nama_hari = "Unknown";
      break;
  }
}
//==========END===========
