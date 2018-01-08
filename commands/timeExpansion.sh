amixer sset 'Speaker Digital' 100%

if [ $1 = "Live" ]; then
	( arecord -Dhw:sndrpiwsp -r 192000 -c 2 -f S16_LE | sox -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdin -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdout sinc 10k speed 0.1 | aplay -D hw:sndrpiwsp -) &> /dev/null	
elif [ -f "time-expansion-audio/$1" ]; then
	(aplay -q "time-expansion-audio/$1") &
else
	(sox -t wav -e signed-integer -b16 -r 192000 -c 2 $1 -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdout sinc 10k speed 0.1 |  aplay -D hw:sndrpiwsp -) &> /dev/null
fi
