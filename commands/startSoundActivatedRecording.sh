export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

while : 
do
	today=$(date '+%F-%H-%M-%S') 
	rec -c 2 -r 192000 $today.wav sinc 10k silence 1 0.1 1% 1 4.0 1% & wait > log.txt
	sleep 0.1s
done
