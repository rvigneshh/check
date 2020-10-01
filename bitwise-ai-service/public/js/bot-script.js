(function( $ ) {

// Created by : Alok Rajasukumaran
// Created on : 06-03-2018
// Updated on : 11-07-2018
// Version    : 1.15

//Before everything is ready
$(document).ready(function () {

console.log("ID:", userUniqueID);


function popup(mylink, windowname) {

  console.log("popup called!", mylink, windowname);

  if (!window.focus) {
    return true;
  }
  var href;
  // var left = (screen.width / 2) - (350 / 2);
  // var top = (screen.height / 2) - (600 / 2);
  var left = 0;
  var top = 0;

  if (typeof (mylink) == 'string') {
    href = mylink;
  } else {
    href = mylink.href;
  }
  console.log("Link analytics", LinkAnalytics);
  if (LinkAnalytics) {
    linkClicked(href);
  }
  window.open(href, windowname,
    'width=350,height=600,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no,directories=no,resizable=no,top=' +
    top + ',left=' + left);
  return false;
}



function linkClicked(href, callback) {

  console.log(Date.now());

  var endpoint = ServerUrl + "linkclicked/";

  console.log(endpoint);

  var Data = {
    "url": endpoint,
    "data": {
      "userid": userId,
      "conversationid": conversationID,
      "botId": botId,
      "urlClicked": href,
      "usercred": userUniqueID,
      "platform": "webchat"
    },
    "type": "POST"
  };

  console.log("Packed data is,", Data);

  var post = postToServer(Data, function (reply, response) {

    console.log(reply, response.response);

    if (reply) {

      console.log(response.response);

      console.log(Date.now());

      // callback(reply,response.response);
    } else {

      console.log("reply false", response);

      // callback(reply,response.response);
    }
  });
}

function postToServer(Data, callback) {

  console.log(Data);

  console.log(Date.now());


  function callTheApi() {
    var jqxhr = $.ajax({
        cache: false,
        url: Data.url,
        type: Data.type,
        data: Data.data
      })
      .done(function (response) {
        // success logic here

        console.log("Api call done with response, ", response);

        console.log(JSON.stringify(response));

        var json = JSON.parse(response.message);
        if (json.LinkAnalytics) {
          LinkAnalytics = true;
        }

        callback(json.reply, json);
      })
      .fail(function (jqXHR, exception) {
        var msg = '';

        console.log("in fail"); // call error retry here

        if (jqXHR.status === 0) {
          msg = 'Not connect.\n Verify Network.';
          console.log("Not connected!");
          //halt!
          callback(false, "No internt connection. " + jqXHR.status);
        } else if (jqXHR.status == 404) {
          msg = 'Requested page not found. [404]';
          callTheApi();
          // xould be network issue. halt.
        } else if (jqXHR.status == 500) {
          msg = 'Internal Server Error [500].';
          callTheApi();
          //ohooo! danger!
        } else if (exception === 'parsererror') {
          msg = 'Requested JSON parse failed.';
          callTheApi();
        } else if (exception === 'timeout') {
          msg = 'Time out error.';
          callTheApi();
        } else if (exception === 'abort') {
          msg = 'Ajax request aborted.';
          callTheApi();
        } else {
          msg = 'Uncaught Error.\n' + jqXHR.responseText;
          callTheApi();
        }

      })
      .always(function () {

        console.log("complete");

      });
  }
  callTheApi();
}

function playTing() {
  var audio = new Audio('https://gamma.bytesmart.info/live/audio/alert.mp3');
  //audio.play();
}

function clickToSend(whatToSend) {
  sendToBoxAndServer(whatToSend);
}

function generate_message(msg, type, delayed) {
  INDEX++;
  var str = "";
  var img = "";
  if (type == "bot") {
    if (delayed) {
      setTimeout(function () {
        playTing();
      }, 5000);
    } else {
      playTing();
    }
    img = botImage;
    var nonhtmlresponse = $("<div>" + msg + "</div>").text();
    // var sentences = nonhtmlresponse.split('.');
    // var len = sentences.length - 1;
    // var i = 0;

    var speechtext = splitter(nonhtmlresponse, 260);

    // if (len === 0) {
    //   speechtext = speechtext + sentences[0] + ".";
    //   console.log("text is now: ", speechtext);

    // } else if (len === 1) {
    //   speechtext = speechtext + sentences[0] + sentences[1] + ".";
    //   console.log("text is now: ", speechtext);

    // } else {
    //   speechtext = speechtext + sentences[0] + sentences[1] + sentences[2] + ".";
    //   console.log("text is now: ", speechtext);
    // }

    console.log("Speaking", speechtext);



    console.log(img);

    // stopSpeech();
    //speech(speechtext);
  } else {

    img = userImage;

    console.log(img);
    console.log(img, type);

  }
  // var img = (type == "user") ? userImage : botImage;
  str += "<div id='cm-msg-" + INDEX + "' class='chat-msg " + type + "'>";
  str += "          <span class='msg-avatar'>";
  str += "          <img src = '" + img + "'>";
  str += "          <\/span>";
  str += "          <div class='cm-msg-text'>";
  str += msg;
  str += "          <\/div>";
  str += "        <\/div>";
  $(".chat-logs").append(str);
  $("#cm-msg-" + INDEX).hide().fadeIn(300);
  if (type == 'user') {
    $("#chat-input").val('');
    $('#think').show(250);
  } else {
    $('#think').hide(250);
  }
  $(".chat-logs").stop().animate({
    scrollTop: $(".chat-logs")[0].scrollHeight
  }, 1000);
}

function splitter(str, l) {
  if (str.length > l) {
    var strs = str.slice(0, l);
    // console.log("in length: ",strs);
    lastIndex = strs.lastIndexOf('.');
    // console.log("Last value of , is :",lastIndex);
    if (lastIndex != -1) {
      strs = strs.substr(0, lastIndex);
      strs = strs + ".";
    }
    return strs;
  } else {
    return str;
  }
}

function speech(say) {

  console.log("In Speech Method", say);

  // speakOutLoud = true; //when false the speach function doesn't work
  var supportInfo = ('speechSynthesis' in window) ? true : false;

  if (supportInfo && speakOutLoud) {
    // var voices = supportInfo;
    if (speechSynthesis.speaking) {
      speechSynthesis.pause();
      speechSynthesis.cancel();
    }
    console.log("returned : ", supportInfo);

    // var timer = setInterval(function () {
    //     if (voices.length !== 0) {
    //       var utterance = new SpeechSynthesisUtterance();
    //       utterance.voice = speechSynthesis.getVoices().filter(function (voice) {

    //         // 
    //         //   console.log("voice name is: ", voice.name);
    //         // }
    //         return voice.name == VoiceName;
    //       })[0];
    //       utterance.lang = 'en-GB';
    //       utterance.text = say;

    //       
    //         console.log("Speaking!");
    //         console.log("utterance: ", utterance);
    //       }
    //       speechSynthesis.speak(utterance);
    //       // timer = setInterval(function () {
    //       //   if (speechSynthesis.paused) {
    //       //     console.log("#continue")
    //       //     speechSynthesis.resume();
    //       //   }
    //       // }, 100);
    //       // clearInterval(timer);

    //     } else {
    //       
    //         console.log("voice length: ", voices.length);
    //       }
    //     }
    //   },
    //   200);


    var timer = null;
    var reading = false;

    console.log("Can speak?", canSpeak);

    if (!reading && canSpeak) {
      if (timer) {
        clearInterval(timer);
      }
      var msg = new SpeechSynthesisUtterance();
      msg.voice = speechSynthesis.getVoices().filter(function (voice) {

        // 
        //   console.log("voice name is: ", voice.name);
        // }
        return voice.name == VoiceName;
      })[0];
      msg.volume = 1; // 0 to 1
      msg.rate = 1.0; // 0.1 to 10
      msg.pitch = 1; //0 to 2
      msg.text = say;
      msg.lang = 'en-GB';

      msg.onerror = function (e) {
        speechSynthesis.cancel();
        reading = false;
        clearInterval(timer);
      };

      msg.onpause = function (e) {
        console.log('onpause in ' + e.elapsedTime + ' seconds.');
      };

      msg.onend = function (e) {
        console.log('onend in ' + e.elapsedTime + ' seconds.');
        reading = false;
        clearInterval(timer);
      };

      speechSynthesis.onerror = function (e) {
        console.log('speechSynthesis onerror in ' + e.elapsedTime + ' seconds.');
        speechSynthesis.cancel();
        reading = false;
        clearInterval(timer);
      };
      console.log("speech synth is", msg);

      speechSynthesis.speak(msg);

      timer = setInterval(function () {
        if (speechSynthesis.paused) {
          console.log("#continue");
          speechSynthesis.resume();
        }
      }, 100);

      reading = true;

    }

  } else {

    console.log("can speak: " + canSpeak, "Support info: " + supportInfo, "Speak out loud: " + speakOutLoud);

  }
}

function sendMsg(msg, callback) {

  console.log(Date.now());

  var endpoint = ServerUrl + "conversation/";

  console.log(endpoint);


  var Data = {
    "url": endpoint,
    "data": {
      "userid": userId,
      "conversationid": conversationID,
      "botId": botId,
      "usermessage": msg,
      "usercred": userUniqueID,
      "platform": "webchat"
    },
    "type": "POST"
  };

  console.log("Packed data is,", Data);

  var post = new postToServer(Data, function (reply, response) {

    console.log(reply, response.response);

    if (reply) {

      console.log(response.response);
      console.log(Date.now());

      callback(response.response);
    } else {

      console.log("reply false", response);
      callback("I am having some issues connecting to the internet! Please check your internet connection.");

    }
  });
}

function sendToBoxAndServer(msg) {
  generate_message(msg, 'user', false);
  var getMsg = sendMsg(msg, function (reply) {
    generate_message(reply, 'bot', false);
  });
}

//after its all ready

if (!Debug) {
        if (!window.console) {
            window.console = {};
        }
        var methods = ["log", "debug", "warn", "info"];
        for (var i = 0; i < methods.length; i++) {
            console[methods[i]] = function () {};
        }
    }
    console.log("imageurl", imageUrl);
    $('.botIcon').css('background-image', 'url(' + imageUrl + ')');
    $('.botIcon').css('background-repeat', 'no-repeat');
    $('.botIcon').css('background-position', 'center');
    if ($(window).innerWidth() <= 751) {
        $('.botIcon').css('background-size', '120px 120px');
    } else {
        $('.botIcon').css('background-size', '120px 120px');
    }
    $('#chat-title-name').text(botName);

    $('#botImage').attr('src', "https://gamma.bytesmart.info/live/images/" + botName + "bot.jpg");
  loadVoices();
  $('#think').hide();
  $('#botName').text(BotName);

  getChatCredentials(userUniqueID);

  $("#chat-input").keyup(function () {
    bringMic();
  });

  $(document).on('click', '#SingleActionButton', function () {
    //   console.log("help called");
    // clickToSend("Help");
    var functionValue = $(this).data("function-value");
    console.log("Value is:", functionValue);
    if (functionValue.length && functionValue.length > 0) {
      clickToSend(functionValue);
    }
  });

  //Send hi at starting to user

  var msg = welcome_message;
  generate_message(msg, 'bot', true);


  function bringMic() {
    if ($("#chat-input").val().length < 1) {
      $(".chat-submit").html('<i class="material-icons">mic_none</i>');
    } else {
      $(".chat-submit").html('<i class="material-icons">send</i>');
    }
  }



  $("#chat-submit").click(function (e) {
    e.preventDefault();
    var msg = $("#chat-input").val();
    // msg = msg.trim();
    if (msg.trim() == '') {
      startDictation();
      return false;
    }
    sendToBoxAndServer(msg);
  });



  function getChatCredentials(uniqueData) {
    var endpoint = ServerUrl + "getuserid/";

    console.log("user id", uniqueData);

    var Data = {
      "url": endpoint,
      "data": {
        "usercred": uniqueData
      },
      "type": "POST"
    };
    var post = new postToServer(Data, function (reply, response) {

      console.log(reply, response);

      if (reply) {

        console.log(response);

        try {

          console.log(reply);

          if (reply == true) {
            userId = response.userid;
            conversationID = response.conversationid;

            console.log(userId, conversationID);

          } else {

            console.log("get credentials. Json replied false. ");

          }
        } catch (ex) {

          console.log(ex);

        }

      } else {

        console.log("Reply is empty!");

      }
    });

  }



  //text to Speech


  if ('speechSynthesis' in window) {

    // Chrome loads voices asynchronously.

    window.speechSynthesis.onvoiceschanged = function (e) {
      // stopSpeech();
      loadVoices();

    };
  }

  // Fetch the list of voices and populate the voice options.
  function loadVoices() {
    // Fetch the available voices.
    if ('speechSynthesis' in window) {

      var voices = speechSynthesis.getVoices();
      var noOfVoices = voices.length;
      console.log("no of voices", noOfVoices);
      if (voicelist.length > 0) {
        for (var i = 0; i < voices.length; ++i) {
          console.log("voice array:", voices[i].name);

          // Set the voice

          // if (voices[i].name = 'Microsoft Zira Desktop - English (United States)') {
          //   VoiceName = 'Microsoft Zira Desktop - English (United States)';
          //   canSpeak = true;
          //   setSpeech();
          //   break;
          // } else
          // if (voices[i].name = 'Microsoft David Desktop - English (United States)') {
          //   VoiceName = 'Microsoft David Desktop - English (United States)';
          //   canSpeak = true;
          //   setSpeech();
          //   break;
          // } else if (voices[i].name = 'english-us') {
          //   VoiceName = 'english-us';
          //   canSpeak = true;
          //   setSpeech();
          //   break;
          // } else if (voices[i].name = 'Alex') {
          //   VoiceName = "Alex";
          //   canSpeak = true;
          //   setSpeech();
          //   break;
          // } else {
          //   VoiceName = "";
          //   canSpeak = false;
          // }


          voicelist.forEach(element => {
            if (voices[i].name == element) {
              VoiceName = element;
              canSpeak = true;
              setSpeech();
            }
          });


          if (i == voices.length) {

            setSpeech();
          } else {


          }
        }
      } else {
        console.log("voicelistlength", voicelist);
      }
    } else {
      console.log("Voice not Supported");
    }
  }

  function stopSpeech() {
    console.log("stoping speech");
    if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel();
    }
  }

  function resumeSpeech() {
    console.log("Resume speech");
    if ('speechSynthesis' in window) {
      speechSynthesis.resume();
    }
  }

  // function generate_button_message(msg, buttons) {
  //   /* Buttons should be object array
  //     [
  //       {
  //         name: 'Existing User',
  //         value: 'existing'
  //       },
  //       {
  //         name: 'New User',
  //         value: 'new'
  //       }
  //     ]
  //   */
  //   INDEX++;
  //   var btn_obj = buttons.map(function (button) {
  //     return "              <li class='button'><a href='javascript:;' class='btn btn-primary chat-btn' chat-value='" + button.value + "'>" + button.name + "<\/a><\/li>";
  //   }).join('');
  //   var str = "";
  //   str += "<div id='cm-msg-" + INDEX + "' class='chat-msg user'>";
  //   str += "          <span class='msg-avatar'>";
  //   str += "          <img src = '" + userImage + "'>"
  //   str += "          <\/span>";
  //   str += "          <div class='cm-msg-text'>";
  //   str += msg;
  //   str += "          <\/div>";
  //   str += "          <div class='cm-msg-button'>";
  //   str += "            <ul>";
  //   str += btn_obj;
  //   str += "            <\/ul>";
  //   str += "          <\/div>";
  //   str += "        <\/div>";
  //   $(".chat-logs").append(str);
  //   $("#cm-msg-" + INDEX).hide().fadeIn(300);
  //   $(".chat-logs").stop().animate({
  //     scrollTop: $(".chat-logs")[0].scrollHeight
  //   }, 1000);
  //   $("#chat-input").attr("disabled", true);
  // }

  function setSpeech() {

    console.log("set speach function!");


    console.log("speakOutLoud", speakOutLoud);

    if ('speechSynthesis' in window) {

      console.log("in can speak", speakOutLoud, canSpeak);

      if (speakOutLoud && canSpeak) {
        console.log("in if loop");
        var amIPaused = speechSynthesis.paused;
        if (amIPaused) {

          console.log("resuming");

          speechSynthesis.resume();
        }
        $(".chat-speech-toggle").html('<i class="material-icons">volume_up</i>');
      } else {
        console.log("in else loop");
        var speaking = speechSynthesis.speaking;
        if (speaking) {

          console.log("paused");

          speechSynthesis.pause();
          $(".chat-speech-toggle").html('<i class="material-icons">volume_off</i>');
        } else {
          $(".chat-speech-toggle").html('<i class="material-icons">volume_off</i>');
        }
        // $(".chat-speech-toggle").html('<i class="material-icons">volume_off</i>');

      }
    }
  }

  function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

      var recognition = new webkitSpeechRecognition();

      recognition.continuous = false;
      recognition.interimResults = false;

      recognition.lang = "en-US";
      recognition.start();

      recognition.onstart = function () {
        listenForVoice = true;
        setRecognizeSpeechIcon();
      };

      recognition.onresult = function (e) {
        document.getElementById('chat-input').value = e.results[0][0].transcript;
        listenForVoice = false;
        setRecognizeSpeechIcon();
        $('#chat-submit').click();
        recognition.stop();
        // document.getElementById('labnol').submit();
      };
      recognition.onend = function () {
        listenForVoice = false;
        setRecognizeSpeechIcon();
      };
      recognition.onerror = function (e) {
        if (e.error.trim() == "not-allowed") {
          var msg = "ohh! I can't hear you. Can you please allow the mic permission in your device so that I can listen to you!";
          generate_message(msg, 'bot', true);
        }
        listenForVoice = false;
        setRecognizeSpeechIcon();
        recognition.stop();
      }
    } else {
      //no mic supported
    }
  }

  function setRecognizeSpeechIcon() {
    if (listenForVoice) {
      $(".chat-submit").html('<i class="material-icons">mic</i>');
    } else {
      $(".chat-submit").html('<i class="material-icons">mic_none</i>');
    }
  }


  $(document).delegate(".chat-btn", "click", function () {
    var value = $(this).attr("chat-value");
    var name = $(this).html();
    $("#chat-input").attr("disabled", false);
    generate_message(name, 'user', false);
  });

  $("#chat-circle").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
    $('body').css('overflow', 'hidden');
    speakOutLoud = true;
    resumeSpeech(); 
  });

  $(".chat-box-toggle").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
    $('body').css('overflow', 'auto');
    speakOutLoud = false;
    stopSpeech();
  });
  $(".chat-speech-toggle").click(function () {
    speakOutLoud = !speakOutLoud;
    loadVoices();
  });
  setRecognizeSpeechIcon();

});
})( jQuery );
