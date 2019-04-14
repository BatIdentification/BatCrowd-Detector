//********* Define our variables *********** \\

var getVariables = getUrlVars();
var speakerStatus = getVariables['status'] == undefined ? "BatPi'sSpeaker" : getVariables['status']
var fileName = getVariables['fileName'];
var playback = "time-expansion";
var audio;

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
        if(playback == "heterodyne"){
          postData['frequency'] = $("#frequency").val();
        }
        $.post("commands.php", postData, function(data){
          response = JSON.parse(data);
          if(response['success'] == true){
            playAudio(source, playback, function(){});
          }
        });
      });
    }else{
      var postData = {output: "External", source: source}
      postData[playback] = true;
      if(playback == "heterodyne"){
        postData['frequency'] = $("#frequency").val();
      }
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
    $("#stop_action").attr('value', playback);

    if(playback == "heterodyne"){
      $(".frequency-control").show();
    }else{
      $(".frequency-control").hide();
    }

  });

  $("#stop_action").click(function(){
    audio.pause();
    aduio.currentTime = 0;
  });

})
