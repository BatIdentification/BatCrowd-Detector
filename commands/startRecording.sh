export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

today=$(date '+%F-%H-%M-%S')
rec -c 2 -r 192000 audiofiles/$today.wav sinc 10k & > log.txt
