#!/bin/bash
today=$(date '+%F-%H-%M-%S')
echo $today.wav >> log.txt
rec -c1 -r 192000 $today.wav sinc 10k >> log.txt &
