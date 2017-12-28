export AUDIODEV=hw:0,0
export AUDIODRIVER=alsa

rec -c 2 -r 192000 record.wav sinc 10k silence 1 0.1 1% 1 5.0 1% : newfile : restart & > log.txt
