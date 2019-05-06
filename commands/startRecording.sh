today=$(date '+%F-%H-%M-%S')
curl -k -d "date_recorded=$today&url=$today.wav" -X POST https://localhost/endpoints/calls.php
rec -c 2 -r 192000 audiofiles/$today.wav sinc 10k & > log.txt
