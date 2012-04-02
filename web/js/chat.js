var unSendedMessages = [];
var unreadedMessagesCount = 0;
var unreadedPrivateMessagesCount = 0;
var message = '';

function normalTitle() {
    document.title = "Chat";
    unreadedMessagesCount = 0;
    unreadedPrivateMessagesCount = 0;
}

function removeToUser() {
    $('#removeToUser').hide();
    $('#username').html('');
    $('#user_to').val('0');
}

function toUser(id, login) {
    $('#removeToUser').show();
    $('#username').html(login);
    $('#user_to').val(id);
}

function sendUnSendedMessages() {
    if (unSendedMessages.length > 0) {
        $.each(unSendedMessages, function (key, postData) {
            $.ajax({
                type:'POST',
                url:'/Chat/addMessage/',
                data:postData,
                success:function (data) {
                    $('#send_message').val('');
                    unSendedMessages.shift();
                }
            });
        });
    }
}

function sendMessage() {
    var postData = "message=" + $('#send_message').val();
    if (parseInt($('#user_to').val()) > 0) {
        postData += "&id_user=" + parseInt($('#user_to').val());
    }
    if (($('#message_type').val() == 1) && (parseInt($('#user_to').val()) < 1)) {
        alert('Private message may only be sended to some users');
    } else {
        postData += "&private=" + $('#message_type').val();
        message = $('#send_message').val();
        $('#send_message').val('');
        $.ajax({
            type:'POST',
            url:'/Chat/addMessage/',
            data:postData,
            success:function (data) {
                if(data != ""){
                    var object = $.parseJSON(data);
                    alert(object.error);
                    $('#send_message').val(message);
                }else{
                    $('#send_message').val('');
                    sendUnSendedMessages();
                }
            },
            error:function () {
                unSendedMessages.push(postData);
                $('#send_message').val('');
            }
        });
    }
}

function unreadedMessages() {
    $.ajax({
        type:'GET',
        url:'/Chat/getNewData/',
        success:function (data, status) {
            var object = $.parseJSON(data);
            $.each(object.chat,function(key,obj){
                if($('#' + key).length > 0){
                    $('#' + key).append(obj.html);
                }else{
                    $('#chats_list').append('<a href="javascript:' + obj.js + '" id="select_' + key + '">' + key + '</a>');
                    $('#chat_zone').append('<div id="' + key + '" class="chat_screen" style="display: none;">' + obj.html + '</div>');
                }
            });
            if (object.notification.totalCount > 0) {
                // desktop notification show only if had private messages
                if(object.notification.privateCount > 0){
                    var message = "Total unread messages: " + object.notification.totalCount + "\r\n" +
                        "Private messages: " + object.notification.privateCount;
                    window.webkitNotifications.createNotification("", "New messages",message).show();
                }
                unreadedMessagesCount += object.notification.totalCount;
                unreadedPrivateMessagesCount += object.notification.privateCount;
                document.title = "Chat (" + unreadedMessagesCount + ")";
            }
            setTimeout(function () {
                $(".chat_screen").attr({ scrollTop:$(".chat_screen").attr("scrollHeight") });
            }, 100);
            updateActivity();
        }
    });
}

function updateUsers(){
    $.ajax({
        type:'GET',
        url:'/Chat/getUserList/',
        success:function (data, status) {
            var object = $.parseJSON(data);
            if (object.users != "") {
                $('#users_list').html(object.users);
            }
            updateActivity();
        }
    });
}

function changeChat(id,userid,login){
    $(".chat_screen").hide();
    $("#"+id).show();
    $('#username').html(login);
    $('#user_to').val(userid);
}

function updateActivity() {
    $.ajax({
        type:'POST',
        url:'/Auth/updateActivity/'
    });
}

$(window).load(function () {
    $('#allow_desktop_notification').live("click", function () {
        window.webkitNotifications.requestPermission();
    });

    $('#send_message').keydown(function () {
        if(unreadedMessagesCount > 0){
            normalTitle();
        }
        if (event.keyCode == 13) {
            sendMessage();
        }
    });
    $('#send_button').live("click", function () {
        sendMessage();
    });
    $(".chat_screen").attr({ scrollTop:$(".chat_screen").attr("scrollHeight") });

    $(".chat_screen").hide();
    $(".chat_screen:first").show();
    setInterval(unreadedMessages, 3000);
    setInterval(updateUsers,30000);
});