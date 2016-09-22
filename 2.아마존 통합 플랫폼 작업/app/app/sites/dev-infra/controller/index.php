<?php
class Controller{
	function __construct(){
		require_once ROOT.'config/utils.php';
		bs::db( 'local', FALSE );
		bs::sql( 'sql', FALSE );
		bs::debug(1);
		ini_set( 'date.timezone', 'Asia/Seoul' );
		if( !SHELL_MODE ) header('Access-Control-Allow-Origin: *');
/* 		bs::table2json( 'reports', 'imageNotices', 'apps', 'parseSNS', 'remotePush' ); */
	}

/*
 *
 *		통합 툴 리스트뷰
 *
 *
 */
	function index(){
/* 		bs::session( 'isLoggedIn', FALSE );  //디버깅용 */

		if( bs::session('isLoggedIn') ){
			bs::data( 'login', TRUE );
			bs::view( 'toolList', FALSE );
		}else bs::view( 'login', FALSE );
	}

	function getToolList(){
		bs::view( 'toolList', FALSE );
	}

	function login() {
		$userName = $_POST['userName'];
		$password = $_POST['password'];

		if($userName == 'fivethirty' && $password == 'eb086c10bd1923eb776934fc820c4b0e') {
			bs::session( 'isLoggedIn', TRUE );
			util::response( HTTP_OK, TRUE );
		} else {
			util::response( HTTP_OK, FALSE );
		}
	}

/*
 *
 *		SNS 파싱
 *
 *
 */
 	function parseSNS(){
	 	util::response( HTTP_OK,  bs::query('parseSNS', array(
	 		'app_prefix'=>$_POST['app_prefix'],
	 		'platform'=>$_POST['platform'],
	 		'app_arn'=>$_POST['appArn']
	 	)));
 	}

 	function getParseSNSJob(){
	 	util::response( HTTP_OK,  bs::query('getParseSNSJob') );
 	}

 	function deleteParseSNSJob($id){
	 	util::response( HTTP_OK, bs::query( 'deleteParseSNSJob', array( 'id'=>$id ) ) );
 	}

 	function onCompleteParseSns($filename){
	 	mail( 'team@fivethirty.kr', 'SNS 유저데이터 파싱 완료', 'dev2 : filename:'.$filename );
 	}

/*
 *
 *		Image Notice
 *
 *
 */
 	function getAppPrefixList(){
	 	echo json_encode( bs::query('getAppPrefix') );
 	}

 	function uploadImageNotice(){
 		bs::data( 'prefixList', bs::query('getAppPrefix') );
 		bs::data( 'noticeList', bs::query('getAllImageNotice') );
 		bs::view('uploadImage', FALSE);
	}

 	function uploadImage($app_prefix, $actionUrl, $actionType/* , $startTime, $endTime */){
	 	// 파일 업로드
	 	$file = NULL;
		if( isset($_FILES) && count($_FILES) ){
			$filename = $_FILES['userfile']['name'];
			$file = bs::upload( 'userfile', '/upload/' );
        }
		if( !$file ) util::response( HTTP_BAD_REQUEST, 'bs::upload Failed' );

		bs::query('uploadImageNotice', array(
			'app_prefix'=>$app_prefix,
			'img_url'=>'http://dev-infra.fivethirty.co/upload/'.$file,
			'action_url'=>$actionUrl,
			'action_type'=>$actionType,
/*
			'start_time'=>$startTime,
			'end_time'=>$endTime,

			// sql query
			//FROM_UNIXTIME(@start_time:imageNotices.start_time@),
			//FROM_UNIXTIME(@end_time:imageNotices.end_time@),
*/
			'created'=>date("Y-m-d H:i:s")
		));

		echo "이미지 업로드 성공";
 	}

 	function getImageNotice(){
	 	$data = bs::query( 'getImageNotice', array( 'app_prefix'=>$_POST['prefix'], 'time'=>date("Y-m-d H:i:s") ) );
	 	util::response( HTTP_OK, $data );
 	}

 	function deleteExpireImageNotice(){
	 	$data = bs::query( 'getExpireImageNotice', array( 'time'=>date("Y-m-d H:i:s") ) );
	 	if($data){
	 		$ids = NULL;
		 	foreach( $data as $v ){
		 		$url = $v->img_url;
				$filename = explode('/', $url);
				$filename = $filename[count($filename)-1];
				exec('rm /usr/srv/app/upload/'.$filename);

		 		$id = $v->id;
			 	$ids = $ids.$id.',';
		 	}
		 	$ids = substr( $ids, 0, -1 );
		 	bs::query( 'deleteExpireImageNotice', array( 'ids'=>$ids ) );
	 	}
 	}

 	function deleteSelectImageNotice($id, $filename, $ext){
	 	$filename = $filename.'.'.$ext;
	 	exec('rm /usr/srv/app/upload/'.$filename);

	 	$res = bs::query( 'deleteImageNotice', array( 'id'=>$id ) );
	 	util::response( HTTP_OK, $res );
 	}

 	function activeImageNotice($isActive, $id){
	 	$res = bs::query( 'activeImageNotice', array( 'id'=>$id, 'is_active'=>$isActive ) );
	 	util::response( HTTP_OK, $res );
 	}

/*
 *
 *		Bug Report
 *
 *
 */
 	function uploadReport(){
/*
 		$t0 = '{"name":"kota","score":"100"}';
	 	var_dump(urlencode($t0));
	 	exit;
*/
 		if( !is_null($_GET['uData']) ){
 			$_GET['uData'] = urldecode($_GET['uData']);
/*
	 		var_dump( urldecode($_GET['uData']) );
	 		var_dump( json_decode($_GET['uData']) );
	 		bs::end();
*/
 		}

 		bs::data( 'prefix', $_GET['appId'] );
 		bs::data( 'appVer', $_GET['appVer'] );
 		bs::data( 'osVer', $_GET['osVer'] );
 		bs::data( 'userData', $_GET['uData'] );
 		bs::data( 'appSchema', $_GET['appSchema'] );
 		bs::data( 'platform', util::parse_user_agent() );
	 	bs::view( 'uploadReport', FALSE );
 	}

 	function saveReport(){
	 	$res = bs::query( 'saveReport', array(
	 		'title'=>$_POST['title'],
	 		'prefix'=>$_POST['prefix'],
	 		'appVer'=>$_POST['appVer'],
	 		'osVer'=>$_POST['osVer'],
	 		'contents'=>$_POST['contents'],
	 		'contact'=>empty($_POST['contact']) ? NULL : $_POST['contact'],
	 		'user_data'=>empty($_POST['userData']) ? NULL : $_POST['userData'],
	 		'created'=>date("Y-m-d H:i:s"),
	 		'platform'=>$_POST['platform']

	 	));
	 	util::response( HTTP_OK, $res);
 	}

/*
 *
 *		Versoin Check
 *
 *
 */
 	function getStoreVersion($prefix){
 		require_once ROOT.'external/simple_html_dom.php';
 		$appInfo = bs::query( 'getAppInfo', array( 'prefix'=>$prefix ) );
 		$iosUrl = $appInfo->ios_url;
 		$andUrl = $appInfo->and_url;
 		$iosVerDB = $appInfo->ios_version;
 		$andVerDB = $appInfo->and_version;

 		if( !is_null($andUrl) ){
	 		$html = file_get_html($andUrl) ? file_get_html($andUrl) : mail('team@fivethirty.kr', '버전 파싱 에러', 'dev-infra : and', $headers);
			foreach( $html->find('div') as $item ){
				if( $item->itemprop == 'softwareVersion' ){
					$andVer = $item->innertext;
					$andVer = trim($andVer);
					break;
				}
			}
			if( bs::debug() ) echo empty($andVer) ? "failed android\n" : 'android	:	'.$andVer."\n";
		}

		if( !is_null($iosUrl) ){
			$html = file_get_html($iosUrl) ? file_get_html($iosUrl) : mail('team@fivethirty.kr', '버전 파싱 에러', 'dev-infra : ios', $headers);
			foreach( $html->find('span.label') as $item ){
				$title = $item->innertext;
				if( strpos( $title, '버전' ) !== FALSE ){
					$iosVer = $item->parent->innertext;
					$iosVer = str_replace( $item, '', $iosVer );
				}
			}
			if( bs::debug() ) echo  empty($iosVer) ? "failed iphone\n" : 'iphone	:	'.$iosVer."\n";
		}

		// 에러일경우 메일 알림
		$headers = 'From: err@fivethirty.kr' . "\r\n" .
		    'Reply-To: err@fivethirty.kr' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		if( !is_null($iosUrl) && empty($iosVer) ) mail('team@fivethirty.kr', '버전 파싱 에러', 'dev-infra : ios', $headers);
		if( !is_null($andUrl) && empty($andVer) ) mail('team@fivethirty.kr', '버전 파싱 에러', 'dev-infra : and', $headers);

		// 데이터베이스 버전 갱신
		if( !empty($iosVer) && $iosVerDB != $iosVer ){
			bs::query( 'setIosAppVersion', array(
				'prefix'=>$prefix,
				'ios_version'=>$iosVer,
				'time'=>date("Y-m-d H:i:s")
			));
		}
		if( !empty($andVer) && $andVerDB != $andVer ){
			bs::query( 'setAndAppVersion', array(
				'prefix'=>$prefix,
				'and_version'=>$andVer,
				'time'=>date("Y-m-d H:i:s")
			));
		}
 	}

 	function getAppVersion(){
	 	$data = bs::query( 'getAppVersion', array( 'prefix'=>$_POST['prefix'] ) );
	 	util::response( HTTP_OK, $data );
 	}



/*
 *
 *		Inapp Purcahse Validator
 *
 *
 */
	function iapValidate(){
		if( $_POST['pl'] == 'ios' ) self::iosIapValidate();
		else if( $_POST['pl'] == 'and' ) self::andIapValidate();
	}

	private function iosIapValidate(){
		require_once ROOT.'external/itunesReceiptValidator.php';
		$endpoint = $_POST['sandbox'] == 1 ? itunesReceiptValidator::SANDBOX_URL : itunesReceiptValidator::PRODUCTION_URL;
		try{
		    $rv = new itunesReceiptValidator( $endpoint, $_POST['receipt'] );
/* 			$environment = $rv->getEndpoint() === itunesReceiptValidator::SANDBOX_URL) ? 'Sandbox' : 'Production'; */
		    $res = $rv->validateReceipt();
		}catch( Exception $e ){
			if( bs::debug() ) echo $e->getMessage();
            $res = FALSE;
		}
		util::response( HTTP_OK, $res );
	}


/*
구글서버에서 받은 영수증 데이터는 다음과 같은 json포맷의 형태를 리턴한다
"original_json": "{\"orderId\":\"12999763169054705758.1365836733911956\",\"packageName\":\"com.android.fuck.you\",\"productId\":\"jewel_1\",\"purchaseTime\":1396597930594,\"purchaseState\":0,\"purchaseToken\":\"blahblablahblah"}",
"signature": "blahblahblah"
*/
	private function andIapValidate(){
		$signed_data = $_POST['signedData'];					// 구글서버로부터 응답받은 원본 json 형태의 영수증 정보
		$signature = $_POST['signature'];						// 구글서버로부터 응답받은 시그니쳐값.
		$public_key_base64 = $_POST['publicKeyBase64'];			// 구글에서 앱마다 제공해주는 라이센스 키

/* 		bs::file('/usr/srv/app/upload/test.txt', "제이슨:".$signed_data."\n시그니쳐:".$signature."\n퍼블릭키:".$public_key_base64."\n"); */

		$key = "-----BEGIN PUBLIC KEY-----\n".chunk_split($public_key_base64, 64,"\n").'-----END PUBLIC KEY-----';
		$key = openssl_get_publickey($key); 					//using PHP to create an RSA key
		if( false === $key ) util::response( HTTP_OK, 'error openssl_get_publickey' );

		if( !$this->is_base64($signature) ) util::response( HTTP_OK, 'signature is not base64 encoding' );
		$signature = base64_decode($signature);

		$res = openssl_verify( $signed_data, $signature, $key, OPENSSL_ALGO_SHA1 );
		if( 0 === $res ) $res = FALSE;
		else if( 1 !== $res ) $res = TRUE;
		else $res = TRUE;

		util::response( HTTP_OK, $res );
	}
	private function is_base64($s){return (bool)preg_match( '/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s );}
}