export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

rm tmp/*.wav

while :
do
	today=$(date '+%F-%H-%M-%S')
	rec -c 2 -r 192000 tmp/$today.wav sinc 10k silence 1 0.1 0.1% 1 4.0 0.1% & wait > log.txt
	sleep 0.1s
done
