#ifndef eHealthClass_h
#define eHealthClass_h

#include "Arduino.h"
#include "DFRobot_MAX30102.h"

class eHealthClass {

public:

  eHealthClass(void);

  void initMax30102();
  void readMax30102();
  void printMax30102Data();

  float getECG();
  int getAirFlow();

private:

  DFRobot_MAX30102 particleSensor;

  int32_t SPO2;
  int8_t SPO2Valid;
  int32_t heartRate;
  int8_t heartRateValid;

};

extern eHealthClass eHealth;

#endif
