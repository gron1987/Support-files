<?php include('header.php'); ?>
<script type="text/javascript" src="/js/auth.js"></script>
<body class="auth">
    <? if(!empty($error)): ?>
    <span class="error_box"><?= $error ?></span>
    <div class="clear10"></div>
    <? endif; ?>
    <form id="form" action="/Auth/login/" method="POST">
        <div id="content">
            <center id="title">
                Auth
            </center>
            <div class="clear10"></div>
            <label>Login: </label>
            <input type="text" name="login" id="login" />
            <div class="clear10"></div>
            <label class="pwd">Password: </label>
            <input class="pwd" type="password" name="pass" id="password"/>
            <div class="clear10 pwd"></div>
            <label class="pwd2">Re password: </label>
            <input class="pwd2" type="password" name="repass" id="repassword"/>
            <div class="clear10 pwd2"></div>
            <center>
                <input id="auth" type="submit" value="Auth" />
            </center>
            <br />
            <center>
                You may login by social network only when you register and connect them to your account.
                <script src="http://ulogin.ru/js/ulogin.js"></script>
                <div id="uLogin" x-ulogin-params="display=small&fields=first_name,last_name&providers=vkontakte,google,facebook&hidden=&redirect_uri=http%3A%2F%2F91.232.0.132%2FAuth%2FsocialAuth%2F"></div>
            </center>
        </div>
    </form>
</body>
