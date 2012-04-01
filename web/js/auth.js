var type = 0; // 0 - auth , 1 - register

function checkLogin(){
    if(($('#login').val().length > 3)){
        $.ajax({
            type:'POST',
            url:'/Auth/checkLogin/',
            data: "login=" + $('#login').val(),
            success:function (data) {
                var object = $.parseJSON(data);
                if(object.result == true){
                    $('.pwd').show();
                    $('#auth').show();
                    $('.pwd2').hide();
                    $('#form').attr('action','/Auth/login/');
                    $('#auth').val('Login');
                    $('#title').html('Login');
                    type = 0;
                }else{
                    $('.pwd').show();
                    $('#auth').hide();
                    $('.pwd2').show();
                    $('#form').attr('action','/Auth/registerUser/');
                    $('#auth').val('Register');
                    $('#title').html('Register');
                    type = 1;
                }
            }
        });
    }
}

function samePwdAndPwd2(){
    if(type == 1){
        if($('#password').val() == $('#repassword').val()){
            if(($('#password').val().length > 3)){
                $('#auth').show();
            }else{
                $('#auth').hide();
            }
        }else{
            $('#auth').hide();
        }
    }
}

$(window).load(function(){
    $('#auth').hide();
    $('.pwd').hide();
    $('.pwd2').hide();

    $('#login').keyup(function () {
        checkLogin();
    });

    $('#password').keyup(function () {
        samePwdAndPwd2();
    });

    $('#repassword').keyup(function () {
        samePwdAndPwd2();
    });
});