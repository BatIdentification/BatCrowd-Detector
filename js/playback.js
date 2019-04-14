//********* Define our variables *********** \\

var getVariables = getUrlVars();
var speakerStatus = getVariables['status'] == undefined ? "BatPi'sSpeaker" : getVariables['status']
var fileName = getVariables['fileName'];
var playback = "time-expansion";

//********* Define our functions *********** \\

//
// Checks is the audiofile has already been time-expanded. If so play it will play it if not it runs its errorCallback
//
function playAudio(audiofile, playbackType, errorCallback){
  $.ajax({
      url:playbackType + '-audio/' + audiofile,
      type:'HEAD',
      error: function(){
          errorCallback();
      },
      success: function(){
        audio = new Audio(playbackType + "-audio/" + audiofile);
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
      setLiveAvailability(speakerStatus)
      //External playback is handled by bash but internal javascript will play
      playAudio(fileName, playback, function(){
        //If the audiofile has not been converted yet we need to keep testing until it has been
        setInterval(playAudio, 1000, audiofile, playback, function(){});
      });
    }
  }

  //
  // Inform the user of the status, change the status for the other functions, and finally check if we should allow live playback.
  //
  $(".img-button").click(function(event){
    $("#speaker-status").html("Current: " + $(event.target).attr('value'));
    speakerStatus = $(event.target).attr('value');
    setLiveAvailability(speakerStatus);
  });

  //
  // When the user clicks on a audiofile.
  //
  $(".audiofile").click(function(event){
    source = $(event.target)[0].innerHTML;
    if(speakerStatus == 'Internal Speakers'){
      playAudio(source, playback, function(){
        var postData = {output: "Internal", source: source}
        postData[playback] = true;
        $.post("commands.php", postData);
      });
    }else{
      var postData = {output: "External", source: source}
      postData[playback] = true;
      $.post("commands.php", postData);
    }
  });

  //
  // Switch which form of playback
  //
  $(".header-option").click(function(){

    playback = $(this).attr("value");
    $(".header-option").removeClass("header-option-active");
    $(this).addClass("header-option-active");

  });

})
