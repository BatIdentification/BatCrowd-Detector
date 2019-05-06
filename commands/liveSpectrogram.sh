#!/bin/bash

SECONDS=0
while [[ SECONDS -lt 300 ]]; do
	today=$(date '+%F-%H-%M-%S')
	arecord -r 192000 -c 2 -f S16_LE -d4 /var/www/html/audiofiles/liveSpec.wav
	sox /var/www/html/audiofiles/liveSpec.wav -n remix 1 rate 192k spectrogram -o /var/www/html/spec.png sinc 10k
	cp /var/www/html/audiofiles/liveSpec.wav /var/www/html/audiofiles/$today.wav
	curl -d "date_recorded=$today&url=$today.wav" -X POST https://localhost/endpoints/calls.php
	cp /var/www/html/spec.png /var/www/html/spectrogram-images/$today.png
	let "SECONDS++"
done
