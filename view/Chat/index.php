<?php
/**
 * @var $html string
 * @var $htmlOnlineUsers string
 * @var $user \Auth\User
 */

$socialButtons = "";
if($user->getFacebook() == 0){
    $socialButtons .= "facebook,";
}
if($user->getVk() == 0){
    $socialButtons .= "vkontakte,";
}
if($user->getGoogle() == 0){
    $socialButtons .= "google,";
}
$socialButtons = rtrim($socialButtons,',');

?>
<?php include_once('header.php'); ?>
<script type="text/javascript" src="/js/chat.js"></script>
<body onclick="normalTitle()">
    <table cellpadding="0" cellspacing="0" class="chat_table" border="1" id="chat1">
        <tr class="top">
            <td colspan="2">
                <input type="button" id="allow_desktop_notification" value="Allow Desktop Notifications" />
                <? if($socialButtons): ?>
                <script src="http://ulogin.ru/js/ulogin.js"></script>
                <div id="uLogin" x-ulogin-params="display=small&fields=first_name,last_name&providers=<?= $socialButtons ?>&hidden=&redirect_uri=http%3A%2F%2F91.232.0.132%2FAuth%2FsocialAdd"></div>
                <? endif; ?>
                <a class="fr" href="/Auth/logout">Logout</a>
                <span>
                    Connected with:
                    <? if($user->getFacebook()): ?>Facebook<? endif; ?>
                    <? if($user->getVk()): ?>VK<? endif; ?>
                    <? if($user->getGoogle()): ?>Google<? endif; ?>
                    <?
                    $username = $user->getUsername();
                    if(!empty($username)): ?>
                    <br />Your name : <?= $user->getUsername(); ?>, but I use your nickname
                    <? endif; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td class="chat">
                <div id="chat" style="height: 100%; max-width: 1020px; overflow: auto; bottom: 0; position: relative;">
                    <?= $html ?>
                </div>
            </td>
            <td class="user_list" id="users_list">
                <?= $htmlOnlineUsers ?>
            </td>
        </tr>
        <tr class="bottom">
            <td colspan="2">
                <table class="bottom_tbl" border="1">
                    <tr>
                        <td id="chat_name" style="text-align:center;">
                            <select id="message_type">
                                <option value="0" selected="selected">Group</option>
                                <option value="1">Private</option>
                            </select>
                            <a id="removeToUser" href="javascript:removeToUser();" style="display: none;">x</a>
                            <span id="username"></span>
                        </td>
                        <td>
                            <input type="hidden" id="user_to" value="0" />
                            <input type="text" id="send_message" onclick="javascript:void(0)" class="fl" />
                        </td>
                        <td id="button">
                            <input type="button" value="send" id="send_button" class="fr" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>