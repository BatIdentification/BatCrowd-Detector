#! /bin/bash

case "$(pidof hostapd | wc -w)" in

0) wpa_cli -i wlan0 disconnect;
   sudo cp /etc/network/access-point-interfaces /etc/network/interfaces;
   sudo ifdown wlan0;
   sudo ifup wlan0;
   sudo service hostapd start;
   sudo service isc-dhcp-server start;
   ;;
1) sudo service hostapd stop;
   sudo service isc-dhcp-server stop;
   sudo cp /etc/network/client-mode-interfaces /etc/network/interfaces;
   sudo ifdown wlan0;
   sudo ifup wlan0;
   wpa_cli -i wlan0 reconnect;
   wpa_cli -i wlan0 reconfigure;
   ;;
esac

