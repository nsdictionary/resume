<html>
<head>
<meta charset="utf-8">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script language="javascript" type="text/javascript" src="http://dev-infra.fivethirty.co/js/head.js"></script>
<script language="javascript" type="text/javascript" src="http://dev-infra.fivethirty.co/js/md5.js"></script>

<title>login</title>
</head>
<body>
<?php bs::out( bs::view( 'toolList', FALSE ) ); ?>

<div id='login'>
<p>Username: <input type="text" id="username"/></p>
<p>Password: <input type="password" id="password"/></p>
<br/>
<button onclick="onLogin();" id="btn_login">Login</button>
</div>

<script>
function onLogin(){
	var userName = $("#username").val();
	var password = $("#password").val();
	if( !isNotNull(userName) ){alert('회원 이름을 입력하세요'); return;}
	if( !isNotNull(password) ){alert('패스워드를 입력하세요'); return;}

	var data = new Object;
	data.userName = userName;
	data.password = hex_md5(password);

	sendRequest('http://dev-infra.fivethirty.co/login', data, function(result){
		if(result) {
			console.log('로그인 성공 해당 페이지로 이동', result);
			$('#toolList').removeAttr('style');
			$('#login').attr( 'style', 'display:none' );
		} else {
			console.log('로그인 실패', result);
			alert('이름 또는 패스워드가 일치하지 않습니다.');
		}
	});
}
</script>
</body>
</html>