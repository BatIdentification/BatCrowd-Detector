#!/bin/bash

SECONDS=0
while [[ SECONDS -lt 300 ]]; do
	today=$(date '+%F-%H-%M-%S')
	arecord -Dhw:sndrpiwsp -r 192000 -c 2 -f S16_LE -d4 /var/www/audiofiles/liveSpec.wav & wait
	sox /var/www/audiofiles/liveSpec.wav -n remix 1 rate 192k spectrogram -o /var/www/spec.png sinc 10k & wait
	cp /var/www/audiofiles/liveSpec.wav /var/www/$today.wav
	cp /var/www/spec.png /var/www/spectrogram-images/$today.png
done
