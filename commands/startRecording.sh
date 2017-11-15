#!/bin/bash
today=$(date '+%F-%H:%M:%S')
echo $today.wav >> log.txt
arecord -Dhw:sndrpiwsp -r 192000 -c 2 -f S16_LE $today.wav >> log.txt &
