<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script language="javascript" type="text/javascript" src="http://dev-infra.fivethirty.co/js/head.js"></script>
<title>upload</title>
</head>
<body>

<table width='100%' border=1 cellspacing=0 cellpadding=5>
  <tr><td><b>연락처</b></td><td><input type=text id='contact' size=30  maxlength=50 autocomplete="off"></td></tr>
  <tr><td><b>제목</b></td><td><input type=text id='title' size=30  maxlength=50 autocomplete="off"></td></tr>
  <tr><td><b>내용</b></td><td><textarea id='contents' cols=30 rows=10 autocomplete="off"></textarea></td></tr>
  <tr><td colspan="2"><input type=button value="등록" onclick="writeCheck();"></td></tr>
</table>

<script>
function writeCheck(){
	if(!$('#contact').val()){alert("연락처를 입력하세요"); return;}
	if(!$('#title').val()){alert("제목을 입력하세요"); return;}
	if(!$('#contents').val()){alert("내용을 입력하세요"); return;}

	var data = new Object();
	data.title = $('#title').val();
	data.contents = $('#contents').val();
	data.contact = $('#contact').val();
	data.userData = '<?php bs::out( bs::data('userData') ); ?>';
	data.prefix = '<?php bs::out( bs::data('prefix') ); ?>';
	data.appVer = '<?php bs::out( bs::data('appVer') ); ?>';
	data.osVer = '<?php bs::out( bs::data('osVer') ); ?>';
	data.platform = '<?php bs::out( bs::data('platform') ); ?>';

	sendRequest('http://dev-infra.fivethirty.co/saveReport', data, function(result){
     	if(result){
     		alert('정상처리 되었습니다.');
     		$('#contact').val('');
     		$('#title').val('');
     		$('#contents').val('');
     		location.href = '<?php bs::out( bs::data('appSchema') ); ?>'+'://';

 		}else alert('네트워크 에러입니다. 다시시도해 보세요. 문제가 계속된다면 관리자에게 문의하세요');
    });
}
</script>

</body>
</html>