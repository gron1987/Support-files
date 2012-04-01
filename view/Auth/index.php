<?php include('header.php'); ?>
<script type="text/javascript" src="/js/auth.js"></script>
<body class="auth">
    <? if(!empty($error)): ?>
    <span class="error_box"><?= $error ?></span>
    <div class="clear10"></div>
    <? endif; ?>
    <form action="/Auth/login/" method="POST">
        <div id="content">
            <label>Login: </label>
            <input type="text" name="login"/>
            <div class="clear10"></div>
            <label>Password: </label>
            <input type="password" name="pass"/>
            <div class="clear10"></div>
            <center>
                <input type="submit" value="Auth" />
            </center>
        </div>
    </form>
</body>
