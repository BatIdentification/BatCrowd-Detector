today=$(date '+%F-%H-%M-%S')
sudo rec -c 2 -r 192000 $today.wav sinc 10k & > log.txt
