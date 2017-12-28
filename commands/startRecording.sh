echo "Test" > log2.txt
whoami > log2.txt

export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

today=$(date '+%F-%H-%M-%S')
rec -c 2 -r 192000 $today.wav sinc 10k & > log.txt
