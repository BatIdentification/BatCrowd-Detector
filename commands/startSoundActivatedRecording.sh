while :
do
	today=$(date '+%F-%H-%M-%S')
	sudo rec -q -c 2 -r 192000 audiofiles/$today.wav sinc 10k silence 1 0.1 0.125% 1 2.0 0.125% & wait > log.txt
	sleep 0.1s
done
