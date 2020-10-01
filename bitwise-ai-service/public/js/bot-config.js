var Debug = "object" == typeof bot_vars ? bot_vars.debug : false,
    botName = "beem",
    BotName = "Beem",
    imageUrl = "https://gamma.bytesmart.info/live/images/" + botName + "boticon.png",
    INDEX = 0,
    ServerUrl = "object" == typeof bot_vars ? bot_vars.host : "https://gamma.bytesmart.info/bitwise/" + botName + "/messages/",
    userImage = "object" == typeof bot_vars ? bot_vars.userImage : "https://gamma.bytesmart.info/live/images/Anonymous_User.jpg",
    botImage = "https://gamma.bytesmart.info/live/images/" + botName + "bot.jpg",
    botId = 1,
    userName = "object" == typeof bot_vars ? bot_vars.username : "somename",
    userUniqueID = "object" == typeof bot_vars ? bot_vars.userUniqueID : "dev.bitwise@gmail.com",
    speakOutLoud = !0,
    listenForVoice = !1,
    VoiceName = "",
    canSpeak = !1,
    userId = "",
    conversationID = "",
    LinkAnalytics = !1,
    chat_icon = "",
    localJs = true,
    welcome_message = "Hey!  I am " + BotName + ", an AI-driven chatbot. I can help you if you have any questions.  <br><div class=\"col-sm text-center\">  <button id='SingleActionButton' type='button' class='btn btn-success btn-success-outline' data-function-value = 'Help'>Help</button> </div>";

var voicelist = ['Microsoft David Desktop - English (United States)', 'Google UK English Male', 'english-us', 'Alex'];
// var voicelist = [];

if (localJs) {
    //document.write("<script type='text/javascript' src='//dev1.bitwise.academy/bitwise/wp-content/plugins/bitwise-ai-service/public/js/" + botName + ".js" + "'><\/scr" + "ipt>");
}
