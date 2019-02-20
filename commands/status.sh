if pgrep -f "startSoundActivatedRecording.sh" > /dev/null
then
	echo 1
elif pgrep -x "rec" > /dev/null
then
	echo 2
elif pgrep -f "commands/timeExpansion.sh" > /dev/null
then
	echo 3
fi
