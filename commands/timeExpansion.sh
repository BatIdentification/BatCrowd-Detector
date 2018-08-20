amixer sset 'Speaker Digital' 88%

if [ $1 = "Live" ]; then
	( arecord -Dhw:sndrpiwsp -r 192000 -c 2 -f S16_LE | sox -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdin -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdout sinc 10k speed 0.1 | aplay -D hw:sndrpiwsp -) &> /dev/null
elif [ $1 = "Live-Amplify" ]; then
	( sox -v 6 -b16 -r 192000 -c 2 -t alsa hw:sndrpiwsp  -r 192000 -t alsa hw:sndrpiwsp sinc 10k speed 0.1 ) &> /dev/null
elif [ -f "time-expansion-audio/$1" ]; then
	(aplay -q "time-expansion-audio/$1") &
else
	(sox -t wav -e signed-integer -b16 -r 192000 -c 2 audio-files/$1 -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdout sinc 10k speed 0.1 gain -n -5 |  aplay -D hw:sndrpiwsp -) &> /dev/null
fi
