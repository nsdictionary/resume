<html>
<head>
<meta charset="utf-8">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<title>tool list</title>
</head>
<body>

<div id="toolList" style="display:none">
<p style="color:green">Tool List -------------------------------------------------------------------</p>
<a href="http://211.170.115.63:8002/bs/index.php?/pushView">1.전체푸시 발송 툴</a><br />
<a href="http://dev-infra.fivethirty.co/uploadImageNotice">2.이미지 공지 툴</a><br />
<a href="http://dev-infra.fivethirty.co/uploadReport?appId=LF-World&appVer=1.0.0&osVer=osversion&appSchema=test&uData={%22name%22%3A%22kota%22%2C%22score%22%3A%22100%22}%22kota%22%2C%22score%22%3A%22100%22}">3.통합 에러 보고 툴(클라이언트 용, 링크는 테스트)</a><br />
<a href="http://211.170.115.63:5002/ft/">4.통합 언어팩 파싱 툴</a><br />
<p style="color:green">-----------------------------------------------------------------------------</p>
</div>

<script>
var isLogin = '<?php bs::out( bs::data('login') ); ?>';
if(isLogin) $('#toolList').removeAttr('style');
</script>

</body>
</html>