#!/bin/bash
GPIO_NO=UNDEFINED
SWITCH_SEC=UNDEFINED
WAIT_GPIO=0.05

if [ "$1" = "" ] ; then
  echo Parameter needed. GPIO# long or short
  exit 16
fi

if [ "$2" = "" ] ; then
  echo Parameter needed. GPIO# long or short
  exit 16
fi

if [ $2 = "long" ] ; then
  SWITCH_SEC=10
fi

if [ $2 = "short" ] ; then
  SWITCH_SEC=0.5
fi

if [ $SWITCH_SEC = "UNDEFINED" ] ; then
  echo Parameter error: long or short expected
  exit 16
fi

echo Using GPIO ${GPIO_NO}

echo Writing GPIO ${GPIO_NO} === ON === 
echo ${GPIO_NO} > /sys/class/gpio/export
sleep ${WAIT_GPIO}
echo out > /sys/class/gpio/gpio${GPIO_NO}/direction
sleep ${SWITCH_SEC}

echo Writing GPIO ${GPIO_NO} === OFF === 
echo ${GPIO_NO} > /sys/class/gpio/unexport
sleep ${WAIT_GPIO}
