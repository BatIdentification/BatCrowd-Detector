export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

today=$(date '+%F-%H-%M-%S')
curl -d "date_recorded=$today&url=$today.wav" -X POST https://batpi.loc/endpoints/calls.php
rec -c 2 -r 192000 audiofiles/$today.wav sinc 10k & > log.txt
