#!/bin/bash

currentState=$(cat "/var/www/commands/variables/wifiState.txt")

if [ $currentState = "access-point" ] 
then
sudo service hostapd start;
sudo service isc-dhcp-server start;
fi

#wpa_cli -i wlan0 disconnect;
#sudo cp /etc/network/access-point-interfaces /etc/network/interfaces;
#sudo ifdown wlan0;
#sudo ifup wlan0;
#sleep 5;
#sudo service hostapd start;
#sudo service isc-dhcp-server start;
