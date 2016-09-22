<html>
<head>
<meta charset="utf-8">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<!-- <script language="javascript" type="text/javascript" src="http://dev-infra.fivethirty.co/js/datetimepicker.js"></script> -->
<script language="javascript" type="text/javascript" src="http://dev-infra.fivethirty.co/js/head.js"></script>

<title>upload</title>
</head>
<body autocomplete="off">

<h4>앱 네임스페이스 선택</h4>
<table border="1">
<?php
	$prefixList = bs::data('prefixList');
	if( $prefixList ){
		$ischecked = FALSE;
		foreach( $prefixList as $v ){
			bs::out('<tr>','<td>'.$v[0].'-iOS</td>');
			if(!$ischecked) {
				bs::out('<td><input autocomplete="off" type="radio" name="chkinfo" onclick="appPrefix=\''.$v[0].'-iOS\';" checked="checked"></td>');
				$ischecked = TRUE;
				bs::data('firstPrefix', $v[0].'-iOS');
			}else{
				bs::out('<td><input autocomplete="off" type="radio" name="chkinfo" onclick="appPrefix=\''.$v[0].'-iOS\';"></td>');
			}
			bs::out('</tr>');



			bs::out('<tr>','<td>'.$v[0].'-And</td>');
			bs::out('<td><input autocomplete="off" type="radio" name="chkinfo" onclick="appPrefix=\''.$v[0].'-And\';"></td>');
			bs::out('</tr>');
		}

/*
		foreach( $prefixList as $v ){
			bs::out('<tr>','<td>'.$v[0].'-And</td>');
			bs::out('<td><input autocomplete="off" type="radio" name="chkinfo" onclick="appPrefix=\''.$v[0].'-And\';"></td>');
			bs::out('</tr>');
		}
*/



	}else{
		bs::out( '<tr><td colspan="3">no record</td></tr>' );
	}
?>
</table>
<br/>

<h4>액션 타입 선택</h4>
다운로드<input autocomplete="off" type="radio" name="typeinfo" onclick="actionType=0;" checked="checked"/>
링크<input autocomplete="off" type="radio" name="typeinfo" onclick="actionType=0;"/>
<br/><br/>

<h4>액션 url 입력</h4>
액션 url : <input type=text id='action_url' size=50  maxlength=200 autocomplete="off"/>
<br/><br/>

<!--
배너 시작시간 : <input id="tf_begTime" placeholder="25-Dec-2013 16:36:49" type="text" size="25" autocomplete="off">
<a href="javascript:NewCal('tf_begTime','ddmmmyyyy',true,24)">
<img src="http://dev-infra.fivethirty.co/js/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><br />

배너 마감시간 : <input id="tf_endTime" placeholder="26-Dec-2013 16:36:49" type="text" size="25" autocomplete="off">
<a href="javascript:NewCal('tf_endTime','ddmmmyyyy',true,24)">
<img src="http://dev-infra.fivethirty.co/js/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><br />
-->

<div class='upload_form' style="display:none">
<br /><br />
	<span id="info" style="color:gray"></span>
	<form method="post" enctype="multipart/form-data" action="http://dev-infra.fivethirty.co/uploadImage" id="upload_photo" />
	<input type="file" name="userfile" />
	<br /><br />
	<input type="submit" name="submit" value="Submit">
	</form>
</div>

<button id="selPhoto" onclick="setBannerImage();">배너 이미지 등록 하기 (png/jpg)</button>

<br/><br/>

<h4>이미지공지 리스트</h4>
<table border="1">
<tr>
	<td>앱 네임스페이스</td>
	<td>이미지</td>
	<td>액션 url</td>
	<td>액션 type</td>
	<td>활성화 여부</td>
<!--
	<td>시작시간</td>
	<td>마감시간</td>
-->
	<td>생성시간</td>
	<td>공지삭제</td>
</tr>

<?php
	$noticeList = bs::data('noticeList');
	if( $noticeList ){
		foreach( $noticeList as $v ){
			$url = $v->img_url;
			$filename = explode('/', $url);
			$filename = $filename[count($filename)-1];
			$filename = explode('.', $filename);

			$actionType = $v->action_type == '0' ? '<font color="green">다운로드</font>' :
				'<font color="gray">링크</font>';
			$isActive = $v->is_active == '0' ?
				'<font color="red">비활성 <button id="selPhoto" onclick="activeBanner(1,'.$v->id.');">활성화</button></font>' :
				'<font color="blue">활성 <button id="selPhoto" onclick="activeBanner(0,'.$v->id.');">비활성화</button></font>';

			bs::out(
				'<tr>',
					'<td>'.$v->app_prefix.'</td>',
					'<td><img src="'.$url.'" width=200 height=50/></td>',
					'<td>'.$v->action_url.'</td>',
					'<td>'.$actionType.'</td>',
					'<td>'.$isActive.'</td>',
/*
					'<td>'.$v->start_time.'</td>',
					'<td>'.$v->end_time.'</td>',
*/
					'<td>'.$v->created.'</td>',
					'<td><button onclick=deactivateNotice('.$v->id.',"'.$filename[0].'","'.$filename[1].'")>삭제</button></td>',
				'<tr/>'
			);
		}
	}
?>
</table>

<script>
var appPrefix = '<?php echo bs::data('firstPrefix') ?>';
var actionType = '0';

function activeBanner( isActive, id ){
	console.log(isActive, id);

	var msg = isActive == 0 ? "정말로 이미지를 비활성화 하시겠습니까?" : "정말로 이미지를 활성화 하시겠습니까?";
	var answer = confirm(msg);

	if (answer){
	     sendRequest('http://dev-infra.fivethirty.co/activeImageNotice/'+isActive+'/'+id, null, function(result) {
	     	if(result) {
		     	alert('정상 처리 되었습니다');
		     	location.reload();
	     	} else {
		     	alert('네트워크 에러입니다. 다시시도해 보세요. 문제가 계속된다면 관리자에게 문의하세요');
	     	}
	     });
	}
}

function deactivateNotice(id, filename, ext){
	var answer = confirm("정말로 배너를 삭제하시겠습니까?")
	if (answer){
	     sendRequest('http://dev-infra.fivethirty.co/deleteSelectImageNotice/'+id+'/'+filename+'/'+ext, null, function(result){
	     	if(result) {
		     	alert('정상 처리 되었습니다');
		     	location.reload();
	     	} else {
		     	alert('네트워크 에러입니다. 다시시도해 보세요. 문제가 계속된다면 관리자에게 문의하세요');
	     	}
	     });
	}
}

function setBannerImage(){
	var begTime = $("#tf_begTime").val();
	var endTime = $("#tf_endTime").val();

	if( !$('#action_url').val() ){alert("url을 입력하세요"); return;}
	if( !appPrefix ){alert('prefix를 선택하세요'); return;}
/*
	if( !begTime || !endTime ){alert('시간을 입력하세요'); return;}

	var begTime = new Date( begTime.replace( /-/g, "/" ) );
	var endTime = new Date( endTime.replace( /-/g, "/" ) );

	begTimeStamp = begTime.getTime()/1000;
	endTimeStamp = endTime.getTime()/1000;

	if( endTimeStamp < begTimeStamp ){
		alert('게시시간이 종료시간보다 빨라야합니다');
		return;
	}
*/

	$(".upload_form").removeAttr('style');
	$(".upload_info").attr( 'style', 'display:none' );
	$("#selPhoto").attr( 'style', 'display:none' );

/* 	$("#info").html( "시작시간 : " + begTime + ", 마감시간 : " + endTime ); */

	// 배너 데이터 전송
	var prefix = 'collection';
	$("#upload_photo").attr( "action", "http://dev-infra.fivethirty.co/uploadImage/"+appPrefix+"/"+$('#action_url').val()+"/"+actionType/* +"/"+begTimeStamp+"/"+endTimeStamp */ );

}
</script>

</body>
</html>