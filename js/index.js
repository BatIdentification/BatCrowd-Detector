  var num_of_calls = 0;
  var count_interval;
  var responses = {1: "Sound-activated recording", 2: "Recording", 3: "Time-expansion", 4: "Hetrodyning"}
  var currentStatus = 0;

  function stopCurrent(){

    if(currentStatus == 1){
      $.post("commands.php", {sound_activated: false});
    }else if(currentStatus == 2){
      $.post("commands.php", {recording: false});
    }else if(currentStatus == 3){
      $.post("commands.php", {timeExpansion: false});
    }else if(currentStatus == 4){
      $.post("commands.php", {hetrodyning: false});
    }

  }

  function updateCallsRecorded(){

    $.post("endpoint.php", {num_calls: true}, function(data){

      var response = jQuery.parseJSON(data);

      num_of_calls = num_of_calls == 0 ? response['num_calls'] : num_of_calls;

      $("#sa_recorded").text(response['num_calls'] - num_of_calls);

      console.log(response['num_calls']);

    });

  }

  function getDetectorStatus(){

    $.get("endpoint.php", {status: 0}, function(status){

      var response = jQuery.parseJSON(status);
      currentStatus = response['status'];

      if(response['status'] != 0){

        $("#detector_status").show();
        $("#detector_status_type").text("Current: " + responses[response['status']]);

        if(response['status'] == 1){
            count_interval = setInterval(updateCallsRecorded, 4000);
        }

      }

    })

  }

  $(document).ready(function(){
    //Setup the page
    tellBatPiTime();
    addPageButtons();
    getDetectorStatus();
    //Start and stop soundactivated recording
    $(".sound_activated_button").click(function(){
      $.post("commands.php", {sound_activated: $(this).val()});
      if($(this).val() == "true"){
        $("#detetector_status").show();
        $("#detector_status_type").text("Current: Sound-activated recording");
        count_interval = setInterval(updateCallsRecorded, 4000);
      }else{
        num_of_calls = 0;
        $("#detetector_status").hide();
        clearInterval(count_interval);
        $("#sa_recorded").text("0");
      }
    })
    //Start and stop normal recording
    $(".recording_button").click(function(){
      $.post("commands.php", {recording: $(this).val()});
    })
    //Setup the status stop button
    $("#detector_status_stop").click(function(){
      stopCurrent();
    });
  })