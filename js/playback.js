//********* Define our variables *********** \\

var getVariables = getUrlVars();
var speakerStatus = getVariables['status'] == undefined ? "BatPi'sSpeaker" : getVariables['status']
var fileName = getVariables['fileName'];

//********* Define our functions *********** \\

//
// Checks is the audiofile has already been time-expanded. If so play it will play it if not it runs its errorCallback
//
function playAudio(audiofile, errorCallback){
  $.ajax({
      url:'time-expansion-audio/' + audiofile,
      type:'HEAD',
      error: function(){
          setInterval(playAudio, 1000, audiofile);
          errorCallback;
      },
      success: function(){
        audio = new Audio("time-expansion-audio/" + audiofile);
        audio.play();
      }
  });
}

//
// We need to ensure the user cannot click on live when we are playing to their speakers
//
function setLiveAvailability(speakerStatus){
  if(speakerStatus == "Internal Speakers"){
    $("#live-audio").addClass("unavailable");
  }else{
    $("#live-audio").removeClass("unavailable");
  }
}

//********* Setup and run the page *********** \\

$(document).ready(function(){

  addPageButtons();

  if(fileName != ""){
    //We need to add the selected attribute to the audio-file name on the right-bar
    $(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
    $(".stop-button").css("display", "block");
    //We need to retain if it is internal or external speakers
    if(speakerStatus == "Internal Speakers"){
      $("#speaker-status").html("Current: Internal Speakers");
      isLiveAvailable()
      //External playback is handled by bash but internal javascript does
      playAudio(fileName, function(){
        setInterval(playAudio, 1000, audiofile);
      });
    }
  }

  //
  // Inform the user of the status, change the status for the other functions, and finally check if we should allow live playback.
  //
  $(".img-button").click(function(event){
    $("#speaker-status").html("Current: " + $(event.target).attr('value'));
    speakerStatus = $(event.target).attr('value');
    isLiveAvailable();
  });

  //
  // When the user clicks on a audiofile.
  //
  $(".audiofile").click(function(event){
    source = $(event.target)[0].innerHTML;
    if(speakerStatus == 'Internal Speakers'){
      playAudio(source, function(){
         window.location="?f=" + source + "&status=Internal Speakers";
      });
    }else{
      source = (source == "Live" & document.getElementById('amplify').checked) ? "Live-Amplify" : source;
      window.location = "?f=" + source + "&status=BatPisSpeaker";
    }
  });

})
