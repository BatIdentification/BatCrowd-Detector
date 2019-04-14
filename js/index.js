  var num_of_calls = 0;
  var count_interval;
  var responses = {1: "Sound-activated recording", 2: "Recording", 3: "Time-expansion", 4: "Hetrodyning"}
  var currentStatus = 0;

  function stopCurrent(){

    $("#detector_status").hide();

    if(currentStatus == 1){
      $.post("commands.php", {sound_activated: false});
      clearInterval(count_interval);
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

  function displayStatus(status){

    $("sa_recorded").hide();
    $("#detector_status").show();
    $("#detector_status_type").text("Current: " + status);

  }

  function getDetectorStatus(){

    $.get("endpoint.php", {status: 0}, function(status){

      var response = jQuery.parseJSON(status);
      currentStatus = response['status'];

      if(response['status'] != 0){

        displayStatus(responses[response['status']]);

        if(response['status'] == 1){
            $("sa_recorded").show();
            count_interval = setInterval(updateCallsRecorded, 4000);
        }

      }

    })

  }

  function setupLocation(){

    $.get("endpoints/location.php", {gps_status: 0}, function(status){

      var response = jQuery.parseJSON(status);
      if(response['gps_status'] == 0){
        clientLocation();
      }else if(response['gps_status'] == 1){
        $("#gps_status").removeClass("greyed");
        setTimeout(setupLocation, 10000);
      }else if(response['gps_status'] == 2){
        clientLocation(false);
        setTimeout(setupLocation, 10000);
      }

    })

  }
  
  function clientLocation(repeat = true){

    navigator.geolocation.getCurrentPosition(
      function(position){
        $("#gps_status").removeClass("greyed");
        $.post("endpoints/location.php", {lat: position.coords.latitude, lng: position.coords.longitude});
        if(repeat){
          setTimeout(clientLocation, 5 * 60 * 1000);
        }
      },
      function(error){
        $("#gps_status").addClass("greyed");
        switch (error.code){
          case error.TIMEOUT:
            alert("Sorry, there were an issue while getting your location");
            break;
          case error.PERMISSION_DENIED:
            //The browser is blocking us since we are not using https
            alert("Sorry, due to restrictions with Chrome we are unable to detect your location.");
            break;
          case error.POSITION_UNAVAILABLE:
            alert("Sorry, your position is currently unavailable");
            break;
        }
      }
    );

  }

  $(document).ready(function(){
    //Setup the page
    tellBatPiTime();
    addPageButtons();
    getDetectorStatus();
    setupLocation();
    //Start and stop soundactivated recording
    $(".sound_activated_button").click(function(){
      $.post("commands.php", {sound_activated: $(this).val()});
      if($(this).val() == "true"){
        displayStatus("Sound-activated recording");
        $("#sa_recorded").text("0");
        $("sa_recorded").show();
        count_interval = setInterval(updateCallsRecorded, 4000);
        currentStatus = 1;
      }else{
        num_of_calls = 0;
        $("#detector_status").hide();
        clearInterval(count_interval);
      }
    })
    //Start and stop normal recording
    $(".recording_button").click(function(){

      $.post("commands.php", {recording: $(this).val()});

      if($(this).val() == "true"){
        displayStatus("Recording");
        currentStatus = 2;
      }else{
          $("#detector_status").hide();
      }

    })
    //Setup the status stop button
    $("#detector_status_stop").click(function(){
      stopCurrent();
    });

  })
