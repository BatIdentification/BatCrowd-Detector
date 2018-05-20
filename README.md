# Bat-Pi
A multi-functional and multi-use bat detector. The Bat-Pi aims to reduce the costs incurred to monitor and identify bat species while not significantly comprimsing on quality when compared to commerical bat detectors.
<br>

<h2>Features</h2>
<ul>
  <li>Ultrasonic recording</li>
  <li>Sound-activated recording</li>
  <li>Spectogram display</li>
  <li>Time Expansion Playback</li>
</ul>
  
These files are all time-stamped without the use of a RTC(Real-time clock) module by having client devices send the time to the BatPi

<h2>Setting up</h2>

The code included in this repository is for the Bat-Pi's web-interface. To get started with the BatPi follow these steps:

<h4> Installing Apache2 and PHP </h4>

First install apache2 by performing
```bash
sudo apt-get install apache2 -y
```

Then install php
```bash
sudo apt-get install php libapache2-mod-php -y
```

<h4>Setting up the BatPi's own network</h4>

Follow this tutorial to make the BatPi setup its own wifi access point 

https://learn.adafruit.com/setting-up-a-raspberry-pi-as-a-wifi-access-point/

<h4> Clone BatPi </h4>

Change your working directory to <b> /var/www/ </b> and remove all the files
```bash
cd /var/www; rm *;
```

Clone the BatPi into here

```bash
git clone https://github.com/richardbeattie/Bat-Pi.git
``` 

<h4>The future</h4>

<ul>
  <li>Intergrating the BatPi with the BatIdentification Project</li>
  <li>Improving the speed of the web-interface</li>
  <li>Having a combined spectrogram and time-expansion viewer</li>
  <li>Have the BatPi do the spectrograms and Time Expansion in advance of wanting a file to be played</li>
</ul>





