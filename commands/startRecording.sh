today=$(date '+%F-%H-%M-%S')
sudo rec -c 2 -r 192000 $today.wav sinc 10k & > log.txt
curl -d "date_recorded=$today&url=$today.wav" -X POST http://localhost/endpoints/calls.php
