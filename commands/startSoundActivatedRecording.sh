while :
do
	today=$(date '+%F-%H-%M-%S')
	curl -d "date_recorded=$today&url=$today.wav" -X POST https://localhost/endpoints/calls.php
	sudo rec -q -c 2 -r 192000 audiofiles/$today.wav sinc 10k silence 1 0.1 0.125% 1 2.0 0.125% & wait > log.txt
	sleep 0.1s
done
