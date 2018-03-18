sleep 10;

#Setup the microphone and speaker
/home/pi/use_case_scripts/Record_from_Headset.sh
/home/pi/use_case_scripts/Playback_to_Speakers.sh

#Tell it where to record from
export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

