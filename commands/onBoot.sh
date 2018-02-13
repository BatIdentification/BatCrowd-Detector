#Setup the microphone and speaker
/home/pi/use_case_scripts/Record_from_Headset.sh
/home/pi/use_case_scripts/Playback_to_Speakers.sh

export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

sudo cp /etc/network/access-point-interfaces /etc/network/interfaces;
sudo ifdown wlan0;
sudo ifup wlan0;
